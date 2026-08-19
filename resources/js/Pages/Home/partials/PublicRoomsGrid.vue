<script setup>
import { computed, provide, ref, watch } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { useCatalogLoadMore } from '@/composables/useCatalogLoadMore'
import Room from './Room.vue'
import MinigameCard from './MinigameCard.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  catalog: {
    type: String,
    default: 'official',
  },
  catalogItems: {
    type: Object,
    required: true,
  },
  catalogCategoryId: {
    type: [Number, String, null],
    default: null,
  },
  categories: {
    type: Array,
    default: () => [],
  },
  communityCategories: {
    type: Array,
    default: () => [],
  },
  catalogTabPlayerCounts: {
    type: Object,
    default: () => ({ official: 0, community: 0 }),
  },
  hiddenCategoryIds: {
    type: Array,
    default: () => [],
  },
})

const page = usePage()
const pendingTab = ref(null)
const pendingFilter = ref(false)
const selectedCategoryId = ref(props.catalogCategoryId ? String(props.catalogCategoryId) : '')

const liveTabPlayerCounts = ref({
  official: props.catalogTabPlayerCounts?.official ?? 0,
  community: props.catalogTabPlayerCounts?.community ?? 0,
})

watch(
  () => props.catalogTabPlayerCounts,
  (counts) => {
    if (!counts) {
      return
    }

    liveTabPlayerCounts.value = {
      official: counts.official ?? 0,
      community: counts.community ?? 0,
    }
  },
  { deep: true },
)

provide('reportTabPlayerDelta', ({ tab, delta }) => {
  if (!tab || delta === 0) {
    return
  }

  liveTabPlayerCounts.value[tab] = Math.max(0, (liveTabPlayerCounts.value[tab] ?? 0) + delta)
})

const user = computed(() => page.props.auth?.user ?? null)

const displayCatalog = computed(() => pendingTab.value ?? props.catalog)

const showTabSkeleton = computed(() => pendingTab.value !== null)

const showFilterOverlay = computed(() => pendingFilter.value && pendingTab.value === null)

const t = (key, replace = {}) => {
  let translation = page.props.language?.[key] ?? key

  Object.entries(replace).forEach(([placeholder, value]) => {
    translation = translation.replace(`:${placeholder}`, String(value))
  })

  return translation
}

const tabs = computed(() => {
  const items = [
    {
      id: 'official',
      label: t('Official rooms'),
      playerCount: liveTabPlayerCounts.value?.official ?? null,
    },
    {
      id: 'community',
      label: t('Private rooms'),
      playerCount: liveTabPlayerCounts.value?.community ?? null,
    },
  ]

  if (user.value) {
    items.push({ id: 'mine', label: t('My rooms') })
  }

  items.push({ id: 'minigames', label: t('Mini-games') })

  return items
})

const switchTabsClass = computed(() => `home-rooms-switch--tabs-${tabs.value.length}`)

const showCategoryFilter = computed(() => displayCatalog.value === 'official' || displayCatalog.value === 'community')

const isMinigamesCatalog = computed(() => displayCatalog.value === 'minigames')

const catalogHeading = computed(() => {
  const tab = tabs.value.find((item) => item.id === displayCatalog.value)

  return tab?.label ?? t('Home catalog')
})

const catalogSeoIntro = computed(() => {
  if (isMinigamesCatalog.value) {
    return t('Mini-games SEO intro')
  }

  if (displayCatalog.value === 'community') {
    return t('Community rooms SEO intro')
  }

  if (displayCatalog.value === 'mine') {
    return t('My rooms SEO intro')
  }

  if (selectedCategoryId.value) {
    const category = activeCategories.value.find((item) => String(item.id) === selectedCategoryId.value)

    if (category) {
      return t('Official rooms SEO intro category', { category: t(category.name) })
    }
  }

  return t('Official rooms SEO intro')
})

const activeCategories = computed(() => (displayCatalog.value === 'official' ? props.categories : props.communityCategories))

const defaultOfficialCount = computed(() => {
  const hidden = new Set(props.hiddenCategoryIds)

  return props.categories.reduce((sum, category) => {
    if (hidden.has(category.id)) {
      return sum
    }

    return sum + (category.rooms_count ?? 0)
  }, 0)
})

