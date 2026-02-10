<script setup>
import { ref, onMounted } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import axios from 'axios'
import AppLayout from '@/Layouts/AppLayout.vue'
import Icon from '@/Components/Icon.vue'
import { pickWrongMessageKey } from '../shared/wrongMessageKeys.js'

const QUESTIONS_PER_ROUND = 5

const nextUrl = route('minigames.album_cover.next')
const checkUrl = route('minigames.album_cover.check')
const backUrl = route('minigames.index')
const homeUrl = route('home')

const loading = ref(true)
const error = ref(null)
const roundTracks = ref([])
const currentQuestionIndex = ref(0)
const result = ref(null)
const sessionScore = ref(0)
const checking = ref(false)
const roundResults = ref([])
const showSummary = ref(false)
const wrongMessageKey = ref('')

const track = ref(null)
const choices = ref([])
const correctValue = ref(null)

/** Puzzle effect for the cover: 'blur' | 'zoom' | 'both' — chosen at random per question */
const puzzleMode = ref('blur')
/** For zoom: one of 5 positions (percent translate). Revealed after answer. */
const ZOOM_POSITIONS = [
  { x: -25, y: -25 },
  { x: 25, y: -25 },
  { x: -25, y: 25 },
  { x: 25, y: 25 },
  { x: 0, y: 0 },
]
const zoomPosition = ref(ZOOM_POSITIONS[0])

function applyQuestion(index) {
  const q = roundTracks.value[index]
  if (!q) return
  track.value = q.track
  choices.value = q.choices || []
  correctValue.value = q.correct_value ?? null
  result.value = null
  wrongMessageKey.value = ''
  const modes = ['blur', 'zoom', 'both']
  puzzleMode.value = modes[Math.floor(Math.random() * modes.length)]
  zoomPosition.value = ZOOM_POSITIONS[Math.floor(Math.random() * ZOOM_POSITIONS.length)]
}

async function preloadRound() {
  loading.value = true
  error.value = null
  roundTracks.value = []
  roundResults.value = []
  sessionScore.value = 0
  showSummary.value = false
  currentQuestionIndex.value = 0
  try {
    const responses = await Promise.all(
      Array.from({ length: QUESTIONS_PER_ROUND }, () => axios.post(nextUrl)),
    )
    roundTracks.value = responses.map((r) => r.data)
    if (roundTracks.value.length > 0) {
      applyQuestion(0)
    } else {
      error.value = __('Failed to load questions.')
    }
  } catch (e) {
    error.value = e.response?.data?.error || e.message || __('Failed to load question.')
  } finally {
    loading.value = false
  }
}

async function submitChoice(chosenValueStr) {
  if (!track.value || checking.value) return
  checking.value = true
  try {
    const { data } = await axios.post(checkUrl, {
      track_id: track.value.id,
      chosen_value: chosenValueStr,
    })
    result.value = data
    if (!data.correct) wrongMessageKey.value = pickWrongMessageKey()
    if (data.correct) {
      sessionScore.value += data.points || 0
    }
    roundResults.value.push({
      track: { id: track.value.id, artwork_url: track.value.artwork_url },
      correctValue: data.correct_value,
      chosenValue: chosenValueStr,
      correct: data.correct,
      points: data.points || 0,
    })
  } catch (e) {
    wrongMessageKey.value = pickWrongMessageKey()
    result.value = { correct: false, correct_value: correctValue.value, points: 0 }
    roundResults.value.push({
      track: { id: track.value.id, artwork_url: track.value.artwork_url },
      correctValue: correctValue.value,
      chosenValue: chosenValueStr,
      correct: false,
      points: 0,
    })
  } finally {
    checking.value = false
  }
}

function nextQuestion() {
  if (roundResults.value.length >= QUESTIONS_PER_ROUND) {
    showSummary.value = true
    return
  }
  currentQuestionIndex.value += 1
  if (currentQuestionIndex.value >= roundTracks.value.length) {
    showSummary.value = true
    return
  }
  applyQuestion(currentQuestionIndex.value)
}

onMounted(() => {
  preloadRound()
})
</script>

