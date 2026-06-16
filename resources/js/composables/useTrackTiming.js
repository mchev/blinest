import { ref } from 'vue'
import axios from 'axios'

/** Estimated offset: serverNow ≈ Date.now() + serverTimeOffsetMs */
const serverTimeOffsetMs = ref(0)
let lastOffsetMeasuredAtMs = 0
let inFlightMeasure = null

export function getServerNowMs() {
  return Date.now() + serverTimeOffsetMs.value
}

export function parseIsoTimestamp(iso) {
  if (!iso) {
    return null
  }

  const ms = new Date(iso).getTime()

  return Number.isFinite(ms) ? ms : null
}

/**
 * Measure server clock offset using `/rooms/:id/time`.
 * By default we do a single request. Use `samples > 1` only for rare "forced resync" moments.
 */
export async function measureServerTimeOffset(roomId, options = {}) {
  const {
    samples = 1,
    maxAgeMs = 3000,
  } = options || {}

  const now = Date.now()
  if (maxAgeMs > 0 && now - lastOffsetMeasuredAtMs < maxAgeMs) {
    return serverTimeOffsetMs.value
  }

  if (inFlightMeasure) {
    return inFlightMeasure
  }

  inFlightMeasure = (async () => {
    let bestOffset = null
    let bestRtt = Number.POSITIVE_INFINITY
    const sampleCount = Math.max(1, Math.min(5, parseInt(samples, 10) || 1))

    for (let i = 0; i < sampleCount; i += 1) {
      const t0 = Date.now()
      // eslint-disable-next-line no-await-in-loop
      const { data } = await axios.get(`/rooms/${roomId}/time`)
      const t1 = Date.now()
      const rtt = t1 - t0
      const serverTime = parseIsoTimestamp(data.server_time)

      if (serverTime === null) {
        continue
      }

      const offset = serverTime - (t0 + rtt / 2)
      if (rtt < bestRtt) {
        bestRtt = rtt
        bestOffset = offset
      }
    }

    if (bestOffset !== null) {
      serverTimeOffsetMs.value = bestOffset
      lastOffsetMeasuredAtMs = Date.now()
    }

    return serverTimeOffsetMs.value
  })().finally(() => {
    inFlightMeasure = null
  })

  return inFlightMeasure
}

export function getElapsedSeconds(startedAtIso, nowMs = getServerNowMs()) {
  const startedAt = parseIsoTimestamp(startedAtIso)

  if (startedAt === null) {
    return 0
  }

  return Math.max(0, (nowMs - startedAt) / 1000)
}

export function getProgressPercent(startedAtIso, trackDuration, nowMs = getServerNowMs()) {
  if (!trackDuration || trackDuration <= 0) {
    return 0
  }

  const elapsed = getElapsedSeconds(startedAtIso, nowMs)

  return Math.min(100, Math.round((100 / trackDuration) * elapsed))
}

export function isAnswerWindowOpen(deadlineAtIso, graceMs = 300, nowMs = getServerNowMs()) {
  const deadline = parseIsoTimestamp(deadlineAtIso)

  if (deadline === null) {
    return true
  }

  return nowMs <= deadline + graceMs
}

export function msUntilDeadline(deadlineAtIso, graceMs = 0, nowMs = getServerNowMs()) {
  const deadline = parseIsoTimestamp(deadlineAtIso)

  if (deadline === null) {
    return 0
  }

  return Math.max(0, deadline + graceMs - nowMs)
}

export function getInterTrackCountdown(nextTrackAtIso, pauseSeconds, nowMs = getServerNowMs()) {
  const remainingMs = msUntilDeadline(nextTrackAtIso, 0, nowMs)
  const pauseMs = Math.max(pauseSeconds, 0) * 1000
  const remainingSeconds = remainingMs <= 0
    ? 0
    : Math.max(1, Math.ceil(remainingMs / 1000))
  const elapsedMs = Math.max(0, pauseMs - remainingMs)
  const progressPercent = pauseMs > 0
    ? Math.min(100, (elapsedMs / pauseMs) * 100)
    : 100

  return {
    remainingMs,
    remainingSeconds,
    progressPercent,
    isComplete: remainingMs <= 0,
  }
}

export function useTrackTiming() {
  return {
    serverTimeOffsetMs,
    measureServerTimeOffset,
    getServerNowMs,
    parseIsoTimestamp,
    getElapsedSeconds,
    getProgressPercent,
    isAnswerWindowOpen,
    msUntilDeadline,
    getInterTrackCountdown,
  }
}
