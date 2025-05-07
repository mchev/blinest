<script setup>
import { useForm } from '@inertiajs/vue3'
import Modal from '@/Components/Modal.vue'
import TextInput from '@/Components/TextInput.vue'
import FileInput from '@/Components/FileInput.vue'
import { ref, watch, onMounted, computed, nextTick, onUnmounted } from 'vue'

const props = defineProps({
    playlist: {
        type: Object,
        required: true
    }
})

const emit = defineEmits(['new-track-uploaded'])

const uploadingTrack = ref(false)
const audioPreview = ref(null)
const audioDuration = ref(0)
const segmentStart = ref(0)
const segmentEnd = ref(30)
const isPlaying = ref(false)
const currentTime = ref(0)
const waveformData = ref([])
const isDragging = ref(false)
const dragType = ref(null) // 'start', 'end', or 'window'
const waveformContainer = ref(null)
const dragStartX = ref(0)
const dragStartSegment = ref(0)
const processingAudio = ref(false)
const artworkPreview = ref(null)
const isGeneratingWaveform = ref(false)
const audioPreviewUrl = ref(null)

const form = useForm({
    artist_name: '',
    track_name: '',
    audio: null,
    artwork: null,
    start_time: 0
})

// Computed properties for better readability
const segmentStartPercentage = computed(() => 
    (segmentStart.value / audioDuration.value) * 100
)

const segmentWidthPercentage = computed(() => 
    (30 / audioDuration.value) * 100
)

const currentTimeFormatted = computed(() => 
    formatTime(currentTime.value)
)

const segmentStartFormatted = computed(() => 
    formatTime(segmentStart.value)
)

const segmentEndFormatted = computed(() => 
    formatTime(segmentStart.value + 30)
)

const audioDurationFormatted = computed(() => 
    formatTime(audioDuration.value)
)

// Format time in MM:SS format
function formatTime(timeInSeconds) {
    const minutes = Math.floor(timeInSeconds / 60)
    const seconds = Math.floor(timeInSeconds % 60)
    return `${minutes}:${seconds.toString().padStart(2, '0')}`
}

// Generate waveform data from audio
const generateWaveform = async (audioFile) => {
    try {
        isGeneratingWaveform.value = true
        // Create placeholder waveform with random values
        waveformData.value = Array(100).fill().map(() => Math.random() * 0.3 + 0.1)
        
        const audioContext = new (window.AudioContext || window.webkitAudioContext)()
        const arrayBuffer = await audioFile.arrayBuffer()
        const audioBuffer = await audioContext.decodeAudioData(arrayBuffer)
        
        // Get the audio data
        const channelData = audioBuffer.getChannelData(0)
        const samples = 100 // Number of points in our waveform
        const blockSize = Math.floor(channelData.length / samples)
        const waveform = []
        
        for (let i = 0; i < samples; i++) {
            let start = blockSize * i
            let sum = 0
            for (let j = 0; j < blockSize; j++) {
                sum += Math.abs(channelData[start + j])
            }
            waveform.push(sum / blockSize)
        }
        
        // Normalize the waveform data
        const max = Math.max(...waveform)
        waveformData.value = waveform.map(value => value / max)
        isGeneratingWaveform.value = false
    } catch (error) {
        console.error('Erreur lors de la génération de la forme d\'onde:', error)
        waveformData.value = Array(100).fill(0.5) // Fallback to flat waveform
        isGeneratingWaveform.value = false
    }
}

