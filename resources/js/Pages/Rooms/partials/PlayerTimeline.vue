<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue'

const props = defineProps({
  variant: {
    type: String,
    required: true,
    validator: (value) => ['playing', 'countdown', 'loading'].includes(value),
  },
  progress: {
    type: Number,
    default: 0,
  },
  remainingSeconds: {
    type: Number,
    default: 0,
  },
  countdown: {
    type: Number,
    default: 0,
  },
  inSpeedZone: {
    type: Boolean,
    default: false,
  },
  speedZonePercent: {
    type: Number,
    default: 18,
  },
  levels: {
    type: Array,
    default: null,
  },
  bass: {
    type: Number,
    default: 0,
  },
})

const clampedProgress = computed(() => Math.min(100, Math.max(0, props.progress)))
const progressPx = computed(() => `${clampedProgress.value}%`)
const isDanger = computed(() => props.variant === 'playing' && props.remainingSeconds > 0 && props.remainingSeconds <= 3)
const isInterTrack = computed(() => props.variant === 'countdown')

const bars = [
  0.18, 0.34, 0.52, 0.28, 0.62, 0.82, 0.56, 0.22, 0.44, 0.7, 0.38, 0.58,
  0.9, 0.46, 0.26, 0.66, 0.78, 0.36, 0.2, 0.48, 0.72, 0.42, 0.6, 0.32,
  0.54, 0.86, 0.5, 0.24, 0.4, 0.74, 0.64, 0.3,
]

const renderedLevels = computed(() => {
  if (!Array.isArray(props.levels) || props.levels.length === 0) {
    return bars
  }

  return props.levels.map((v) => Math.min(1, Math.max(0, Number(v) || 0)))
})

const barValue = (v) => Math.max(0.12, Math.min(1, Number(v) || 0))

const displaySeconds = computed(() => (
  isInterTrack.value ? props.countdown : props.remainingSeconds
))

const formattedTime = computed(() => `${Math.max(0, Math.ceil(displaySeconds.value))}`)

const showPlayhead = computed(() => props.variant === 'playing' || props.variant === 'countdown')

const timerBeforePlayhead = computed(() => clampedProgress.value > 85)

const canvasEl = ref(null)
let canvasCtx = null
let resizeObserver = null
let drawRaf = null
let countdownAnimRaf = null
let playingAnimRaf = null
let animPhase = 0

const syncCanvasSize = () => {
  if (!canvasEl.value) {
    return
  }

  const rect = canvasEl.value.getBoundingClientRect()
  const dpr = Math.max(1, Math.min(2, window.devicePixelRatio || 1))
  const width = Math.max(1, Math.floor(rect.width * dpr))
  const height = Math.max(1, Math.floor(rect.height * dpr))

  if (canvasEl.value.width !== width || canvasEl.value.height !== height) {
    canvasEl.value.width = width
    canvasEl.value.height = height
  }

  if (!canvasCtx) {
    canvasCtx = canvasEl.value.getContext('2d', { alpha: true })
  }
}

const scheduleDraw = () => {
  if (drawRaf != null) {
    return
  }

  drawRaf = requestAnimationFrame(() => {
    drawRaf = null
    draw()
  })
}

const drawSmoothPath = (pts, reverse = false) => {
  const p = reverse ? [...pts].reverse() : pts
  if (p.length < 2) {
    return
  }

  canvasCtx.moveTo(p[0].x, p[0].y)
  for (let i = 1; i < p.length; i += 1) {
    const prev = p[i - 1]
    const curr = p[i]
    const cx = Math.floor((prev.x + curr.x) / 2)
    const cy = Math.floor((prev.y + curr.y) / 2)
    canvasCtx.quadraticCurveTo(prev.x, prev.y, cx, cy)
  }
  const last = p[p.length - 1]
  canvasCtx.lineTo(last.x, last.y)
}

