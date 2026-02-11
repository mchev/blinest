<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import MinigamePlayLayout from '../shared/MinigamePlayLayout.vue'
import { pickWrongMessageKey } from '../shared/wrongMessageKeys.js'

const QUESTIONS_PER_ROUND = 5

const nextUrl = route('minigames.anagram.next')
const checkUrl = route('minigames.anagram.check')
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
const userInput = ref('')

/** Current question: { track_id, scrambled_artist, artwork_url } */
const currentQuestion = ref(null)

const showProgress = computed(() => !!currentQuestion.value)

function applyQuestion(index) {
  const q = roundTracks.value[index]
  if (!q) return
  currentQuestion.value = {
    track_id: q.track_id,
    scrambled_artist: q.scrambled_artist,
    artwork_url: q.artwork_url,
  }
  result.value = null
  wrongMessageKey.value = ''
  userInput.value = ''
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

async function submitAnswer() {
  const value = userInput.value?.trim()
  if (!currentQuestion.value || checking.value || value === '') return
  checking.value = true
  try {
    const { data } = await axios.post(checkUrl, {
      track_id: currentQuestion.value.track_id,
      chosen_value: value,
    })
    result.value = data
    if (!data.correct) wrongMessageKey.value = pickWrongMessageKey()
    if (data.correct) {
      sessionScore.value += data.points || 0
    }
    roundResults.value.push({
      track: { id: currentQuestion.value.track_id, artwork_url: currentQuestion.value.artwork_url },
      correctValue: data.correct_value,
      chosenValue: value,
      correct: data.correct,
      points: data.points || 0,
    })
  } catch (e) {
    wrongMessageKey.value = pickWrongMessageKey()
    result.value = { correct: false, correct_value: null, points: 0 }
    roundResults.value.push({
      track: { id: currentQuestion.value.track_id, artwork_url: currentQuestion.value.artwork_url },
      correctValue: null,
      chosenValue: value,
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
  <MinigamePlayLayout
    :page-title="__('Anagram')"
    :back-url="backUrl"
    :home-url="homeUrl"
    :questions-per-round="QUESTIONS_PER_ROUND"
    :session-score="sessionScore"
    :show-summary="showSummary"
    :current-question-index="currentQuestionIndex"
    :round-results="roundResults"
    :loading="loading"
    :error="error"
    result-item-label="Artist"
    :show-progress="showProgress"
    @retry="preloadRound"
  >
    <div
      v-if="currentQuestion"
      class="overflow-hidden rounded-2xl border-2 border-teal-500/20 bg-neutral-900/80 shadow-2xl ring-1 ring-white/5"
    >
      <div class="flex flex-col gap-6 p-6">
        <div v-if="!result" class="flex flex-col gap-3">
          <p class="text-center text-sm font-bold uppercase tracking-wider text-neutral-400">
            {{ __('Unscramble the letters to find the artist name') }}
          </p>
          <p
            class="font-mono text-center text-2xl font-bold tracking-[0.3em] text-teal-400 uppercase"
            aria-label="Scrambled letters"
          >
            {{ currentQuestion.scrambled_artist }}
          </p>
          <form class="flex flex-col gap-3" @submit.prevent="submitAnswer">
            <input
              v-model="userInput"
              type="text"
              class="w-full rounded-xl border-2 border-neutral-600 bg-neutral-800/80 px-4 py-3.5 font-semibold text-neutral-100 placeholder-neutral-500 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/50"
              :placeholder="__('Type the artist name...')"
              :disabled="checking"
              autocomplete="off"
            />
            <button
              type="submit"
              class="w-full rounded-xl bg-gradient-to-r from-teal-600 to-cyan-600 px-4 py-3.5 font-black text-white shadow-lg transition hover:from-teal-500 hover:to-cyan-500 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:ring-offset-2 focus:ring-offset-neutral-900 disabled:opacity-50"
              :disabled="checking || !userInput?.trim()"
            >
              {{ __('Validate') }}
            </button>
          </form>
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
            class="w-full rounded-xl bg-gradient-to-r from-teal-600 to-cyan-600 px-4 py-4 font-black text-white shadow-lg transition hover:from-teal-500 hover:to-cyan-500 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:ring-offset-2 focus:ring-offset-neutral-900"
            @click="nextQuestion"
          >
            {{ currentQuestionIndex >= QUESTIONS_PER_ROUND - 1 ? __('See results') : __('Next question') }}
          </button>
        </div>
      </div>
    </div>
  </MinigamePlayLayout>
</template>