// Extract 30s segment using Web Audio API
async function extract30sSegment(file, start, duration = 30) {
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)()
        const arrayBuffer = await file.arrayBuffer()
        const audioBuffer = await audioContext.decodeAudioData(arrayBuffer)
        
        // Create a new buffer for the 30s segment
        const sampleRate = audioBuffer.sampleRate
        const startSample = Math.floor(start * sampleRate)
        const durationSamples = Math.floor(duration * sampleRate)
        const newBuffer = audioContext.createBuffer(
            audioBuffer.numberOfChannels,
            durationSamples,
            sampleRate
        )
        
        // Copy the selected portion to the new buffer
        for (let channel = 0; channel < audioBuffer.numberOfChannels; channel++) {
            const channelData = audioBuffer.getChannelData(channel)
            const newChannelData = newBuffer.getChannelData(channel)
            for (let i = 0; i < durationSamples; i++) {
                newChannelData[i] = channelData[startSample + i]
            }
        }

        // Get channel data
        const leftChannel = newBuffer.getChannelData(0)
        const rightChannel = newBuffer.getChannelData(1)
        
        // Find the maximum amplitude across both channels
        let maxAmplitude = 0
        for (let i = 0; i < leftChannel.length; i++) {
            maxAmplitude = Math.max(maxAmplitude, Math.abs(leftChannel[i]), Math.abs(rightChannel[i]))
        }
        
        // Calculate normalization factor (target peak at -1dB)
        const targetPeak = 0.89 // -1dB
        const normalizationFactor = maxAmplitude > 0 ? targetPeak / maxAmplitude : 1
        
        // Apply normalization
        for (let i = 0; i < leftChannel.length; i++) {
            leftChannel[i] *= normalizationFactor
            rightChannel[i] *= normalizationFactor
        }

        // Convert to MP3 using lamejs
        const mp3encoder = new window.lamejs.Mp3Encoder(2, sampleRate, 128)
        const mp3Data = []
        
        // Convert float32 to int16
        const sampleBlockSize = 1152 // must be multiple of 576
        
        for (let i = 0; i < leftChannel.length; i += sampleBlockSize) {
            const leftChunk = new Int16Array(sampleBlockSize)
            const rightChunk = new Int16Array(sampleBlockSize)
            
            // Convert float32 to int16 for both channels
            for (let j = 0; j < sampleBlockSize && (i + j) < leftChannel.length; j++) {
                leftChunk[j] = leftChannel[i + j] * 0x7FFF
                rightChunk[j] = rightChannel[i + j] * 0x7FFF
            }
            
            const mp3buf = mp3encoder.encodeBuffer(leftChunk, rightChunk)
            if (mp3buf.length > 0) {
                mp3Data.push(mp3buf)
            }
        }
        
        const end = mp3encoder.flush()
        if (end.length > 0) {
            mp3Data.push(end)
        }
        
        const mp3Blob = new Blob(mp3Data, { type: 'audio/mp3' })
        return new File([mp3Blob], 'segment.mp3', { type: 'audio/mp3' })
    } catch (error) {
        console.error('Erreur lors de l\'extraction du segment audio:', error)
        throw error
    }
}

// Watch for audio file changes
watch(() => form.audio, async (newFile) => {
    if (newFile) {
        try {
            // Clean up previous audio preview URL if it exists
            if (audioPreviewUrl.value) {
                URL.revokeObjectURL(audioPreviewUrl.value)
            }
            
            audioPreviewUrl.value = URL.createObjectURL(newFile)
            const audio = new Audio(audioPreviewUrl.value)
            audioPreview.value = audio
            
            audio.addEventListener('loadedmetadata', () => {
                audioDuration.value = audio.duration
                segmentEnd.value = Math.min(30, audio.duration)
            })
            
            audio.addEventListener('timeupdate', () => {
                currentTime.value = audio.currentTime
                if (audio.currentTime >= segmentStart.value + 30) {
                    audio.pause()
                    isPlaying.value = false
                }
            })
            
            audio.addEventListener('ended', () => {
                isPlaying.value = false
            })

            await generateWaveform(newFile)
        } catch (error) {
            console.error('Erreur lors du chargement de l\'audio:', error)
        }
    }
})

// Watch for artwork file changes
watch(() => form.artwork, (newFile) => {
    if (newFile) {
        artworkPreview.value = URL.createObjectURL(newFile)
    } else {
        artworkPreview.value = null
    }
})

const playPreview = () => {
    if (!audioPreview.value) return
    
    if (isPlaying.value) {
        audioPreview.value.pause()
        isPlaying.value = false
    } else {
        audioPreview.value.play()
        isPlaying.value = true
    }
}

const playFromRange = () => {
    if (!audioPreview.value) return
    // Force the audio to start from the beginning of the selected range
    audioPreview.value.currentTime = segmentStart.value
    audioPreview.value.play()
    isPlaying.value = true
}

const updateSegmentStart = (newStart) => {
    if (newStart < 0) newStart = 0
    if (newStart > audioDuration.value - 30) newStart = audioDuration.value - 30
    segmentStart.value = newStart
    segmentEnd.value = newStart + 30
    form.start_time = newStart
}

