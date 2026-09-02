<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Dropdown from '@/Components/Dropdown.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  categories: {
    type: Array,
    default: () => [],
  },
  modelValue: {
    type: Array,
    default: () => [],
  },
  disabled: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['update:modelValue'])

const page = usePage()

const t = (key, replace = {}) => {
  let translation = page.props.language?.[key] ?? key

  Object.entries(replace).forEach(([placeholder, value]) => {
    translation = translation.replace(`:${placeholder}`, String(value))
  })

  return translation
}

const selectedIds = computed(() => props.modelValue.map(String))

const isSelected = (categoryId) => selectedIds.value.includes(String(categoryId))

const triggerLabel = computed(() => {
  if (!selectedIds.value.length) {
    return t('All categories')
  }

  if (selectedIds.value.length === 1) {
    const category = props.categories.find((item) => String(item.id) === selectedIds.value[0])

    return category ? t(category.name) : t('All categories')
  }

  return t(':count categories', { count: selectedIds.value.length })
})

const toggleCategory = (categoryId) => {
  const id = String(categoryId)

  if (isSelected(categoryId)) {
    emit(
      'update:modelValue',
      selectedIds.value.filter((value) => value !== id),
    )

    return
  }

  emit('update:modelValue', [...selectedIds.value, id])
}

const clearSelection = () => {
  emit('update:modelValue', [])
}
</script>

<template>
  <Dropdown :auto-close="false" placement="bottom-end" :overlay="false" class="home-category-filter">
    <div
      class="home-category-filter__trigger"
      :class="{ 'home-category-filter__trigger--active': selectedIds.length > 0 }"
      :aria-disabled="disabled"
      :aria-label="t('Filter by category')"
      :title="triggerLabel"
    >
      <svg class="home-category-filter__icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
        <path d="M3 4.5a.75.75 0 0 1 .75-.75h12.5a.75.75 0 0 1 .53 1.28l-4.72 4.72v4.19a.75.75 0 0 1-1.085.67L7.5 13.09V9.5L2.72 4.72A.75.75 0 0 1 3 4.5Z" />
      </svg>
      <span class="home-category-filter__value">{{ triggerLabel }}</span>
      <span v-if="selectedIds.length" class="home-category-filter__badge">{{ selectedIds.length }}</span>
      <Icon name="cheveron-down" class="home-category-filter__chevron" aria-hidden="true" />
    </div>

    <template #dropdown>
      <div class="home-category-filter__panel" role="listbox" :aria-label="t('Filter by category')" :aria-multiselectable="true">
        <button type="button" class="home-category-filter__option" :class="{ 'home-category-filter__option--selected': !selectedIds.length }" @click="clearSelection">
          <span class="home-category-filter__check" aria-hidden="true">
            <svg v-if="!selectedIds.length" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
          </span>
          <span class="home-category-filter__option-label">{{ t('All categories') }}</span>
        </button>

        <div class="home-category-filter__divider" role="separator" />

        <button v-for="category in categories" :key="category.id" type="button" class="home-category-filter__option" :class="{ 'home-category-filter__option--selected': isSelected(category.id) }" @click="toggleCategory(category.id)">
          <span class="home-category-filter__check" aria-hidden="true">
            <svg v-if="isSelected(category.id)" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
          </span>
          <span class="home-category-filter__option-label">
            {{ t(category.name) }}
            <span class="home-category-filter__count">({{ category.rooms_count }})</span>
          </span>
        </button>
      </div>
    </template>
  </Dropdown>
</template>
