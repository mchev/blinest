<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const page = usePage()

// Translation function for script setup
const __ = (key, replace = {}) => {
  const translation = page.props.language?.[key] || key
  let result = translation
  Object.keys(replace).forEach((k) => {
    result = result.replace(`:${k}`, replace[k])
  })
  return result
}

const metrics = [
  {
    title: __('Score in public rooms'),
    description: __('Your total score accumulated in public rooms. This is the main source of XP.'),
    formula: __('1 point = 1 XP'),
    max: __('No limit'),
    icon: 'podium',
    color: 'text-yellow-500',
    bgColor: 'bg-yellow-500/10',
  },
  {
    title: __('Seniority'),
    description: __('Reward for your loyalty to Blinest. Based on the number of months since your registration.'),
    formula: __('50 XP per month'),
    max: __('600 XP (12 months maximum)'),
    icon: 'calendar',
    color: 'text-blue-500',
    bgColor: 'bg-blue-500/10',
  },
  {
    title: __('Rooms created'),
    description: __('Reward for creating and sharing rooms with the community.'),
    formula: __('100 XP per room'),
    max: __('1000 XP (10 rooms maximum)'),
    icon: 'room',
    color: 'text-green-500',
    bgColor: 'bg-green-500/10',
  },
  {
    title: __('Team membership'),
    description: __('Reward for joining a team and playing together.'),
    formula: __('200 XP'),
    max: __('200 XP (one-time bonus)'),
    icon: 'team',
    color: 'text-purple-500',
    bgColor: 'bg-purple-500/10',
  },
  {
    title: __('Playlists created'),
    description: __('Reward for creating playlists and contributing to the game content.'),
    formula: __('20 XP per playlist'),
    max: __('2000 XP (100 playlists maximum)'),
    icon: 'playlist',
    color: 'text-indigo-500',
    bgColor: 'bg-indigo-500/10',
  },
  {
    title: __('Tracks liked'),
    description: __('Reward for engaging with the community by liking tracks.'),
    formula: __('5 XP per track liked'),
    max: __('1000 XP (200 tracks maximum)'),
    icon: 'heart',
    color: 'text-red-500',
    bgColor: 'bg-red-500/10',
  },
  {
    title: __('Mini-games score'),
    description: __('Reward for playing solo mini-games (Quiz, Who sang?). Your total points from mini-games count as XP.'),
    formula: __('1 point = 1 XP'),
    max: __('1000 XP (maximum)'),
    icon: 'play',
    color: 'text-teal-500',
    bgColor: 'bg-teal-500/10',
  },
  {
    title: __('Consecutive days streak'),
    description: __('Reward for daily connection. Your streak resets if you miss a day.'),
    formula: __('10 XP per day'),
    max: __('300 XP (30 days maximum)'),
    icon: 'fire',
    color: 'text-amber-500',
    bgColor: 'bg-amber-500/10',
  },
]

const levelProgression = [
  { level: 1, xpNeeded: 100, totalXp: '0-99' },
  { level: 2, xpNeeded: 150, totalXp: '100-249' },
  { level: 3, xpNeeded: 200, totalXp: '250-449' },
  { level: 4, xpNeeded: 250, totalXp: '450-699' },
  { level: 5, xpNeeded: 300, totalXp: '700-999' },
  { level: 10, xpNeeded: 500, totalXp: '~3,000-3,499' },
  { level: 20, xpNeeded: 1000, totalXp: '~15,000-15,999' },
  { level: 30, xpNeeded: 1500, totalXp: '~40,000-40,999' },
  { level: 50, xpNeeded: 2500, totalXp: '~100,000-100,999' },
  { level: 100, xpNeeded: 5000, totalXp: '~400,000+' },
]
</script>

