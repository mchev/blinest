import { ref, computed, watch, onMounted, onUnmounted, inject } from 'vue'

const PUBLIC_STATE_POLL_MS = 30_000

/**
 * Live state for homepage room cards via public Echo channel.
 * Subscribes only while the card is visible (IntersectionObserver).
 */
export function useRoomPublicChannel(room, rootRef) {
  const reportTabPlayerDelta = inject('reportTabPlayerDelta', null)

  const memberCount = ref(room.subscriptions ?? 0)
  const isPlaying = ref(Boolean(room.is_playing))
  const currentTrackIndex = ref(room.current_track_index ?? 0)
  const memberCountBump = ref(false)

  const tracksByRound = computed(() => Math.max(1, room.tracks_by_round ?? 10))

  const progressPercent = computed(() => Math.min(100, Math.round((currentTrackIndex.value / tracksByRound.value) * 100)))

  const tracksRemaining = computed(() => Math.max(0, tracksByRound.value - currentTrackIndex.value))

  const isNearEnd = computed(() => isPlaying.value && tracksRemaining.value > 0 && tracksRemaining.value <= 2)

  /** @type {import('vue').ComputedRef<'hot'|'live'|'live-empty'|'lobby-manual'|'lobby'>} */
  const cardState = computed(() => {
    if (isPlaying.value && memberCount.value >= 5) {
      return 'hot'
    }
    if (isPlaying.value && memberCount.value >= 1) {
      return 'live'
    }
    if (isPlaying.value) {
      return 'live-empty'
    }
    if (!room.is_autostart) {
      return 'lobby-manual'
    }

    return 'lobby'
  })

  const visibleTrackDots = computed(() => Math.min(tracksByRound.value, 12))

  let echoChannel = null
  let observer = null
  let bumpTimeout = null
  let pollInterval = null
  let lastReportedMemberCount = room.subscriptions ?? 0

  function reportMemberCountDelta(nextCount) {
    if (typeof reportTabPlayerDelta !== 'function') {
      return
    }

    const delta = nextCount - lastReportedMemberCount

    if (delta === 0) {
      return
    }

    lastReportedMemberCount = nextCount
    reportTabPlayerDelta({
      roomId: room.id,
      delta,
      tab: room.is_public ? 'official' : 'community',
    })
  }

  function applyPublicState(payload) {
    if (payload.memberCount !== undefined && payload.memberCount > memberCount.value) {
      memberCountBump.value = true
      if (bumpTimeout) {
        clearTimeout(bumpTimeout)
      }
      bumpTimeout = setTimeout(() => {
        memberCountBump.value = false
      }, 700)
    }

    if (payload.memberCount !== undefined) {
      memberCount.value = payload.memberCount
      reportMemberCountDelta(payload.memberCount)
    }
    if (payload.isPlaying !== undefined) {
      isPlaying.value = payload.isPlaying
    }
    if (payload.currentTrackIndex !== undefined) {
      currentTrackIndex.value = payload.currentTrackIndex
    }
  }

  function listenForPublicState(channel) {
    channel.listen('.RoomPublicState', applyPublicState)
    channel.listen('RoomPublicState', applyPublicState)
  }

  function startPolling() {
    if (pollInterval) {
      return
    }

    pollInterval = setInterval(fetchInitialPublicState, PUBLIC_STATE_POLL_MS)
  }

  function stopPolling() {
    if (!pollInterval) {
      return
    }

    clearInterval(pollInterval)
    pollInterval = null
  }

  function subscribeEcho() {
    fetchInitialPublicState()
    startPolling()

    if (echoChannel || !window.Echo) {
      return
    }

    echoChannel = window.Echo.channel(`room.public.${room.id}`)
    listenForPublicState(echoChannel)
  }

  async function fetchInitialPublicState() {
    if (!room.slug) {
      return
    }

    try {
      const response = await fetch(route('rooms.public-state', { room: room.slug }), {
        headers: {
          Accept: 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        },
      })

      if (response.ok) {
        applyPublicState(await response.json())
      }
    } catch {
      // Ignore network errors; Echo or the next poll will catch up.
    }
  }

  function leaveEcho() {
    stopPolling()

    if (!echoChannel || !window.Echo) {
      echoChannel = null

      return
    }

    window.Echo.leave(`room.public.${room.id}`)
    echoChannel = null
  }

  watch(
    () => room.subscriptions,
    (value) => {
      if (value !== undefined && value !== null) {
        memberCount.value = value
        lastReportedMemberCount = value
      }
    },
  )

  onMounted(() => {
    if (!rootRef.value) {
      return
    }

    observer = new IntersectionObserver(
      (entries) => {
        if (entries[0]?.isIntersecting) {
          subscribeEcho()
        } else {
          leaveEcho()
        }
      },
      { rootMargin: '120px', threshold: 0 },
    )
    observer.observe(rootRef.value)
  })

  onUnmounted(() => {
    if (observer && rootRef.value) {
      observer.disconnect()
    }
    leaveEcho()
    if (bumpTimeout) {
      clearTimeout(bumpTimeout)
    }
  })

  return {
    memberCount,
    isPlaying,
    currentTrackIndex,
    tracksByRound,
    progressPercent,
    tracksRemaining,
    isNearEnd,
    cardState,
    memberCountBump,
    visibleTrackDots,
  }
}
