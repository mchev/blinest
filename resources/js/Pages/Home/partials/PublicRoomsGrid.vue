<script setup>
import { computed, ref } from 'vue'
import Room from './Room.vue'
import HomeSectionHeader from './HomeSectionHeader.vue'

const props = defineProps({
  rooms: {
    type: Array,
    default: () => [],
  },
  categories: {
    type: Array,
    default: () => [],
  },
  hiddenCategoryIds: {
    type: Array,
    default: () => [],
  },
})

const selectedCategoryId = ref('')
const showAll = ref(false)
const initialLimit = 16

const sortedRooms = computed(() =>
  [...(props.rooms || [])].sort(
    (a, b) =>
      (b.subscriptions ?? 0) - (a.subscriptions ?? 0)
      || (b.rounds_count ?? 0) - (a.rounds_count ?? 0),
  ),
)

const isHiddenByDefault = (room) =>
  props.hiddenCategoryIds.includes(room.category?.id)

const filteredRooms = computed(() => {
  if (!selectedCategoryId.value) {
    return sortedRooms.value.filter((room) => !isHiddenByDefault(room))
  }

  const categoryId = Number(selectedCategoryId.value)

  return sortedRooms.value.filter((room) => room.category?.id === categoryId)
})

const defaultVisibleCount = computed(() =>
  sortedRooms.value.filter((room) => !isHiddenByDefault(room)).length,
)

const visibleRooms = computed(() => {
  if (showAll.value) {
    return filteredRooms.value
  }

  return filteredRooms.value.slice(0, initialLimit)
})

const hiddenRoomCount = computed(() => {
  if (showAll.value) {
    return 0
  }

  return Math.max(0, filteredRooms.value.length - initialLimit)
})

const selectedCategory = computed(() =>
  props.categories.find((category) => String(category.id) === selectedCategoryId.value) ?? null,
)

const liveRoomCount = computed(() =>
  filteredRooms.value.filter((room) => room.is_playing).length,
)
</script>

<template>
  <section id="public-rooms" class="space-y-5">
    <HomeSectionHeader kicker="Blinest" :title="__('Official rooms')">
      <template #subtitle>
        <span class="text-zinc-400">
          <template v-if="selectedCategory">
            {{ __('Official rooms SEO intro category', { category: __(selectedCategory.name) }) }}
          </template>
          <template v-else>
            {{ __('Official rooms SEO intro') }}
          </template>
          <span v-if="liveRoomCount > 0" class="font-medium text-red-400/90">
            · {{ liveRoomCount }} {{ __('live now') }}
          </span>
        </span>
      </template>
      <template #action>
        <select
          id="public-room-category-filter"
          v-model="selectedCategoryId"
          class="form-select w-full py-2 text-sm sm:w-52"
          :aria-label="__('Filter by category')"
          @change="showAll = false"
        >
          <option value="">
            {{ __('All categories') }} ({{ defaultVisibleCount }})
          </option>
          <option
            v-for="category in categories"
            :key="category.id"
            :value="String(category.id)"
          >
            {{ __(category.name) }} ({{ category.rooms_count }})
          </option>
        </select>
      </template>
    </HomeSectionHeader>

    <div
      v-if="visibleRooms.length"
      class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-4"
    >
      <Room
        v-for="room in visibleRooms"
        :key="room.id"
        :room="room"
        variant="catalog"
      />
    </div>

    <div v-if="hiddenRoomCount > 0" class="flex justify-center">
      <button
        type="button"
        class="game-btn-secondary"
        @click="showAll = true"
      >
        {{ __('Show more rooms') }}
        <span class="text-zinc-400">(+{{ hiddenRoomCount }})</span>
      </button>
    </div>

    <div
      v-else-if="!visibleRooms.length"
      class="rounded-xl border border-dashed border-zinc-700 bg-arena-panel/40 px-6 py-10 text-center"
    >
      <p class="text-sm font-semibold text-zinc-200">
        {{ __('No rooms in this category right now.') }}
      </p>
      <button
        type="button"
        class="game-link-action mt-3"
        @click="selectedCategoryId = ''"
      >
        {{ __('Show all categories') }}
      </button>
    </div>
  </section>
</template>