const handleDragStart = (type, event) => {
    isDragging.value = true
    dragType.value = type
    dragStartX.value = (event.touches ? event.touches[0].clientX : event.clientX)
    dragStartSegment.value = segmentStart.value
    document.addEventListener('mousemove', handleDrag)
    document.addEventListener('mouseup', handleDragEnd)
    document.addEventListener('touchmove', handleDrag)
    document.addEventListener('touchend', handleDragEnd)
}

const handleDrag = (event) => {
    if (!isDragging.value || !waveformContainer.value) return
    const containerRect = waveformContainer.value.getBoundingClientRect()
    const clientX = event.touches ? event.touches[0].clientX : event.clientX
    const deltaX = clientX - dragStartX.value
    const percentDelta = deltaX / containerRect.width
    const timeDelta = percentDelta * audioDuration.value

    if (dragType.value === 'window') {
        let newStart = dragStartSegment.value + timeDelta
        newStart = Math.max(0, Math.min(newStart, audioDuration.value - 30))
        segmentStart.value = newStart
        segmentEnd.value = newStart + 30
        form.start_time = newStart
    } else {
        const x = (event.clientX || event.touches[0].clientX) - containerRect.left
        const percentage = Math.max(0, Math.min(1, x / containerRect.width))
        const newTime = percentage * audioDuration.value
        if (dragType.value === 'start') {
            // Prevent overlap and out-of-bounds
            let start = Math.max(0, Math.min(newTime, audioDuration.value - 30))
            segmentStart.value = start
            segmentEnd.value = start + 30
            form.start_time = start
        } else if (dragType.value === 'end') {
            // Not needed, always 30s window
        }
    }
}

const handleDragEnd = async () => {
    if (!isDragging.value) return

    isDragging.value = false
    dragType.value = null
    document.removeEventListener('mousemove', handleDrag)
    document.removeEventListener('mouseup', handleDragEnd)
    document.removeEventListener('touchmove', handleDrag)
    document.removeEventListener('touchend', handleDragEnd)

    if (audioPreview.value) {
        audioPreview.value.pause()
        // Force the currentTime to the start of the range, then play after a short delay
        audioPreview.value.currentTime = segmentStart.value
        setTimeout(() => {
            audioPreview.value.play()
            isPlaying.value = true
        }, 50)
    }
}

// Add click handler for the waveform to set playback position
const handleWaveformClick = (event) => {
    if (!audioPreview.value || isDragging.value) return
    
    const containerRect = waveformContainer.value.getBoundingClientRect()
    const x = event.clientX - containerRect.left
    const percentage = Math.max(0, Math.min(1, x / containerRect.width))
    const newTime = percentage * audioDuration.value
    
    audioPreview.value.currentTime = newTime
    if (!isPlaying.value) {
        audioPreview.value.play()
        isPlaying.value = true
    }
}

const uploadTrack = async () => {
    if (!form.audio) {
        form.setError('audio', 'Veuillez sélectionner un fichier audio')
        return
    }
    
    if (form.audio && segmentStart.value !== undefined) {
        processingAudio.value = true
        form.processing = true
        
        try {
            form.audio = await extract30sSegment(form.audio, segmentStart.value, 30)
        } catch (e) {
            form.processing = false
            processingAudio.value = false
            form.setError('message', 'Erreur lors du traitement audio: ' + e.message)
            return
        }
        
        processingAudio.value = false
    }
    
    form.post(route('local-tracks.store'), {
        onSuccess: () => {
            uploadingTrack.value = false
            emit('new-track-uploaded')
            form.reset()
            resetAudio()
        },
        onError: (errors) => {
            console.error('Erreurs lors du téléchargement:', errors)
        }
    })
}

const resetAudio = () => {
    if (audioPreview.value) {
        audioPreview.value.pause()
        audioPreview.value = null
    }
    if (audioPreviewUrl.value) {
        URL.revokeObjectURL(audioPreviewUrl.value)
        audioPreviewUrl.value = null
    }
    if (artworkPreview.value) {
        URL.revokeObjectURL(artworkPreview.value)
        artworkPreview.value = null
    }
    waveformData.value = []
    audioDuration.value = 0
    segmentStart.value = 0
    segmentEnd.value = 30
    isPlaying.value = false
    currentTime.value = 0
}

const close = () => {
    uploadingTrack.value = false
    form.reset()
    form.clearErrors()
    resetAudio()
}

// Add cleanup on component unmount
onUnmounted(() => {
    resetAudio()
})
</script>