const communityCategoryTotal = computed(() => props.communityCategories.reduce((sum, category) => sum + (category.rooms_count ?? 0), 0))

const categoryFilterCount = computed(() => {
  if (!selectedCategoryId.value) {
    return displayCatalog.value === 'official' ? defaultOfficialCount.value : communityCategoryTotal.value
  }

  const category = activeCategories.value.find((item) => String(item.id) === selectedCategoryId.value)

  return category?.rooms_count ?? 0
})

const selectedCategory = computed(() => {
  if (!selectedCategoryId.value || displayCatalog.value !== 'official') {
    return null
  }

  return activeCategories.value.find((item) => String(item.id) === selectedCategoryId.value) ?? null
})

const catalogItemsList = computed(() => {
  if (pendingTab.value !== null) {
    return []
  }

  return props.catalogItems?.data ?? []
})

const catalogQuery = () => ({
  tab: props.catalog,
  category_id: props.catalogCategoryId || undefined,
})

const { loading: loadingMore, hasMore, showLoadMoreButton, loadMore, loadMoreTrigger, syncAutoLoad } = useCatalogLoadMore(() => props.catalogItems, catalogQuery)

const partialReloadOptions = {
  only: ['catalog', 'catalog_items', 'catalog_category_id'],
  reset: ['catalog_items'],
  preserveState: true,
  preserveScroll: true,
  showProgress: false,
  replace: true,
}

const finishPartialReload = () => {
  pendingTab.value = null
  pendingFilter.value = false
}

const reloadCatalog = (query) => {
  router.get(
    route('home'),
    {
      catalog: 1,
      ...query,
    },
    {
      ...partialReloadOptions,
      onSuccess: () => {
        finishPartialReload()
        syncAutoLoad()
      },
      onError: finishPartialReload,
    },
  )
}

const switchTab = (tab) => {
  if (displayCatalog.value === tab) {
    return
  }

  pendingTab.value = tab
  selectedCategoryId.value = ''

  reloadCatalog({ tab })
}

watch(selectedCategoryId, (value, previous) => {
  if (previous === undefined) {
    return
  }

  if (!showCategoryFilter.value) {
    return
  }

  pendingFilter.value = true

  reloadCatalog({
    tab: props.catalog,
    category_id: value || undefined,
  })
})

watch(
  () => props.catalogCategoryId,
  (value) => {
    selectedCategoryId.value = value ? String(value) : ''
  },
)

watch(
  () => props.catalog,
  (value) => {
    if (pendingTab.value === value) {
      pendingTab.value = null
    }
  },
)

const panelId = (tab) => `home-catalog-panel-${tab}`
const tabId = (tab) => `home-catalog-tab-${tab}`
</script>