const buildRibbonPoints = (levels, w, h, dpr, phase = 0) => {
  const count = levels.length
  const padX = Math.floor(14 * dpr)
  const verticalPad = Math.floor(5 * dpr)
  const midY = Math.floor(h * 0.5)
  const energy = Math.max(0, Math.min(1, Number(props.bass) || 0))
  const ampBudget = Math.max(1, Math.floor((h * 0.5) - verticalPad))
  const maxAmp = Math.min(
    Math.floor(h * 0.36 * (0.9 + (energy * 0.55))),
    ampBudget
  )

  const smoothed = new Array(count).fill(0)
  for (let i = 0; i < count; i += 1) {
    const a = barValue(levels[Math.max(0, i - 1)])
    const b = barValue(levels[i])
    const c = barValue(levels[Math.min(count - 1, i + 1)])
    smoothed[i] = (a + b * 2 + c) / 4
  }

  const pointsTop = []
  const pointsBottom = []
  for (let i = 0; i < count; i += 1) {
    const t = count <= 1 ? 0 : i / (count - 1)
    const x = Math.floor(padX + t * (w - padX * 2))
    const breathe = isInterTrack.value
      ? 0.35 + (Math.sin(phase + t * Math.PI * 2) * 0.12)
      : 1
    const amp = Math.floor(smoothed[i] * maxAmp * breathe)
    pointsTop.push({ x, y: midY - amp })
    pointsBottom.push({ x, y: midY + amp })
  }

  return { pointsTop, pointsBottom }
}

const drawRibbon = (w, h, dpr, grad, pointsTop, pointsBottom, alpha = 0.55) => {
  const energy = Math.max(0, Math.min(1, Number(props.bass) || 0))

  canvasCtx.save()
  canvasCtx.beginPath()
  drawSmoothPath(pointsTop, false)
  drawSmoothPath(pointsBottom, true)
  canvasCtx.closePath()
  canvasCtx.fillStyle = grad
  canvasCtx.globalAlpha = alpha
  canvasCtx.fill()

  canvasCtx.globalAlpha = 1
  canvasCtx.lineWidth = Math.max(2, Math.floor((2.2 + energy * 1.1) * dpr))
  canvasCtx.strokeStyle = grad
  canvasCtx.shadowBlur = Math.floor((14 + energy * 10) * dpr)
  canvasCtx.shadowColor = isInterTrack.value
    ? 'rgba(251, 191, 36, 0.35)'
    : 'rgba(56, 189, 248, 0.32)'
  canvasCtx.stroke()

  canvasCtx.shadowBlur = 0
  canvasCtx.lineWidth = Math.max(1, Math.floor(1.2 * dpr))
  canvasCtx.strokeStyle = 'rgba(255, 255, 255, 0.22)'
  canvasCtx.globalAlpha = 0.9
  canvasCtx.stroke()
  canvasCtx.restore()
}

const draw = () => {
  if (!canvasEl.value) {
    return
  }

  syncCanvasSize()
  if (!canvasCtx) {
    return
  }

  const w = canvasEl.value.width
  const h = canvasEl.value.height
  canvasCtx.clearRect(0, 0, w, h)

  const dpr = Math.max(1, Math.min(2, window.devicePixelRatio || 1))
  const grad = canvasCtx.createLinearGradient(0, 0, w, 0)

  if (isInterTrack.value) {
    grad.addColorStop(0.0, 'rgba(251, 191, 36, 0.85)')
    grad.addColorStop(0.35, 'rgba(245, 158, 11, 0.9)')
    grad.addColorStop(0.65, 'rgba(250, 204, 21, 0.88)')
    grad.addColorStop(1.0, 'rgba(251, 191, 36, 0.85)')
  } else {
    grad.addColorStop(0.0, 'rgba(244, 63, 94, 0.95)')
    grad.addColorStop(0.28, 'rgba(56, 189, 248, 0.95)')
    grad.addColorStop(0.55, 'rgba(34, 197, 94, 0.92)')
    grad.addColorStop(0.72, 'rgba(250, 204, 21, 0.92)')
    grad.addColorStop(1.0, 'rgba(244, 63, 94, 0.90)')
  }

  const { pointsTop, pointsBottom } = buildRibbonPoints(
    renderedLevels.value,
    w,
    h,
    dpr,
    animPhase
  )

  drawRibbon(w, h, dpr, grad, pointsTop, pointsBottom, isInterTrack.value ? 0.42 : 0.55)
}