<template>
    <div>
        <button 
            class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blinest-600 to-blinest-500 hover:from-blinest-500 hover:to-blinest-400 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200"
            @click="uploadingTrack = true"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15m0-3-3-3m0 0-3 3m3-3V15" />
            </svg>
            <span>Importer un audio</span>
        </button>

        <Modal :show="uploadingTrack" @close="close" max-width="2xl">
            <div class="p-6 bg-neutral-900 rounded-lg">
                <h1 class="text-2xl font-bold text-white mb-1">Importer un audio</h1>
                <p class="text-sm text-neutral-400 mb-6">Tous les champs sont obligatoires. Vous pouvez trouver des mp3 à l'adresse suivante : <a href="https://emp3juice.la/" target="_blank" class="text-blinest-400 hover:text-blinest-300 underline transition-colors">emp3juice.la</a></p>
                
                <form @submit.prevent="uploadTrack" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <TextInput 
                            v-model="form.artist_name" 
                            label="Nom de l'artiste" 
                            id="artist_name" 
                            :error="form.errors.artist_name"
                            required
                            class="w-full"
                        />
                        <TextInput 
                            v-model="form.track_name" 
                            label="Titre de la chanson" 
                            id="track_name" 
                            :error="form.errors.track_name"
                            required
                            class="w-full"
                        />
                    </div>
                    
                    <div class="p-4 border border-neutral-700 rounded-lg bg-neutral-800/50">
                        <FileInput 
                            v-model="form.audio" 
                            label="Audio" 
                            id="audio" 
                            accept="audio/mp3"
                            :error="form.errors.audio"
                            required
                        />
                        <p class="mt-2 text-sm text-red-400 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                            Attention, les fichiers audio doivent être au format MP3.
                        </p>
                    </div>

                    <!-- Audio Preview and Waveform Selector -->
                    <div v-if="form.audio" class="p-5 border border-neutral-700 rounded-lg bg-neutral-800/50 space-y-4">
                        <h3 class="text-lg font-medium text-white mb-3">Sélection de l'extrait</h3>
                        <div class="flex items-center gap-4">
                            <button 
                                type="button"
                                @click="playPreview"
                                class="flex items-center justify-center w-12 h-12 rounded-full bg-neutral-700 hover:bg-neutral-600 transition-colors shadow-lg hover:shadow-neutral-500/20"
                                :disabled="!audioPreview"
                                :class="{ 'opacity-50': !audioPreview }"
                            >
                                <svg v-if="!isPlaying" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" />
                                </svg>
                                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25v13.5m-7.5-13.5v13.5" />
                                </svg>
                            </button>
                            
                            <!-- Waveform Container -->
                            <div 
                                ref="waveformContainer"
                                class="w-full flex-1 relative h-20 bg-neutral-900 rounded-xl overflow-hidden cursor-pointer shadow-inner"
                                @click="handleWaveformClick"
                            >
                                <!-- Loading Indicator -->
                                <div v-if="isGeneratingWaveform" class="absolute inset-0 flex items-center justify-center bg-neutral-800/70 backdrop-blur-sm z-10">
                                    <div class="flex flex-col items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-6 animate-spin text-blinest-500">
                                            <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                                        </svg>
                                        <span class="text-xs text-white mt-2">Génération de la forme d'onde...</span>
                                    </div>
                                </div>
                                
                                <!-- Waveform -->
                                <div class="absolute inset-0 flex items-end gap-[1px] w-full h-full pointer-events-none">
                                    <div 
                                        v-for="(value, index) in waveformData" 
                                        :key="index"
                                        class="flex-1 bg-gradient-to-t from-neutral-500/80 to-neutral-400/50 rounded-full transition-all duration-300"
                                        :class="{ 'animate-pulse': isGeneratingWaveform }"
                                        :style="{ height: `${value * 100}%` }"
                                    ></div>
                                </div>
                                
                                <!-- Selection Window (orange/yellow highlight, draggable) -->
                                <div 
                                    class="absolute inset-y-0 border-2 border-yellow-400 bg-yellow-300/20 cursor-grab active:cursor-grabbing transition-all duration-100"
                                    :style="{
                                        left: `${segmentStartPercentage}%`,
                                        width: `${segmentWidthPercentage}%`
                                    }"
                                    @mousedown.stop="handleDragStart('window', $event)"
                                    @touchstart.stop="handleDragStart('window', $event)"
                                ></div>
                                
                                <!-- Start Handle -->
                                <div 
                                    class="absolute top-0 bottom-0 w-1 bg-yellow-400 cursor-ew-resize"
                                    :style="{ left: `${segmentStartPercentage}%` }"
                                    @mousedown.stop="handleDragStart('start', $event)"
                                    @touchstart.stop="handleDragStart('start', $event)"
                                >
                                    <div class="absolute -top-1 -left-1.5 w-4 h-4 bg-yellow-400 border-2 border-white rounded-full shadow-lg"></div>
                                </div>
                                
                                <!-- End Handle (not draggable, just visual) -->
                                <div 
                                    class="absolute top-0 bottom-0 w-1 bg-yellow-400"
                                    :style="{ left: `${(segmentStartPercentage + segmentWidthPercentage)}%` }"
                                >
                                    <div class="absolute -top-1 -left-1.5 w-4 h-4 bg-yellow-400 border-2 border-white rounded-full shadow-lg"></div>
                                </div>
                                
                                <!-- Playhead -->
                                <div 
                                    v-if="isPlaying"
                                    class="absolute top-0 bottom-0 w-0.5 bg-white shadow-[0_0_5px_rgba(255,255,255,0.5)]"
                                    :style="{ left: `${((currentTime) / audioDuration) * 100}%` }"
                                ></div>

                                <!-- Time Markers -->
                                <div class="absolute bottom-0 left-0 right-0 flex justify-between px-2 text-[10px] text-neutral-500 pointer-events-none">
                                    <span>0:00</span>
                                    <span>{{ audioDurationFormatted }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Current Time and Selected Segment -->
                        <div class="flex justify-between text-xs text-neutral-400 mt-2 bg-neutral-900/50 p-2 rounded-lg">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Position: {{ currentTimeFormatted }}
                            </span>
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 9.563C9 9.252 9.252 9 9.563 9h4.874c.311 0 .563.252.563.563v4.874c0 .311-.252.563-.563.563H9.564A.562.562 0 019 14.437V9.564z" />
                                </svg>
                                Sélection: {{ segmentStartFormatted }} - {{ segmentEndFormatted }}
                            </span>
                        </div>
                    </div>

                    <div class="p-4 border border-neutral-700 rounded-lg bg-neutral-800/50">
                        <FileInput 
                            v-model="form.artwork" 
                            label="Pochette de l'album" 
                            id="artwork" 
                            :error="form.errors.artwork"
                            accept="image/*"
                            required
                        />
                        <p class="mt-2 text-sm text-red-400 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                            Les fichiers images doivent être au format PNG, JPG ou JPEG et faire une taille inférieure à 2MB.
                        </p>
                        
                        <!-- Artwork Preview -->
                        <div v-if="artworkPreview" class="mt-4 flex items-center gap-3">
                            <div class="relative w-24 h-24 rounded-lg overflow-hidden shadow-lg border border-neutral-600">
                                <img 
                                    :src="artworkPreview" 
                                    alt="Aperçu de la pochette" 
                                    class="w-full h-full object-cover"
                                />
                            </div>
                            <div class="text-sm text-neutral-400">
                                <p class="font-medium text-white mb-1">Aperçu de la pochette</p>
                                <p>Cette image sera utilisée pour l'affichage de la chanson dans la playlist.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div v-if="form.errors.message" class="p-3 bg-red-500/20 border border-red-500/50 rounded-lg text-red-400 text-sm">
                        {{ form.errors.message }}
                    </div>
                    
                    <div class="flex justify-end">
                        <button 
                            type="submit" 
                            class="flex items-center gap-2 px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-neutral-600 to-neutral-500 hover:from-neutral-500 hover:to-neutral-400 rounded-lg shadow-lg hover:shadow-neutral-500/20 transform hover:translate-y-[-2px] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                            :disabled="form.processing || processingAudio"
                        >
                            <svg v-if="form.processing || processingAudio" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4 mr-2 animate-spin">
                                <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                            </svg>
                            <span v-if="processingAudio">Traitement audio en cours...</span>
                            <span v-else-if="form.processing">Téléchargement en cours...</span>
                            <span v-else>Importer l'extrait dans la bibliothèque Blinest</span>
                        </button>
                    </div>
                </form>
            </div>
        </Modal>
    </div>
</template>