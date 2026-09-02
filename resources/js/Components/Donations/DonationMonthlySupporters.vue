<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import Dropdown from '@/Components/Dropdown.vue'
import { useTranslate } from '@/composables/useTranslate'

const props = defineProps({
  supporters: {
    type: Array,
    default: () => [],
  },
  maxVisible: {
    type: Number,
    default: 10,
  },
  label: {
    type: String,
    default: null,
  },
  dropdownTitle: {
    type: String,
    default: null,
  },
  compact: {
    type: Boolean,
    default: false,
  },
})

const translate = useTranslate()

const visibleSupporters = computed(() => props.supporters.slice(0, props.maxVisible))

const overflowCount = computed(() => Math.max(0, props.supporters.length - props.maxVisible))

const sectionLabel = computed(() => props.label ?? translate('Donation monthly supporters'))

const dropdownLabel = computed(() => props.dropdownTitle ?? sectionLabel.value)

const avatarSizeClass = computed(() => (props.compact ? 'h-6 w-6' : 'h-7 w-7'))
</script>

<template>
  <div v-if="supporters.length" class="relative z-10 space-y-2">
    <p class="text-xs leading-snug text-white/55">
      {{ sectionLabel }}
      <span class="text-white/35">· {{ supporters.length }}</span>
    </p>

    <div class="flex flex-nowrap items-center -space-x-1.5 overflow-visible">
      <Link v-for="(supporter, index) in visibleSupporters" :key="supporter.id" :href="route('user.profile', { user: supporter.id })" class="group relative shrink-0 transition hover:z-30 focus-visible:z-30" :style="{ zIndex: 10 + visibleSupporters.length - index }">
        <img :src="supporter.photo" :alt="supporter.name" :class="[avatarSizeClass, 'rounded-full object-cover ring-2 ring-brand-deep transition group-hover:ring-white/45 group-hover:brightness-110']" loading="lazy" />
        <span class="pointer-events-none absolute bottom-full left-1/2 z-30 mb-1.5 -translate-x-1/2 whitespace-nowrap rounded-md bg-brand-midnight/95 px-2 py-1 text-[10px] font-semibold text-white opacity-0 shadow-lg ring-1 ring-white/15 transition group-hover:opacity-100">
          {{ supporter.name }}
        </span>
      </Link>

      <div v-if="overflowCount > 0" class="relative shrink-0 hover:z-30" :style="{ zIndex: 8 }">
        <Dropdown placement="bottom-start" :overlay="false">
          <span class="flex cursor-pointer items-center justify-center rounded-full bg-white/10 font-bold text-white/80 ring-2 ring-brand-deep transition hover:bg-white/15 hover:text-white" :class="compact ? 'h-6 min-w-6 px-1 text-[9px]' : 'h-7 min-w-7 px-1.5 text-[10px]'" :title="translate('Donation monthly supporters show all')"> +{{ overflowCount }} </span>
          <template #dropdown>
            <div class="w-60 p-2">
              <p class="mb-2 px-1 text-xs font-semibold uppercase tracking-wide text-white/50">
                {{ sectionLabel }}
                <span class="text-white/35">({{ supporters.length }})</span>
              </p>
              <ul class="max-h-56 space-y-0.5 overflow-y-auto">
                <li v-for="supporter in supporters" :key="`all-${supporter.id}`">
                  <Link :href="route('user.profile', { user: supporter.id })" class="flex items-center gap-2.5 rounded-md px-2 py-1.5 transition hover:bg-white/5">
                    <img :src="supporter.photo" :alt="supporter.name" class="h-7 w-7 shrink-0 rounded-full object-cover ring-1 ring-white/15" loading="lazy" />
                    <span class="truncate text-sm font-medium text-white">{{ supporter.name }}</span>
                  </Link>
                </li>
              </ul>
            </div>
          </template>
        </Dropdown>
      </div>
    </div>
  </div>
</template>
