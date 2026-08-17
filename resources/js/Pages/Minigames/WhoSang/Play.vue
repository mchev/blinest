<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import MinigamePlayLayout from '../shared/MinigamePlayLayout.vue'
import MinigamePlayer from '../shared/MinigamePlayer.vue'
import { pickWrongMessageKey } from '../shared/wrongMessageKeys.js'

const QUESTIONS_PER_ROUND = 5

const nextUrl = route('minigames.who_sang.next')
const checkUrl = route('minigames.who_sang.check')
const backUrl = route('minigames.index')
const homeUrl = route('home')

const loading = ref(true)
const error = ref(null)
const roundTracks = ref([])
const currentQuestionIndex = ref(0)
const result = ref(null)
const sessionScore = ref(0)
const checking = ref(false)
const playerRef = ref(null)
const roundResults = ref([])
const showSummary = ref(false)
const timeUp = ref(false)
const wrongMessageKey = ref('')

const track = ref(null)
const choices = ref([])
const correctValue = ref(null)

const showProgress = computed(() => !!track.value)

function applyQuestion(index) {
  const q = roundTracks.value[index]
  if (!q) return
  track.value = q.track
  choices.value = q.choices || []
  correctValue.value = q.correct_value ?? null
  result.value = null
  timeUp.value = false
  wrongMessageKey.value = ''
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
    const responses = await Promise.all(Array.from({ length: QUESTIONS_PER_ROUND }, () => axios.post(nextUrl)))
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

function onTrackEnded() {
  if (result.value !== null) return
  timeUp.value = true
  wrongMessageKey.value = pickWrongMessageKey()
  result.value = {
    correct: false,
    correct_value: correctValue.value,
    points: 0,
  }
  roundResults.value.push({
    track: { id: track.value?.id, artwork_url: track.value?.artwork_url },
    correctValue: correctValue.value,
    chosenValue: null,
    correct: false,
    points: 0,
  })
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
  playerRef.value?.stop()
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
  <MinigamePlayLayout :back-url="backUrl" :home-url="homeUrl" :questions-per-round="QUESTIONS_PER_ROUND" :session-score="sessionScore" :show-summary="showSummary" :current-question-index="currentQuestionIndex" :round-results="roundResults" :loading="loading" :error="error" :show-progress="showProgress" @retry="preloadRound">
    <div v-if="track" class="overflow-hidden rounded-2xl border-2 border-teal-500/20 bg-neutral-900/80 shadow-2xl shadow-teal-500/5 ring-1 ring-white/5">
      <div class="flex flex-col gap-6 p-6">
        <MinigamePlayer ref="playerRef" :preview-url="track.preview_url" :artwork-url="track.artwork_url" @ended="onTrackEnded" />

        <div v-if="!result" class="flex flex-col gap-3">
          <p class="text-center text-sm font-bold uppercase tracking-wider text-neutral-400">
            {{ __('Who sang this track?') }}
          </p>
          <ul class="flex flex-col gap-2">
            <li v-for="(choice, index) in choices" :key="index">
              <button type="button" class="w-full rounded-xl border-2 border-neutral-600 bg-neutral-800/80 px-4 py-3.5 text-left font-semibold text-neutral-100 transition hover:border-teal-500 hover:bg-teal-500/10 hover:shadow-[0_0_20px_rgba(20,184,166,0.15)] focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 focus:ring-offset-neutral-900 disabled:opacity-50" :disabled="checking" @click="submitChoice(choice)">
                {{ choice }}
              </button>
            </li>
          </ul>
        </div>

        <div v-else class="space-y-5">
          <div v-if="timeUp" class="rounded-xl border-2 border-amber-500/40 bg-amber-500/10 px-4 py-3 text-center font-bold uppercase tracking-wider text-amber-400">
            {{ __("Time's up!") }}
          </div>
          <p :class="result.correct ? 'text-teal-400' : 'text-amber-500'" class="text-center text-lg font-bold">
            {{ result.correct ? __('Correct!') : __(wrongMessageKey) }}
            <template v-if="result.points"> +{{ result.points }} {{ __('points') }}</template>
          </p>
          <p v-if="!result.correct && result.correct_value" class="text-center text-neutral-400">
            {{ __('Correct answer') }}: <span class="font-semibold text-neutral-200">{{ result.correct_value }}</span>
          </p>
          <button type="button" class="w-full rounded-xl bg-gradient-to-r from-teal-600 to-cyan-600 px-4 py-4 font-black text-white shadow-lg shadow-teal-500/30 transition hover:from-teal-500 hover:to-cyan-500 hover:shadow-teal-500/40 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:ring-offset-2 focus:ring-offset-neutral-900" @click="nextQuestion">
            {{ currentQuestionIndex >= QUESTIONS_PER_ROUND - 1 ? __('See results') : __('Next question') }}
          </button>
        </div>
      </div>
    </div>
  </MinigamePlayLayout>
</template>