const startCountdownAnim = () => {
  if (countdownAnimRaf != null) {
    return
  }

  const tick = () => {
    if (!isInterTrack.value) {
      countdownAnimRaf = null
      return
    }

    animPhase += 0.08
    draw()
    countdownAnimRaf = requestAnimationFrame(tick)
  }

  countdownAnimRaf = requestAnimationFrame(tick)
}

const stopCountdownAnim = () => {
  if (countdownAnimRaf != null) {
    cancelAnimationFrame(countdownAnimRaf)
    countdownAnimRaf = null
  }
}

const startPlayingAnim = () => {
  if (playingAnimRaf != null) {
    return
  }

  const tick = () => {
    if (props.variant !== 'playing') {
      playingAnimRaf = null
      return
    }

    draw()
    playingAnimRaf = requestAnimationFrame(tick)
  }

  playingAnimRaf = requestAnimationFrame(tick)
}

const stopPlayingAnim = () => {
  if (playingAnimRaf != null) {
    cancelAnimationFrame(playingAnimRaf)
    playingAnimRaf = null
  }
}

const syncVariantAnim = (variant) => {
  if (variant === 'countdown') {
    stopPlayingAnim()
    startCountdownAnim()
    return
  }

  stopCountdownAnim()

  if (variant === 'playing') {
    startPlayingAnim()
    return
  }

  stopPlayingAnim()
  scheduleDraw()
}

watch(() => props.levels, () => scheduleDraw(), { deep: false })
watch(() => props.progress, () => scheduleDraw())
watch(() => props.variant, (variant) => {
  syncVariantAnim(variant)
})
watch(() => props.remainingSeconds, () => scheduleDraw())
watch(() => props.countdown, () => scheduleDraw())

onMounted(() => {
  syncCanvasSize()
  scheduleDraw()
  syncVariantAnim(props.variant)

  if (canvasEl.value) {
    resizeObserver = new ResizeObserver(() => {
      syncCanvasSize()
      scheduleDraw()
    })
    resizeObserver.observe(canvasEl.value)
  }
})

onBeforeUnmount(() => {
  stopCountdownAnim()
  stopPlayingAnim()

  if (resizeObserver && canvasEl.value) {
    resizeObserver.unobserve(canvasEl.value)
  }
  resizeObserver = null

  if (drawRaf != null) {
    cancelAnimationFrame(drawRaf)
    drawRaf = null
  }
})
</script>

<template>
  <div class="hud">
    <template v-if="variant === 'loading'">
      <div class="rail rail--loading relative flex h-12 items-center justify-center">
        <span class="h-4 w-4 animate-spin rounded-full border-2 border-neutral-600 border-t-emerald-400" />
      </div>
    </template>

    <template v-else>
      <div
        class="rail relative h-12"
        role="progressbar"
        :aria-valuenow="Math.round(clampedProgress)"
        aria-valuemin="0"
        aria-valuemax="100"
      >
        <canvas ref="canvasEl" class="viz absolute inset-0 z-10" />

        <div
          v-if="showPlayhead"
          class="playhead absolute inset-y-0 z-30"
          :class="{
            'playhead--danger': isDanger,
            'playhead--speed': inSpeedZone && !isDanger,
            'playhead--inter': isInterTrack,
          }"
          :style="{ left: progressPx }"
        >
          <div class="playhead-glow -translate-x-1/2" />
          <div class="playhead-beam -translate-x-1/2" />
          <div class="playhead-cap" />
          <div
            class="playhead-timer"
            :class="{
              'playhead-timer--before': timerBeforePlayhead,
              'playhead-timer--danger': isDanger,
              'playhead-timer--inter': isInterTrack,
              'playhead-timer--speed': inSpeedZone && !isDanger && !isInterTrack,
            }"
          >
            <span class="timer-value tabular-nums">{{ formattedTime }}<span class="timer-unit">s</span></span>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>

