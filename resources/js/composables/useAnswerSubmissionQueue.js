import { computed, ref } from 'vue'
import { usePage } from '@inertiajs/vue3'

/**
 * Serial answer submission queue — input never waits on the network.
 * Each Enter captures text immediately; requests are processed FIFO so
 * server-side word accumulation stays ordered (fighting-game input buffer).
 */
export function useAnswerSubmissionQueue({ getRound, getTrack, getCurrentTime, getWords, setWords, onGoodAnswers, onMessage, isDisabled }) {
  const page = usePage()

  const __ = (key, replace = {}) => {
    const translation = page.props.language?.[key] || key
    let result = translation

    Object.keys(replace).forEach((replaceKey) => {
      result = result.replace(`:${replaceKey}`, replace[replaceKey])
    })

    return result
  }

  const queue = ref([])
  const processing = ref(false)
  let generation = 0
  let submissionCounter = 0
  let activeAbortController = null

  const pendingCount = computed(() => queue.value.length + (processing.value ? 1 : 0))

  const reset = () => {
    generation += 1
    queue.value = []
    processing.value = false
    activeAbortController?.abort()
    activeAbortController = null
  }

  const dropStaleHead = () => {
    while (queue.value.length > 0) {
      const head = queue.value[0]
      const track = getTrack()
      const round = getRound()

      if (head.generation !== generation || !track || !round || head.trackId !== track.id || head.roundId !== round.id) {
        queue.value.shift()
        continue
      }

      break
    }
  }

  const isStaleItem = (item) => {
    const track = getTrack()
    const round = getRound()

    return item.generation !== generation || !track || !round || item.trackId !== track.id || item.roundId !== round.id
  }

  const isAbortError = (error) => error?.code === 'ERR_CANCELED' || error?.name === 'CanceledError'

  const handleError = (item, error) => {
    if (isAbortError(error)) {
      return
    }

    const status = error.response?.status
    const isNetworkOrThrottle = !error.response || status === 429
    const isConflict = status === 409
    const isInvalidTime = status === 400

    if (isConflict) {
      return
    }

    if (isNetworkOrThrottle) {
      onMessage({ type: 'bad', body: __('Connection problem, please try again') })
    } else if (isInvalidTime) {
      onMessage({ type: 'bad', body: __('Answer window closed, try again on the next track') })
    } else {
      console.error('Error checking answer:', error)
    }
  }

  const processQueue = async () => {
    if (processing.value) {
      return
    }

    processing.value = true

    try {
      while (queue.value.length > 0) {
        dropStaleHead()

        if (queue.value.length === 0) {
          break
        }

        const item = queue.value[0]

        if (isStaleItem(item)) {
          queue.value.shift()
          continue
        }

        activeAbortController?.abort()
        activeAbortController = new AbortController()

        try {
          const response = await axios.post(
            `/rounds/${item.roundId}/tracks/${item.trackId}/check`,
            {
              text: item.text,
              words: getWords(),
              currentTime: item.currentTime,
            },
            { signal: activeAbortController.signal },
          )

          if (isStaleItem(item)) {
            queue.value.shift()
            continue
          }

          queue.value.shift()

          if (response.data.good_answers?.length) {
            onGoodAnswers(response.data.good_answers)
          }

          setWords(response.data.words || [])

          if (response.data.message) {
            onMessage(response.data.message)
          }
        } catch (error) {
          if (isAbortError(error)) {
            queue.value.shift()
            continue
          }

          if (isStaleItem(item)) {
            queue.value.shift()
            continue
          }

          queue.value.shift()
          handleError(item, error)
        } finally {
          if (activeAbortController?.signal.aborted) {
            activeAbortController = null
          }
        }
      }
    } finally {
      processing.value = false
      activeAbortController = null

      if (queue.value.length > 0) {
        processQueue()
      }
    }
  }

  const submit = (rawText) => {
    const trimmed = rawText.trim()

    if (trimmed.length < 1 || isDisabled()) {
      return false
    }

    const round = getRound()
    const track = getTrack()

    if (!round || !track) {
      return false
    }

    const duplicatePending = queue.value.some((item) => item.text === trimmed && item.generation === generation && item.trackId === track.id)

    if (duplicatePending) {
      return false
    }

    queue.value.push({
      id: ++submissionCounter,
      text: trimmed,
      roundId: round.id,
      trackId: track.id,
      generation,
      currentTime: getCurrentTime(),
    })

    processQueue()

    return true
  }

  return {
    submit,
    reset,
    pendingCount,
  }
}
