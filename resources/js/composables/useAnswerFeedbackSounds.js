let audioContext = null
let buffersPromise = null
let feedbackChain = null

const bufferDefinitions = {
  send: [{ frequency: 720, duration: 0.055, type: 'sine' }],
  good: [
    { frequency: 740, duration: 0.08, delay: 0, type: 'sine' },
    { frequency: 988, duration: 0.1, delay: 0.07, type: 'sine' },
  ],
  almost: [{ frequency: 520, duration: 0.11, type: 'triangle' }],
  bad: [
    { frequency: 360, duration: 0.085, delay: 0, type: 'bite', envelope: 2.4 },
    { frequency: 250, duration: 0.095, delay: 0.075, type: 'bite', envelope: 2.4 },
  ],
}

const typeGainMultipliers = {
  send: 0.75,
  good: 0.9,
  almost: 1,
  bad: 0.38,
}

const typeGainCaps = {
  send: 0.44,
  good: 0.44,
  almost: 0.44,
  bad: 0.1,
}

function getAudioContext() {
  if (!audioContext) {
    audioContext = new (window.AudioContext || window.webkitAudioContext)()
  }

  return audioContext
}

function getFeedbackChain(context) {
  if (feedbackChain?.context === context) {
    return feedbackChain
  }

  const input = context.createGain()
  const compressor = context.createDynamicsCompressor()

  compressor.threshold.value = -10
  compressor.knee.value = 18
  compressor.ratio.value = 4
  compressor.attack.value = 0.002
  compressor.release.value = 0.12

  input.connect(compressor)
  compressor.connect(context.destination)

  feedbackChain = { context, input, compressor }

  return feedbackChain
}

function getMasterVolume() {
  const stored = localStorage.getItem('volume')

  if (stored === null || stored === '') {
    return 1
  }

  const parsed = parseFloat(stored)

  return Number.isFinite(parsed) ? parsed : 1
}

export function areAnswerSoundsEnabled() {
  return localStorage.getItem('answerSounds') !== 'false'
}

function getFeedbackGain(type) {
  const master = getMasterVolume()

  if (master <= 0) {
    return 0
  }

  const base = 0.18 + master * 0.2
  const multiplier = typeGainMultipliers[type] ?? 1

  return Math.min(typeGainCaps[type] ?? 0.44, base * multiplier)
}

function renderToneBuffer(context, { frequency, duration, type = 'sine', delay = 0, envelope = 1 }) {
  const sampleRate = context.sampleRate
  const totalDuration = delay + duration
  const frameCount = Math.max(1, Math.ceil(sampleRate * totalDuration))
  const buffer = context.createBuffer(1, frameCount, sampleRate)
  const channel = buffer.getChannelData(0)
  const delayFrames = Math.floor(delay * sampleRate)
  const toneFrames = Math.max(1, Math.ceil(duration * sampleRate))
  let peak = 0

  for (let i = 0; i < toneFrames; i += 1) {
    const t = i / sampleRate
    const frame = delayFrames + i
    const envelopeValue = Math.pow(1 - i / toneFrames, envelope)
    let sample = 0

    if (type === 'triangle') {
      sample = (2 / Math.PI) * Math.asin(Math.sin(2 * Math.PI * frequency * t))
    } else if (type === 'square') {
      sample = Math.sin(2 * Math.PI * frequency * t) >= 0 ? 1 : -1
    } else if (type === 'bite') {
      const triangle = (2 / Math.PI) * Math.asin(Math.sin(2 * Math.PI * frequency * t))
      const square = Math.sin(2 * Math.PI * frequency * t) >= 0 ? 1 : -1

      sample = triangle * 0.5 + square * 0.5
    } else {
      sample = Math.sin(2 * Math.PI * frequency * t)
    }

    channel[frame] = sample * envelopeValue
    peak = Math.max(peak, Math.abs(channel[frame]))
  }

  if (peak > 0) {
    const scale = 0.98 / peak

    for (let i = 0; i < channel.length; i += 1) {
      channel[i] *= scale
    }
  }

  return buffer
}

async function ensureBuffers() {
  if (buffersPromise) {
    return buffersPromise
  }

  buffersPromise = (async () => {
    const context = getAudioContext()
    const rendered = {}

    for (const [name, tones] of Object.entries(bufferDefinitions)) {
      rendered[name] = tones.map((tone) => renderToneBuffer(context, tone))
    }

    return rendered
  })()

  return buffersPromise
}

export function primeAnswerFeedbackAudio() {
  if (!areAnswerSoundsEnabled() || getMasterVolume() <= 0) {
    return false
  }

  try {
    const context = getAudioContext()
    getFeedbackChain(context)

    if (context.state === 'suspended') {
      void context.resume()
    }

    void ensureBuffers()

    return true
  } catch (error) {
    console.warn('Answer feedback audio prime failed:', error)
    buffersPromise = null

    return false
  }
}

async function ensureAudioRunning() {
  const context = getAudioContext()

  if (context.state === 'running') {
    return true
  }

  if (context.state === 'closed') {
    return false
  }

  try {
    await context.resume()

    return context.state === 'running'
  } catch (error) {
    console.warn('Answer feedback audio resume failed:', error)

    return false
  }
}

async function playBufferSet(name) {
  const feedbackGain = getFeedbackGain(name)

  if (!areAnswerSoundsEnabled() || feedbackGain <= 0) {
    return
  }

  primeAnswerFeedbackAudio()

  const running = await ensureAudioRunning()

  if (!running) {
    return
  }

  try {
    const context = getAudioContext()
    const { input } = getFeedbackChain(context)
    const buffers = await ensureBuffers()
    const clips = buffers[name] ?? []

    clips.forEach((buffer, index) => {
      const source = context.createBufferSource()
      const gainNode = context.createGain()

      source.buffer = buffer
      gainNode.gain.value = feedbackGain
      source.connect(gainNode)
      gainNode.connect(input)
      source.start(context.currentTime + index * 0.001)
    })
  } catch (error) {
    console.warn(`Answer feedback sound "${name}" failed:`, error)
  }
}

export function useAnswerFeedbackSounds() {
  return {
    playSend: () => {
      void playBufferSet('send')
    },
    playGood: () => {
      void playBufferSet('good')
    },
    playAlmost: () => {
      void playBufferSet('almost')
    },
    playBad: () => {
      void playBufferSet('bad')
    },
    primeAnswerFeedbackAudio,
    previewBad: () => {
      void playBufferSet('bad')
    },
  }
}