<style scoped>
.hud {
  background: transparent;
}

.rail {
  background: transparent;
}

.viz {
  width: 100%;
  height: 100%;
  display: block;
  pointer-events: none;
}

.playhead-timer {
  position: absolute;
  top: 50%;
  left: 0.75rem;
  transform: translateY(-50%);
  pointer-events: none;
  white-space: nowrap;
}

.playhead-timer--before {
  left: auto;
  right: 0.75rem;
}

.timer-value {
  font-size: 1.05rem;
  font-weight: 900;
  line-height: 1;
  color: rgb(255 255 255);
  text-shadow:
    0 0 10px rgba(0, 0, 0, 0.85),
    0 1px 3px rgba(0, 0, 0, 0.9);
}

.timer-unit {
  margin-left: 0.1rem;
  font-size: 0.68rem;
  font-weight: 800;
  color: rgba(212, 212, 212, 0.95);
  text-shadow: 0 1px 4px rgba(0, 0, 0, 0.85);
}

.playhead-timer--danger .timer-value {
  color: rgb(254 205 211);
}

.playhead-timer--inter .timer-value {
  color: rgb(252 211 77);
}

.playhead-timer--speed .timer-value {
  color: rgb(252 211 77);
}

.playhead {
  pointer-events: none;
}

.playhead-glow {
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  width: 18px;
  background: radial-gradient(ellipse at center, rgba(255, 255, 255, 0.35) 0%, rgba(56, 189, 248, 0.18) 45%, transparent 70%);
}

.playhead-beam {
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  width: 2px;
  background: linear-gradient(
    180deg,
    transparent 0%,
    rgba(255, 255, 255, 0.95) 18%,
    rgba(255, 255, 255, 0.95) 82%,
    transparent 100%
  );
  box-shadow:
    0 0 8px rgba(255, 255, 255, 0.55),
    0 0 16px rgba(56, 189, 248, 0.35);
}

.playhead-cap {
  position: absolute;
  top: 50%;
  left: 0;
  width: 7px;
  height: 7px;
  margin-top: -3.5px;
  transform: translateX(-50%) rotate(45deg);
  background: rgb(255 255 255);
  border-radius: 1px;
  box-shadow:
    0 0 6px rgba(255, 255, 255, 0.8),
    0 0 12px rgba(56, 189, 248, 0.45);
}

.playhead--speed .playhead-beam,
.playhead--speed .playhead-cap {
  box-shadow:
    0 0 8px rgba(251, 191, 36, 0.55),
    0 0 16px rgba(245, 158, 11, 0.35);
}

.playhead--speed .playhead-cap {
  background: rgb(252 211 77);
}

.playhead--danger .playhead-beam {
  background: linear-gradient(
    180deg,
    transparent 0%,
    rgba(254, 205, 211, 0.95) 18%,
    rgba(254, 205, 211, 0.95) 82%,
    transparent 100%
  );
  box-shadow:
    0 0 8px rgba(251, 113, 133, 0.55),
    0 0 16px rgba(244, 63, 94, 0.35);
}

.playhead--danger .playhead-cap {
  background: rgb(254 205 211);
  box-shadow:
    0 0 6px rgba(251, 113, 133, 0.8),
    0 0 12px rgba(244, 63, 94, 0.45);
}

.playhead--inter .playhead-glow {
  background: radial-gradient(ellipse at center, rgba(255, 255, 255, 0.28) 0%, rgba(251, 191, 36, 0.2) 45%, transparent 70%);
}

.playhead--inter .playhead-beam {
  box-shadow:
    0 0 8px rgba(251, 191, 36, 0.5),
    0 0 16px rgba(245, 158, 11, 0.35);
}

.playhead--inter .playhead-cap {
  background: rgb(252 211 77);
  box-shadow:
    0 0 6px rgba(251, 191, 36, 0.75),
    0 0 12px rgba(245, 158, 11, 0.4);
}
</style>