<template>
  <section id="home-catalog" class="space-y-4 lg:space-y-6" aria-labelledby="home-catalog-heading">
    <header class="home-catalog-header">
      <div class="sr-only">
        <h2 id="home-catalog-heading">
          {{ catalogHeading }}
        </h2>
        <p>
          {{ catalogSeoIntro }}
        </p>
      </div>

      <div class="home-rooms-switch" :class="switchTabsClass" role="tablist" :aria-label="t('Home catalog')">
        <button v-for="tab in tabs" :id="tabId(tab.id)" :key="tab.id" type="button" role="tab" class="home-rooms-switch__tab" :class="{ 'home-rooms-switch__tab--active': displayCatalog === tab.id }" :aria-selected="displayCatalog === tab.id" :aria-controls="panelId(tab.id)" :tabindex="displayCatalog === tab.id ? 0 : -1" @click="switchTab(tab.id)">
          <span class="home-rooms-switch__tab-inner">
            <span>{{ tab.label }}</span>
            <span v-if="tab.playerCount != null" class="home-rooms-switch__tab-count" :aria-label="t(':count players online', { count: tab.playerCount })">
              <Icon name="users" class="h-3.5 w-3.5 shrink-0" aria-hidden="true" />
              <span>{{ tab.playerCount }}</span>
            </span>
          </span>
        </button>
      </div>

      <select v-if="showCategoryFilter" id="home-room-category-filter" v-model="selectedCategoryId" class="retro-select home-rooms-toolbar__filter" :aria-label="t('Filter by category')" :disabled="showTabSkeleton || showFilterOverlay">
        <option value="">{{ t('All categories') }} ({{ categoryFilterCount }})</option>
        <option v-for="category in activeCategories" :key="category.id" :value="String(category.id)">{{ t(category.name) }} ({{ category.rooms_count }})</option>
      </select>

      <Link v-if="selectedCategory?.slug && displayCatalog === 'official'" :href="route('categories.show', selectedCategory.slug)" class="game-link-action text-sm">
        {{ t('View category page') }}
        <Icon name="cheveron-right" class="inline-block h-4 w-4" />
      </Link>
    </header>

    <div :id="panelId(displayCatalog)" role="tabpanel" :aria-labelledby="tabId(displayCatalog)" class="home-catalog-panel space-y-6">
      <div v-if="showTabSkeleton" class="grid grid-cols-2 gap-3 sm:gap-4 md:grid-cols-3 xl:grid-cols-4" role="status" aria-live="polite" aria-busy="true">
        <div v-for="index in 8" :key="`catalog-skeleton-${index}`" class="home-catalog-skeleton" />
        <span class="sr-only">{{ t('Loading rooms...') }}</span>
      </div>

      <template v-else>
        <div v-if="showFilterOverlay" class="home-catalog-panel__overlay" role="status" aria-live="polite">
          <p class="text-sm font-semibold text-white">{{ t('Loading rooms...') }}</p>
        </div>

        <div v-if="isMinigamesCatalog && catalogItemsList.length" class="mb-4 flex justify-end">
          <Link :href="route('minigames.index')" class="game-link-action">
            {{ t('View all') }}
            <Icon name="cheveron-right" class="inline-block h-4 w-4" />
          </Link>
        </div>

        <div v-if="!catalogItemsList.length && displayCatalog === 'mine'" class="home-rooms-empty flex flex-col items-center gap-4">
          <h3 class="text-base font-bold text-white">
            {{ t('No rooms yet') }}
          </h3>
          <p class="max-w-md text-sm text-white/70">
            {{ t('Create your first room to start playing') }}
          </p>
          <div class="flex flex-col items-center gap-3 sm:flex-row sm:justify-center">
            <Link :href="route('rooms.create')" class="game-btn-secondary inline-flex">
              {{ t('Create my first room') }}
            </Link>
            <Link :href="route('docs.create-content')" class="game-link-action inline-flex items-center gap-1">
              {{ t('Créer rooms & playlists') }}
              <Icon name="cheveron-right" class="inline-block h-4 w-4" />
            </Link>
          </div>
        </div>

        <div v-else-if="!catalogItemsList.length" class="home-rooms-empty">
          <p class="text-sm font-semibold text-white">
            {{ selectedCategoryId ? t('No rooms in this category right now.') : t('No rooms available at the moment.') }}
          </p>
          <button v-if="selectedCategoryId" type="button" class="game-link-action mt-3" @click="selectedCategoryId = ''">
            {{ t('Show all categories') }}
          </button>
        </div>

        <template v-else>
          <div id="catalog-grid" class="grid grid-cols-2 gap-3 sm:gap-4 md:grid-cols-3 xl:grid-cols-4" :class="{ 'opacity-50': showFilterOverlay }">
            <template v-if="isMinigamesCatalog">
              <MinigameCard v-for="game in catalogItemsList" :key="game.type" :game="game" />
            </template>
            <template v-else>
              <Room v-for="room in catalogItemsList" :key="room.id" :room="room" variant="catalog" />
            </template>
          </div>

          <div v-if="hasMore" ref="loadMoreTrigger" class="flex justify-center py-4">
            <button v-if="showLoadMoreButton" type="button" class="game-btn-secondary" :disabled="loadingMore" @click="loadMore">
              {{ loadingMore ? t('Loading rooms...') : t('Show more rooms') }}
            </button>

            <p v-else-if="loadingMore" class="text-sm font-semibold text-white/70" role="status">
              {{ t('Loading rooms...') }}
            </p>
          </div>
        </template>
      </template>
    </div>
  </section>
</template>