<template>
<AppLayout>
    <div class="max-w-4xl mx-auto px-4 py-8 space-y-8">
      <!-- Header -->
      <div class="text-center space-y-4">
        <h1 class="text-4xl font-bold">{{ __('Level System') }}</h1>
        <p class="text-lg text-neutral-400">
          {{ __('Understand how the level system works and how to earn XP to progress.') }}
        </p>
      </div>

      <!-- Introduction -->
      <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 border border-slate-700">
        <h2 class="text-2xl font-bold mb-4">{{ __('How it works') }}</h2>
        <div class="space-y-3 text-neutral-300">
          <p>
            {{ __('The level system rewards your activity and engagement on Blinest. Your level is calculated based on your total XP (Experience Points), which you earn through various activities.') }}
          </p>
          <p>
            {{ __('Your level increases as you accumulate XP. The higher your level, the more XP you need to reach the next level.') }}
          </p>
          <p class="font-semibold text-white">
            {{ __('All XP calculations are updated in real-time when you play in public rooms.') }}
          </p>
          <p class="font-semibold text-green-400">
            {{ __('There is no maximum level - you can progress infinitely! The XP requirement increases by 50 XP per level.') }}
          </p>
        </div>
      </div>

      <!-- Level Progression -->
      <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 border border-slate-700">
        <h2 class="text-2xl font-bold mb-4">{{ __('Level Progression') }}</h2>
        <p class="text-neutral-400 mb-4">
          {{ __('The XP required to reach the next level increases progressively. Here are some examples:') }}
        </p>
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="border-b border-slate-700">
                <th class="text-left py-3 px-4 font-semibold">{{ __('Level') }}</th>
                <th class="text-left py-3 px-4 font-semibold">{{ __('XP needed for next level') }}</th>
                <th class="text-left py-3 px-4 font-semibold">{{ __('Total XP range') }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(prog, index) in levelProgression" :key="prog.level" :class="index % 2 === 0 ? 'bg-slate-800/50' : ''">
                <td class="py-3 px-4 font-bold">{{ prog.level }}</td>
                <td class="py-3 px-4">{{ prog.xpNeeded }} {{ __('XP') }}</td>
                <td class="py-3 px-4">{{ prog.totalXp }} {{ __('XP') }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <p class="text-xs text-neutral-500 mt-4">
          {{ __('Note: The XP requirement increases by 50 XP per level. There is no maximum level - you can progress infinitely!') }}
        </p>
      </div>

      <!-- XP Sources -->
      <div class="space-y-6">
        <h2 class="text-2xl font-bold">{{ __('How to earn XP') }}</h2>
        <p class="text-neutral-400">
          {{ __('Here are all the ways you can earn XP to increase your level:') }}
        </p>

        <div class="grid gap-4 md:grid-cols-2">
          <div
            v-for="metric in metrics"
            :key="metric.title"
            :class="['rounded-xl p-5 border border-slate-700', metric.bgColor]"
          >
            <div class="flex items-start gap-4">
              <div :class="['flex-shrink-0 p-2 rounded-lg', metric.bgColor]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" :class="['h-6 w-6', metric.color]">
                  <path v-if="metric.icon === 'podium'" stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236v4.069m0 0a8.97 8.97 0 01-2.916.52m2.916-.52v4.069m0 0v2.613m0 0c-1.027-.083-2.05-.213-3.062-.38m3.062.38v-2.613m0 0h2.613m-2.613 0a8.97 8.97 0 01-2.916-.52m2.916.52v-2.613m0 0h-2.613m2.613 0a8.97 8.97 0 012.916-.52m-2.916.52v4.069m0 0h2.613m-2.613 0a8.97 8.97 0 00-2.916.52m2.916-.52v-2.613m0 0h-2.613m2.613 0a8.97 8.97 0 012.916-.52m-2.916.52v-2.613m0 0h-2.613m2.613 0a8.97 8.97 0 00-2.916-.52m2.916.52v-4.069m0 0h-2.613m2.613 0a8.97 8.97 0 012.916-.52m-2.916-.52v4.069m0 0h-2.613" />
                  <path v-else-if="metric.icon === 'calendar'" stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                  <path v-else-if="metric.icon === 'room'" stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                  <path v-else-if="metric.icon === 'playlist'" stroke-linecap="round" stroke-linejoin="round" d="M9 9l10.5-3m0 6.553v3.75a2.25 2.25 0 01-1.632 2.163l-1.32.377a1.803 1.803 0 11-.99-3.467l2.31-.66a2.25 2.25 0 001.632-2.163zm0 0V2.25L9 5.25v10.303m0 0v3.75a2.25 2.25 0 01-1.632 2.163l-1.32.377a1.803 1.803 0 01-.99-3.467l2.31-.66A2.25 2.25 0 009 15.553z" />
                  <path v-else-if="metric.icon === 'team'" stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                  <path v-else-if="metric.icon === 'check'" stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  <path v-else-if="metric.icon === 'heart'" stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                  <path v-else-if="metric.icon === 'message'" stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                  <path v-else-if="metric.icon === 'explore'" stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.944 11.944 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                  <path v-else-if="metric.icon === 'fire'" stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 10.601a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z" />
                  <path v-else stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-3h6" />
                </svg>
              </div>
              <div class="flex-1 space-y-2">
                <h3 :class="['text-lg font-bold', metric.color]">
                  {{ metric.title }}
                </h3>
                <p class="text-sm text-neutral-300">
                  {{ metric.description }}
                </p>
                <div class="flex flex-wrap gap-2 pt-2">
                  <span class="text-xs font-semibold bg-slate-700 px-2 py-1 rounded">
                    {{ metric.formula }}
                  </span>
                  <span class="text-xs text-neutral-400 px-2 py-1">
                    {{ __('Max') }}: {{ metric.max }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Updates -->
      <div class="bg-gradient-to-br from-blue-900/20 to-blue-800/20 rounded-2xl p-6 border border-blue-700/50">
        <h2 class="text-2xl font-bold mb-4 text-blue-400">{{ __('When are levels updated?') }}</h2>
        <ul class="space-y-2 text-neutral-300">
          <li class="flex items-start gap-2">
            <span class="text-blue-400 mt-1">•</span>
            <span>{{ __('In real-time when you score points in public rooms') }}</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="text-blue-400 mt-1">•</span>
            <span>{{ __('When you create a room or playlist') }}</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="text-blue-400 mt-1">•</span>
            <span>{{ __('When you like or unlike a track') }}</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="text-blue-400 mt-1">•</span>
            <span>{{ __('When you join a team') }}</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="text-blue-400 mt-1">•</span>
            <span>{{ __('When you log in (if not updated in the last hour)') }}</span>
          </li>
        </ul>
      </div>

      <!-- Important Notes -->
      <div class="bg-gradient-to-br from-amber-900/20 to-amber-800/20 rounded-2xl p-6 border border-amber-700/50">
        <h2 class="text-2xl font-bold mb-4 text-amber-400">{{ __('Important Notes') }}</h2>
        <ul class="space-y-2 text-neutral-300">
          <li class="flex items-start gap-2">
            <span class="text-amber-400 mt-1">•</span>
            <span>{{ __('Only scores from public rooms (without password) count towards your level.') }}</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="text-amber-400 mt-1">•</span>
            <span>{{ __('Each metric has a maximum XP cap to ensure balanced progression.') }}</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="text-amber-400 mt-1">•</span>
            <span>{{ __('Your consecutive days streak resets if you don\'t log in for more than 24 hours.') }}</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="text-amber-400 mt-1">•</span>
            <span>{{ __('Level updates are broadcast in real-time, so you\'ll see your level increase immediately.') }}</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="text-amber-400 mt-1">•</span>
            <span>{{ __('There is no maximum level - the progression continues infinitely. The XP requirement increases by 50 XP per level (not by 10).') }}</span>
          </li>
        </ul>
      </div>

      <!-- Back link -->
      <div class="text-center pt-4">
        <Link :href="route('home')" class="text-blue-400 hover:text-blue-300 transition-colors">
          ← {{ __('Back to home') }}
        </Link>
      </div>
    </div>
  </AppLayout>
</template>