<template>
  <Head :title="__('Album cover')" />
  <AppLayout>
    <div class="mx-auto max-w-xl px-4 py-8">
      <header class="mb-6">
        <div class="flex items-center justify-between">
          <Link
            :href="backUrl"
            class="inline-flex items-center gap-2 text-sm text-neutral-400 transition hover:text-white"
          >
            <Icon name="cheveron-right" class="h-4 w-4 rotate-180" />
            {{ __('Mini-games') }}
          </Link>
          <span
            v-if="sessionScore >= 0 && !showSummary && track"
            class="rounded-full border-2 border-teal-500/50 bg-teal-500/20 px-4 py-1.5 text-sm font-bold text-teal-400 shadow-[0_0_20px_rgba(20,184,166,0.2)]"
          >
            {{ sessionScore }} {{ __('points') }}
          </span>
        </div>
        <div v-if="track && !showSummary" class="mt-4">
          <div class="flex justify-between text-xs font-medium uppercase tracking-wider text-neutral-500">
            <span>{{ __('Question') }} {{ currentQuestionIndex + 1 }} / {{ QUESTIONS_PER_ROUND }}</span>
          </div>
          <div class="mt-2 flex gap-1">
            <div
              v-for="i in QUESTIONS_PER_ROUND"
              :key="i"
              class="h-2 flex-1 overflow-hidden rounded-full bg-neutral-800"
            >
              <div
                class="h-full rounded-full transition-all duration-300"
                :class="i <= currentQuestionIndex + 1 ? 'bg-gradient-to-r from-teal-500 to-cyan-400 shadow-[0_0_10px_rgba(20,184,166,0.5)]' : ''"
                :style="i <= currentQuestionIndex + 1 ? { width: '100%' } : { width: '0%' }"
              />
            </div>
          </div>
        </div>
      </header>

      <div v-if="showSummary" class="space-y-6">
        <div class="overflow-hidden rounded-2xl border-2 border-amber-500/30 bg-neutral-900/90 shadow-2xl shadow-amber-500/10 ring-1 ring-white/5">
          <div class="bg-gradient-to-br from-amber-600/30 via-neutral-800 to-teal-600/20 px-6 py-8 text-center">
            <h2 class="text-2xl font-black uppercase tracking-widest text-white drop-shadow-lg">
              {{ __('Round over!') }}
            </h2>
            <p class="mt-3 text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-amber-400">
              {{ sessionScore }} <span class="text-xl font-bold text-neutral-400">{{ __('points') }}</span>
            </p>
          </div>
          <div class="divide-y divide-neutral-700/50">
            <div
              v-for="(r, index) in roundResults"
              :key="index"
              class="flex items-center gap-4 px-6 py-4"
            >
              <img
                v-if="r.track.artwork_url"
                :src="r.track.artwork_url"
                alt=""
                class="h-14 w-14 flex-shrink-0 rounded-lg border-2 border-neutral-600 object-cover"
              />
              <div v-else class="h-14 w-14 flex-shrink-0 rounded-lg border-2 border-neutral-700 bg-neutral-800" />
              <div class="min-w-0 flex-1">
                <p class="font-bold text-neutral-200">
                  {{ r.correctValue || __('Track') }} #{{ index + 1 }}
                </p>
                <p v-if="!r.correct && r.correctValue" class="text-sm text-neutral-400">
                  {{ __('Your answer') }}: {{ r.chosenValue || '—' }}
                </p>
              </div>
              <span
                v-if="r.correct"
                class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full border-2 border-teal-500/50 bg-teal-500/20 text-lg font-bold text-teal-400"
              >
                ✓
              </span>
              <span
                v-else
                class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full border-2 border-amber-500/50 bg-amber-500/20 text-lg font-bold text-amber-500"
              >
                ✗
              </span>
            </div>
          </div>
          <div class="flex flex-col gap-3 border-t-2 border-neutral-700/50 px-6 py-5 sm:flex-row">
            <Link
              :href="backUrl"
              class="flex-1 rounded-xl border-2 border-neutral-600 bg-neutral-800 px-4 py-3.5 text-center font-bold text-white transition hover:border-neutral-500 hover:bg-neutral-700"
            >
              {{ __('Back to mini-games') }}
            </Link>
            <Link
              :href="homeUrl"
              class="flex-1 rounded-xl border-2 border-teal-500 bg-gradient-to-r from-teal-600 to-teal-500 px-4 py-3.5 text-center font-bold text-white shadow-lg shadow-teal-500/25 transition hover:from-teal-500 hover:to-teal-400"
            >
              {{ __('Back to home') }}
            </Link>
          </div>
        </div>
      </div>

      <div v-else-if="loading" class="flex flex-col items-center gap-6 py-16">
        <div class="h-16 w-16 animate-spin rounded-full border-4 border-teal-500/30 border-t-teal-500" />
        <p class="text-lg font-medium text-neutral-400">{{ __('Loading...') }}</p>
        <p class="text-sm text-neutral-500">{{ __('Preparing your 5 questions...') }}</p>
      </div>

      <div v-else-if="error" class="space-y-4 rounded-2xl border-2 border-amber-500/30 bg-neutral-900/80 p-8">
        <p class="text-amber-400">{{ error }}</p>
        <button
          type="button"
          class="rounded-xl bg-neutral-600 px-4 py-2 font-medium text-white hover:bg-neutral-500"
          @click="preloadRound"
        >
          {{ __('Retry') }}
        </button>
      </div>

      <template v-else-if="track">
        <div class="overflow-hidden rounded-2xl border-2 border-teal-500/20 bg-neutral-900/80 shadow-2xl shadow-teal-500/5 ring-1 ring-white/5">
          <div class="flex flex-col gap-6 p-6">
            <!-- Album cover: blurred and/or zoomed until answer, then full reveal -->
            <div class="flex justify-center">
              <div
                class="relative aspect-square w-full max-w-sm overflow-hidden rounded-xl border-2 border-neutral-600 bg-neutral-800 shadow-xl ring-2 ring-teal-500/20"
              >
                <img
                  v-if="track.artwork_url"
                  :src="track.artwork_url"
                  alt=""
                  class="h-full w-full object-cover transition-all duration-500 ease-out"
                  :class="{
                    'blur-md': !result && (puzzleMode === 'blur' || puzzleMode === 'both'),
                    'scale-105': !result && puzzleMode === 'blur',
                  }"
                  :style="
                    result
                      ? {}
                      : puzzleMode === 'zoom' || puzzleMode === 'both'
                        ? {
                            transform: `scale(2) translate(${zoomPosition.x}%, ${zoomPosition.y}%)`,
                          }
                        : {}
                  "
                />
                <div
                  v-else
                  class="flex h-full w-full items-center justify-center text-4xl font-bold text-neutral-500"
                >
                  ?
                </div>
              </div>
            </div>

            <div v-if="!result" class="flex flex-col gap-3">
              <p class="text-center text-sm font-bold uppercase tracking-wider text-neutral-400">
                {{ __('Who is the artist?') }}
              </p>
              <ul class="flex flex-col gap-2">
                <li v-for="(choice, index) in choices" :key="index">
                  <button
                    type="button"
                    class="w-full rounded-xl border-2 border-neutral-600 bg-neutral-800/80 px-4 py-3.5 text-left font-semibold text-neutral-100 transition hover:border-teal-500 hover:bg-teal-500/10 hover:shadow-[0_0_20px_rgba(20,184,166,0.15)] focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 focus:ring-offset-neutral-900 disabled:opacity-50"
                    :disabled="checking"
                    @click="submitChoice(choice)"
                  >
                    {{ choice }}
                  </button>
                </li>
              </ul>
            </div>

            <div v-else class="space-y-5">
              <p
                :class="result.correct ? 'text-teal-400' : 'text-amber-500'"
                class="text-center text-lg font-bold"
              >
                {{ result.correct ? __('Correct!') : __(wrongMessageKey) }}
                <template v-if="result.points"> +{{ result.points }} {{ __('points') }}</template>
              </p>
              <p v-if="!result.correct && result.correct_value" class="text-center text-neutral-400">
                {{ __('Correct answer') }}: <span class="font-semibold text-neutral-200">{{ result.correct_value }}</span>
              </p>
              <button
                type="button"
                class="w-full rounded-xl bg-gradient-to-r from-teal-600 to-cyan-600 px-4 py-4 font-black text-white shadow-lg shadow-teal-500/30 transition hover:from-teal-500 hover:to-cyan-500 hover:shadow-teal-500/40 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:ring-offset-2 focus:ring-offset-neutral-900"
                @click="nextQuestion"
              >
                {{ currentQuestionIndex >= QUESTIONS_PER_ROUND - 1 ? __('See results') : __('Next question') }}
              </button>
            </div>
          </div>
        </div>
      </template>
    </div>
  </AppLayout>
</template>
