import { computed, nextTick, onMounted, onUnmounted, ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const MERGE_HEADER = 'X-Inertia-Infinite-Scroll-Merge-Intent'

const pageOverflows = () => document.documentElement.scrollHeight > window.innerHeight + 64

export function useCatalogLoadMore(getCatalogItems, getQuery) {
  const loading = ref(false)
  const loadMoreTrigger = ref(null)
  const allowsAutoLoad = ref(false)

  const hasMore = computed(() => !!getCatalogItems()?.next_page_url)

  const showLoadMoreButton = computed(() => hasMore.value && !allowsAutoLoad.value)

  const loadMore = () => {
    const catalogItems = getCatalogItems()

    if (loading.value || !catalogItems?.next_page_url) {
      return
    }

    loading.value = true

    router.get(
      route('home'),
      {
        catalog: catalogItems.current_page + 1,
        ...getQuery(),
      },
      {
        only: ['catalog_items'],
        preserveState: true,
        preserveScroll: true,
        showProgress: false,
        headers: { [MERGE_HEADER]: 'append' },
        onFinish: () => {
          loading.value = false
        },
      },
    )
  }

  let observer = null

  const syncAutoLoad = async () => {
    await nextTick()

    observer?.disconnect()
    observer = null
    allowsAutoLoad.value = pageOverflows()

    if (!allowsAutoLoad.value || !loadMoreTrigger.value || !hasMore.value) {
      return
    }

    observer = new IntersectionObserver(
      (entries) => {
        if (entries.some((entry) => entry.isIntersecting)) {
          loadMore()
        }
      },
      { rootMargin: '240px' },
    )

    observer.observe(loadMoreTrigger.value)
  }

  watch(() => [getCatalogItems()?.data?.length, hasMore.value], syncAutoLoad, { flush: 'post' })

  watch(loadMoreTrigger, syncAutoLoad)

  onMounted(() => {
    window.addEventListener('resize', syncAutoLoad, { passive: true })
  })

  onUnmounted(() => {
    observer?.disconnect()
    window.removeEventListener('resize', syncAutoLoad)
  })

  return {
    loading,
    hasMore,
    showLoadMoreButton,
    loadMore,
    loadMoreTrigger,
    syncAutoLoad,
  }
}
