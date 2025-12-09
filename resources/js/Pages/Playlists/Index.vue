<script setup>
import { watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Icon from '@/Components/Icon.vue'
import Card from '@/Components/Card.vue'
import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'
import Pagination from '@/Components/Pagination.vue'
import TextInput from '@/Components/TextInput.vue'

const props = defineProps({
  filters: Object,
  playlists: Object,
})

const form = useForm({
  search: props.filters.search,
  trashed: props.filters.trashed,
})

watch(
  form,
  throttle(() => {
    router.get('/playlists', pickBy(form), { remember: 'forget', preserveState: true })
  }, 150),
  { deep: true },
)

const reset = () => {
  form.reset()
}
</script>

<template>
  <Head :title="__('My Playlists')" />
  <AppLayout>
    <!-- Header -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
      <h1 class="text-2xl lg:text-3xl font-bold text-neutral-100">{{ __('My Playlists') }}</h1>
      <Link class="btn-primary flex items-center justify-center gap-2" :href="route('playlists.create')">
        <Icon name="plus" class="h-4 w-4" />
        <span>{{ __('Create a playlist') }}</span>
      </Link>
    </div>

    <!-- Search -->
    <Card class="mb-6">
      <div class="p-4">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
          <div class="flex-1">
            <text-input 
              v-model="form.search" 
              prepend-icon="search" 
              :placeholder="__('Search playlists') + '...'"
              class="w-full bg-neutral-900/50 border-neutral-600/50 focus:border-blinest-500/50"
            />
          </div>
          <button 
            v-if="form.search" 
            class="px-4 py-2 text-sm text-neutral-400 hover:text-neutral-200 transition-colors"
            @click="reset"
          >
            {{ __('Reset') }}
          </button>
        </div>
      </div>
    </Card>

    <!-- Playlists Grid -->
    <div v-if="playlists.data.length > 0" class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
      <Card 
        v-for="playlist in playlists.data" 
        :key="playlist.id"
        class="group hover:border-blinest-500/50 transition-all duration-200 cursor-pointer"
      >
        <Link :href="route('playlists.edit', playlist.id)" class="block">
          <div class="p-4 lg:p-5">
            <!-- Header -->
            <div class="mb-4 flex items-start justify-between gap-3">
              <div class="flex-1 min-w-0">
                <h3 class="text-lg font-bold text-neutral-200 mb-1 truncate group-hover:text-blinest-400 transition-colors">
                  {{ playlist.name }}
                </h3>
                <p v-if="playlist.description" class="text-sm text-neutral-400 line-clamp-2">
                  {{ playlist.description }}
                </p>
              </div>
              <Icon 
                v-if="playlist.deleted_at" 
                name="trash" 
                class="h-4 w-4 flex-shrink-0 text-red-400" 
              />
            </div>

            <!-- Stats -->
            <div class="mb-4 flex items-center gap-4 text-xs text-neutral-400">
              <div class="flex items-center gap-1.5">
                <Icon name="music" class="h-3.5 w-3.5" />
                <span>{{ playlist.total_tracks || 0 }} {{ __('tracks') }}</span>
              </div>
              <div class="flex items-center gap-1.5">
                <Icon name="users" class="h-3.5 w-3.5" />
                <span>{{ playlist.moderators.length }} {{ __('Moderators') }}</span>
              </div>
            </div>

            <!-- Owner & Moderators -->
            <div class="space-y-2 border-t border-neutral-700/50 pt-3">
              <div class="flex items-center gap-2 text-xs">
                <span class="text-neutral-500">{{ __('Owner') }}:</span>
                <span class="text-neutral-300">{{ playlist.owner.name }}</span>
              </div>
              <div v-if="playlist.moderators.length > 0" class="flex flex-wrap items-center gap-1.5">
                <span class="text-xs text-neutral-500">{{ __('Moderators') }}:</span>
                <span 
                  v-for="moderator in playlist.moderators.slice(0, 3)" 
                  :key="moderator.id"
                  class="inline-flex items-center rounded-full bg-neutral-700/50 px-2 py-0.5 text-xs text-neutral-300"
                >
                  {{ moderator.name }}
                </span>
                <span 
                  v-if="playlist.moderators.length > 3"
                  class="text-xs text-neutral-500"
                >
                  +{{ playlist.moderators.length - 3 }}
                </span>
              </div>
            </div>

            <!-- Arrow indicator -->
            <div class="mt-4 flex items-center justify-end">
              <Icon name="cheveron-right" class="h-5 w-5 text-neutral-500 group-hover:text-blinest-500 transition-colors" />
            </div>
          </div>
        </Link>
      </Card>
    </div>

    <!-- Empty State -->
    <Card v-else class="p-8 text-center">
      <div class="flex flex-col items-center gap-3">
        <Icon name="music" class="h-12 w-12 text-neutral-600" />
        <div>
          <h3 class="text-lg font-semibold text-neutral-200 mb-1">{{ __('No playlists found') }}</h3>
          <p class="text-sm text-neutral-400">
            {{ form.search ? __('Try adjusting your search') : __('Create your first playlist to get started') }}
          </p>
        </div>
        <Link v-if="!form.search" class="btn-primary mt-2" :href="route('playlists.create')">
          {{ __('Create a playlist') }}
        </Link>
      </div>
    </Card>

    <!-- Pagination -->
    <div v-if="playlists.links && playlists.links.length > 3" class="mt-6">
      <Pagination :links="playlists.links" />
    </div>
  </AppLayout>
</template>
