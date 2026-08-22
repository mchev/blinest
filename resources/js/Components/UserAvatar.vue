<script setup>
import { computed } from 'vue'
import { useTranslate } from '@/composables/useTranslate'
import { userHasDonorCrown } from '@/utils/donorPerks'

const props = defineProps({
  user: {
    type: Object,
    default: null,
  },
  alt: {
    type: String,
    default: '',
  },
  imgClass: {
    type: String,
    default: 'h-10 w-10 rounded-full object-cover ring-2 ring-white/20',
  },
  crownSize: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value),
  },
})

const translate = useTranslate()

const showCrown = computed(() => userHasDonorCrown(props.user))

const crownClass = computed(() => {
  if (props.crownSize === 'sm') {
    return 'user-avatar__crown user-avatar__crown--sm'
  }

  if (props.crownSize === 'lg') {
    return 'user-avatar__crown user-avatar__crown--lg'
  }

  return 'user-avatar__crown'
})

const imageAlt = computed(() => props.alt || props.user?.name || '')
</script>

<template>
  <span class="user-avatar inline-flex">
    <img v-if="user?.photo" :src="user.photo" :alt="imageAlt" :class="imgClass" loading="lazy" />
    <span v-if="showCrown" :class="crownClass" :title="translate('Donor crown title')" :aria-label="translate('Donor crown title')">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M11.562 3.266a.5.5 0 0 1 .876 0L15.39 8.87a1 1 0 0 0 1.516.294L21.183 5.5a.5.5 0 0 1 .798.519l-2.834 10.246a1 1 0 0 1-.956.734H5.81a1 1 0 0 1-.957-.734L2.02 6.02a.5.5 0 0 1 .798-.519l4.276 3.664a1 1 0 0 0 1.516-.294z" />
      </svg>
    </span>
  </span>
</template>
