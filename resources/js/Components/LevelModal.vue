<script setup>
import { computed, ref, watch } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import Modal from '@/Components/Modal.vue'
import LevelBadge from '@/Components/LevelBadge.vue'
import LevelInfo from '@/Components/LevelInfo.vue'

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  level: {
    type: Number,
    default: 1,
  },
  currentXp: {
    type: Number,
    default: 0,
  },
  xpForNextLevel: {
    type: Number,
    default: 100,
  },
  totalXp: {
    type: Number,
    default: 0,
  },
  levelMetrics: {
    type: Object,
    default: null,
  },
})

const emit = defineEmits(['close'])

const page = usePage()
const user = page.props.auth?.user

// Translation function
const __ = (key, replace = {}) => {
  const translation = page.props.language?.[key] || key
  let result = translation
  Object.keys(replace).forEach((k) => {
    result = result.replace(`:${k}`, replace[k])
  })
  return result
}

// Couleur selon le niveau (pour le header)
const levelColor = computed(() => {
  if (props.level >= 50) return 'text-purple-500'
  if (props.level >= 30) return 'text-yellow-500'
  if (props.level >= 20) return 'text-blue-500'
  if (props.level >= 10) return 'text-green-500'
  return 'text-neutral-400'
})
</script>

<template>
  <Modal :show="show" @close="$emit('close')" max-width="2xl">
    <div class="bg-gradient-to-br from-slate-800 to-slate-900">
      <!-- Header -->
      <div class="border-b border-neutral-700 px-6 py-6">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-4">
            <LevelBadge :level="level" size="lg" variant="default" />
            <div>
              <h2 :class="['text-3xl font-bold', levelColor]">
                {{ __('Level') }} {{ level }}
              </h2>
              <p class="text-sm text-neutral-400 mt-1">
                {{ __('Total XP') }}: <span :class="['font-bold', levelColor]">{{ totalXp.toLocaleString('fr-FR') }} {{ __('XP') }}</span>
              </p>
            </div>
          </div>
          <button
            @click="$emit('close')"
            class="text-neutral-400 hover:text-neutral-200 transition-colors"
          >
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Content -->
      <div class="px-6 py-6 space-y-6">
        <LevelInfo
          :level="level"
          :current-xp="currentXp"
          :xp-for-next-level="xpForNextLevel"
          :total-xp="totalXp"
          :level-metrics="levelMetrics"
          :compact="false"
        />

        <!-- Info Link -->
        <div class="text-center pt-2">
          <Link
            :href="route('docs.level')"
            class="text-sm text-blue-400 hover:text-blue-300 transition-colors inline-flex items-center gap-1"
            @click="$emit('close')"
          >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ __('How does it work?') }}
          </Link>
        </div>
      </div>
    </div>
  </Modal>
</template>

