<script setup>
import { ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import ScoresTab from './partials/ScoresTab.vue'
import LikesTab from './partials/LikesTab.vue'
import BookmarksTab from './partials/BookmarksTab.vue'
import Badge from '@/Components/Badge.vue'

const props = defineProps({
  user: Object,
})

const tab = ref('scores')

</script>
<template>
  <Head :title="user.name" />
  <AppLayout>
    <section class="relative py-16 mt-64">
        <div class="container mx-auto px-4">
          <div class="relative flex flex-col min-w-0 break-words bg-gradient-to-br from-neutral-800 to-neutral-700 w-full mb-6 shadow-xl rounded-lg -mt-64">
            <div class="px-6">
              <div class="flex flex-wrap justify-center">
                <div class="w-full lg:w-3/12 px-4 lg:order-2 flex justify-center">
                    <img alt="..." :src="user.photo" class="shadow-xl rounded-full w-40 h-40 align-middle border-none absolute -m-16 -ml-20 lg:-ml-16 max-w-150-px">
                </div>
                <div class="w-full lg:w-4/12 px-4 hidden md:block lg:order-3 lg:text-right lg:self-center">
                  <div class="py-6 px-3 mt-32 sm:mt-0">
                    <div class="lg:mr-4 p-3 text-center">
                      <span class="text-xl font-bold block uppercase tracking-wide text-white-600 flex items-center gap-1 justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                          <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                        </svg>
                        {{ user.stats.bookmarks }}
                      </span>
                      <span class="text-sm text-white-400">{{ __('Bookmark') }}</span>
                    </div>
                  </div>
                </div>
                <div class="w-full lg:w-4/12 px-4 lg:order-1 hidden md:block">
                  <div class="flex justify-center py-4 lg:pt-4 pt-8">
                    <div class="mr-4 p-3 text-center">
                      <span class="text-xl font-bold block uppercase tracking-wide text-white-600">{{ user.stats.rooms }}</span><span class="text-sm text-white-400">{{ __('Rooms') }}</span>
                    </div>
                    <div class="mr-4 p-3 text-center">
                      <span class="text-xl font-bold block uppercase tracking-wide text-white-600">{{ user.stats.playlists }}</span><span class="text-sm text-white-400">{{ __('Playlists') }}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="text-center mt-32 md:mt-12">
                <div class="mb-4">
                  <h1 class="text-4xl font-semibold text-white-700">
                    {{ user.name }}
                  </h1>
                  <Link :href="route('teams.show', user.team)" v-if="user.team" class="text-sm text-neutral-400">{{ user.team.name}}</Link>
                </div>
                <div class="flex justify-center items-center gap-2 text-sm font-bold uppercase">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                  </svg>
                  {{ __('Member since') }} {{ user.created_at_from_now }}
                </div>
                <div class="flex justify-center items-center gap-2 text-4xl font-bold mt-12 mb-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0" />
                  </svg>
                  {{ user.total_score }} <sup>{{ __('PTS') }}</sup>
                </div>
                <div class="flex gap-8 text-sm items-center uppercase justify-center">
                  <div class="flex flex-col">
                    <span class="font-bold">{{ __('Public rooms') }}</span>
                    <span>{{ user.total_public_score }} <sup>{{ __('PTS') }}</sup></span>
                  </div>
                  <div class="flex flex-col">
                    <span class="font-bold">{{ __('Private rooms') }}</span>
                    <span>{{ user.total_private_score }} <sup>{{ __('PTS') }}</sup></span>
                  </div>
                </div>

<!--                 <div class="flex w-full justify-center items-center gap-4 flex-wrap my-12">
                  <Badge color="silver" :text="__('Seniority')" class="h-16" />
                </div> -->

              </div>
              <div class="mt-10 py-10 border-t border-white-200 text-center">

                <ul class="flex flex-wrap border-b border-neutral-700 text-center text-sm font-medium text-neutral-400 max-w-2xl justify-center mx-auto">
                  <li class="mr-2">
                    <button type="button" @click="tab = 'scores'" class="inline-block rounded-t-lg p-4 hover:bg-neutral-800 hover:text-neutral-300" :class="{ 'active bg-neutral-800 text-neutral-300': tab == 'scores' }">{{ __('Scores') }}</button>
                  </li>
                  <li class="mr-2">
                    <button type="button" @click="tab = 'likes'" class="inline-block rounded-t-lg p-4 hover:bg-neutral-800 hover:text-neutral-300" :class="{ 'active bg-neutral-800 text-neutral-300': tab == 'likes' }">{{ __('Likes') }}</button>
                  </li>
                  <li class="mr-2">
                    <button type="button" @click="tab = 'bookmarks'" class="inline-block rounded-t-lg p-4 hover:bg-neutral-800 hover:text-neutral-300" :class="{ 'active bg-neutral-800 text-neutral-300': tab == 'bookmarks' }">{{ __('Bookmark') }}</button>
                  </li>
                </ul>

                <div class="flex flex-wrap justify-center">
                  <div class="w-full lg:w-9/12 lg:px-4">
                    <ScoresTab v-show="tab == 'scores'" :user="user"/>
                    <LikesTab v-show="tab == 'likes'" :user="user"/>
                    <BookmarksTab v-show="tab == 'bookmarks'" :user="user"/>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </section>
  </AppLayout>
</template>