<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import EloBadge from '@/Components/EloBadge.vue'
import LevelBadge from '@/Components/LevelBadge.vue'

const page = usePage()

// Translation function
const __ = (key, replace = {}) => {
  const translation = page.props.language?.[key] || key
  let result = translation
  Object.keys(replace).forEach((k) => {
    result = result.replace(`:${k}`, replace[k])
  })
  return result
}
</script>

<template>
  <AppLayout>
    <div class="mx-auto max-w-5xl space-y-8 px-4 py-8">
      <!-- Header -->
      <div class="space-y-4 text-center">
        <h1 class="text-4xl font-bold">Les systèmes de progression</h1>
        <p class="text-lg text-neutral-400">Vue d'ensemble des systèmes de progression : Scores, Levels et ELO</p>
        <p class="text-sm text-neutral-500">Cette page présente une synthèse des trois systèmes de progression sur Blinest. Pour des guides détaillés, consultez les sections spécifiques ci-dessous.</p>
      </div>

      <!-- Introduction -->
      <div class="rounded-2xl border border-slate-700 bg-gradient-to-br from-slate-800 to-slate-900 p-6">
        <h2 class="mb-4 text-2xl font-bold">Pourquoi 3 systèmes ?</h2>
        <p class="mb-4 text-neutral-300">On a créé trois systèmes différents pour récompenser différents aspects de ton expérience sur Blinest :</p>
        <ul class="list-inside list-disc space-y-2 text-neutral-300">
          <li><strong>Les Scores</strong> : Pour mesurer ton activité et ta participation</li>
          <li><strong>Les Levels</strong> : Pour récompenser ton engagement global sur la plateforme</li>
          <li><strong>L'ELO</strong> : Pour évaluer ta compétence réelle dans les parties compétitives</li>
        </ul>
        <p class="mt-4 text-neutral-300">Chaque système a son rôle et ils se complètent bien. Ensemble, ça donne une progression plus riche et équilibrée.</p>
      </div>

      <!-- Comparison Table -->
      <div class="overflow-x-auto rounded-2xl border border-slate-700 bg-gradient-to-br from-slate-800 to-slate-900 p-6">
        <h2 class="mb-6 text-2xl font-bold">Comparaison des systèmes</h2>
        <table class="w-full border-collapse text-sm">
          <thead>
            <tr class="border-b border-slate-700">
              <th class="bg-slate-800/50 px-4 py-3 text-left font-semibold">Caractéristique</th>
              <th class="bg-slate-800/50 px-4 py-3 text-left font-semibold">Scores</th>
              <th class="bg-slate-800/50 px-4 py-3 text-left font-semibold">Levels</th>
              <th class="bg-slate-800/50 px-4 py-3 text-left font-semibold">ELO</th>
            </tr>
          </thead>
          <tbody>
            <tr class="border-b border-slate-700/50 hover:bg-slate-800/30">
              <td class="px-4 py-3 font-semibold">Objectif</td>
              <td class="px-4 py-3">Mesurer l'activité</td>
              <td class="px-4 py-3">Récompenser l'engagement</td>
              <td class="px-4 py-3">Évaluer la compétence</td>
            </tr>
            <tr class="border-b border-slate-700/50 hover:bg-slate-800/30">
              <td class="px-4 py-3 font-semibold">Évolution</td>
              <td class="px-4 py-3">Toujours croissante</td>
              <td class="px-4 py-3">Toujours croissante</td>
              <td class="px-4 py-3">Peut augmenter ou diminuer</td>
            </tr>
            <tr class="border-b border-slate-700/50 hover:bg-slate-800/30">
              <td class="px-4 py-3 font-semibold">Où ça compte</td>
              <td class="px-4 py-3">Toutes les rooms</td>
              <td class="px-4 py-3">Rooms publiques uniquement</td>
              <td class="px-4 py-3">Rooms publiques, 3+ joueurs</td>
            </tr>
            <tr class="border-b border-slate-700/50 hover:bg-slate-800/30">
              <td class="px-4 py-3 font-semibold">Affichage pendant les parties</td>
              <td class="px-4 py-3">✅ Oui</td>
              <td class="px-4 py-3">❌ Non (dans les profils)</td>
              <td class="px-4 py-3">✅ Oui</td>
            </tr>
            <tr class="border-b border-slate-700/50 hover:bg-slate-800/30">
              <td class="px-4 py-3 font-semibold">Influence des adversaires</td>
              <td class="px-4 py-3">Aucune</td>
              <td class="px-4 py-3">Aucune</td>
              <td class="px-4 py-3">✅ Oui (plus fort = plus de points)</td>
            </tr>
            <tr class="border-b border-slate-700/50 hover:bg-slate-800/30">
              <td class="px-4 py-3 font-semibold">Valeur initiale</td>
              <td class="px-4 py-3">0</td>
              <td class="px-4 py-3">Level 1</td>
              <td class="px-4 py-3">1500</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Scores Section -->
      <div class="rounded-2xl border border-yellow-700/50 bg-gradient-to-br from-yellow-900/20 to-yellow-800/20 p-6">
        <div class="mb-4 flex items-start gap-4">
          <div class="flex-shrink-0">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-yellow-500/20">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6 text-yellow-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236v4.069m0 0a8.97 8.97 0 01-2.916.52m2.916-.52v4.069m0 0v2.613m0 0c-1.027-.083-2.05-.213-3.062-.38m3.062.38v-2.613m0 0h2.613m-2.613 0a8.97 8.97 0 01-2.916-.52m2.916.52v-2.613m0 0h-2.613m2.613 0a8.97 8.97 0 012.916-.52m-2.916.52v4.069m0 0h2.613m-2.613 0a8.97 8.97 0 00-2.916.52m2.916-.52v-2.613m0 0h-2.613m2.613 0a8.97 8.97 0 012.916-.52m-2.916.52v-2.613m0 0h-2.613m2.613 0a8.97 8.97 0 012.916-.52m-2.916.52v-2.613m0 0h-2.613m2.613 0a8.97 8.97 0 00-2.916-.52m2.916-.52v-4.069m0 0h-2.613m2.613 0a8.97 8.97 0 012.916-.52m-2.916-.52v4.069m0 0h-2.613" />
              </svg>
            </div>
          </div>
          <div class="flex-1">
            <h2 class="mb-2 text-2xl font-bold text-yellow-400">Scores</h2>
            <p class="mb-4 text-neutral-300">Les scores, c'est simple : chaque bonne réponse te rapporte des points. Plus tu joues, plus tu accumules de points.</p>
            <div class="space-y-2 text-sm text-neutral-300">
              <p><strong>En bref :</strong></p>
              <ul class="ml-4 list-inside list-disc space-y-1">
                <li>Ça compte dans <strong>toutes les rooms</strong> (publiques et privées)</li>
                <li>Ça ne peut <strong>jamais baisser</strong> - ça s'accumule</li>
                <li>Ça mesure ton <strong>activité totale</strong> sur Blinest</li>
                <li>C'est affiché pendant les parties pour voir qui mène</li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Levels Section -->
      <div class="rounded-2xl border border-blue-700/50 bg-gradient-to-br from-blue-900/20 to-blue-800/20 p-6">
        <div class="mb-4 flex items-start gap-4">
          <div class="flex-shrink-0">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-500/20">
              <LevelBadge :level="25" size="md" variant="compact" />
            </div>
          </div>
          <div class="flex-1">
            <h2 class="mb-2 text-2xl font-bold text-blue-400">Levels</h2>
            <p class="mb-4 text-neutral-300">Les levels récompensent ton engagement global sur Blinest. Tu gagnes de l'XP (Experience Points) en participant activement à la communauté.</p>
            <div class="space-y-2 text-sm text-neutral-300">
              <p><strong>En bref :</strong></p>
              <ul class="ml-4 list-inside list-disc space-y-1">
                <li>Ça compte uniquement dans les <strong>rooms publiques</strong></li>
                <li>C'est basé sur plusieurs trucs : scores, ancienneté, rooms créées, playlists, etc.</li>
                <li>Ça ne peut <strong>jamais baisser</strong> - progression continue</li>
                <li>C'est affiché dans les <strong>profils</strong> pour montrer ton expérience</li>
                <li>Chaque level demande plus d'XP que le précédent</li>
              </ul>
            </div>
            <div class="mt-4">
              <Link :href="route('docs.level')" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-400 transition-colors hover:text-blue-300">
                Tout savoir sur les Levels
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
              </Link>
            </div>
          </div>
        </div>
      </div>

      <!-- ELO Section -->
      <div class="rounded-2xl border border-purple-700/50 bg-gradient-to-br from-purple-900/20 to-purple-800/20 p-6">
        <div class="mb-4 flex items-start gap-4">
          <div class="flex-shrink-0">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-500/20">
              <EloBadge :elo="1850" size="md" variant="compact" />
            </div>
          </div>
          <div class="flex-1">
            <h2 class="mb-2 text-2xl font-bold text-purple-400">ELO</h2>
            <p class="mb-4 text-neutral-300">L'ELO, c'est un système de classement compétitif qui évalue ton niveau réel. Contrairement aux scores et levels, l'ELO peut monter ou descendre selon tes performances.</p>
            <div class="space-y-2 text-sm text-neutral-300">
              <p><strong>En bref :</strong></p>
              <ul class="ml-4 list-inside list-disc space-y-1">
                <li>Ça compte uniquement dans les <strong>rooms publiques avec 3+ joueurs</strong></li>
                <li>Ça peut <strong>monter ou descendre</strong> selon tes performances</li>
                <li>Ça prend en compte le <strong>niveau de tes adversaires</strong></li>
                <li>C'est affiché <strong>pendant les parties</strong> pour évaluer la compétence</li>
                <li>Système de <strong>placement</strong> pour les nouveaux joueurs (10 premiers rounds)</li>
                <li>K-factor variable selon ton niveau d'expérience</li>
                <li><strong>Calcul proportionnel</strong> : Si tu arrives en milieu de partie, ton ELO évolue proportionnellement aux tracks jouées (minimum 80%)</li>
              </ul>
            </div>
            <div class="mt-4">
              <Link :href="route('docs.elo')" class="inline-flex items-center gap-2 text-sm font-semibold text-purple-400 transition-colors hover:text-purple-300">
                Tout savoir sur l'ELO
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
              </Link>
            </div>
          </div>
        </div>
      </div>

      <!-- When to use what -->
      <div class="rounded-2xl border border-green-700/50 bg-gradient-to-br from-green-900/20 to-green-800/20 p-6">
        <h2 class="mb-4 text-2xl font-bold text-green-400">Où les voir ?</h2>
        <div class="space-y-4">
          <div class="rounded-lg bg-slate-800/50 p-4">
            <h3 class="mb-2 font-semibold text-white">Pendant une partie</h3>
            <p class="text-sm text-neutral-300">Tu verras les <strong>scores</strong> (pour voir qui mène) et l'<strong>ELO</strong> (pour évaluer la compétence des joueurs). Le level n'est pas affiché pendant les parties car il représente l'expérience globale, pas la compétence dans cette partie spécifique.</p>
          </div>
          <div class="rounded-lg bg-slate-800/50 p-4">
            <h3 class="mb-2 font-semibold text-white">Dans un profil</h3>
            <p class="text-sm text-neutral-300">Tu verras les <strong>scores totaux</strong>, le <strong>level</strong> (expérience) et l'<strong>ELO</strong> (compétence). Ça donne une vue complète de l'activité et du niveau du joueur.</p>
          </div>
          <div class="rounded-lg bg-slate-800/50 p-4">
            <h3 class="mb-2 font-semibold text-white">Dans les classements</h3>
            <p class="text-sm text-neutral-300">Les classements peuvent être triés par <strong>score</strong> (activité), <strong>level</strong> (expérience) ou <strong>ELO</strong> (compétence). Chaque classement montre un aspect différent de la progression.</p>
          </div>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="rounded-2xl border border-slate-700 bg-gradient-to-br from-slate-800 to-slate-900 p-6">
        <h2 class="mb-4 text-2xl font-bold">Pour aller plus loin</h2>
        <div class="grid gap-4 md:grid-cols-2">
          <Link :href="route('docs.level')" class="group flex items-center gap-4 rounded-lg border border-slate-700 bg-slate-800/50 p-4 transition-all hover:bg-slate-800/70">
            <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-blue-500/20 transition-colors group-hover:bg-blue-500/30">
              <LevelBadge :level="10" size="sm" variant="compact" />
            </div>
            <div class="flex-1">
              <h3 class="font-semibold text-white transition-colors group-hover:text-blue-400">Système de Levels</h3>
              <p class="mt-1 text-xs text-neutral-400">Comment gagner de l'XP et progresser</p>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5 text-neutral-400 transition-colors group-hover:text-blue-400">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
          </Link>

          <Link :href="route('docs.elo')" class="group flex items-center gap-4 rounded-lg border border-slate-700 bg-slate-800/50 p-4 transition-all hover:bg-slate-800/70">
            <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-purple-500/20 transition-colors group-hover:bg-purple-500/30">
              <EloBadge :elo="2000" size="sm" variant="compact" />
            </div>
            <div class="flex-1">
              <h3 class="font-semibold text-white transition-colors group-hover:text-purple-400">Système ELO</h3>
              <p class="mt-1 text-xs text-neutral-400">Tout savoir sur le classement compétitif</p>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5 text-neutral-400 transition-colors group-hover:text-purple-400">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
          </Link>

          <Link :href="route('docs.glossary')" class="group flex items-center gap-4 rounded-lg border border-slate-700 bg-slate-800/50 p-4 transition-all hover:bg-slate-800/70">
            <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-green-500/20 transition-colors group-hover:bg-green-500/30">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6 text-green-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
              </svg>
            </div>
            <div class="flex-1">
              <h3 class="font-semibold text-white transition-colors group-hover:text-green-400">Glossaire</h3>
              <p class="mt-1 text-xs text-neutral-400">Définitions des termes techniques</p>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5 text-neutral-400 transition-colors group-hover:text-green-400">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
          </Link>

          <Link :href="route('docs.howto')" class="group flex items-center gap-4 rounded-lg border border-slate-700 bg-slate-800/50 p-4 transition-all hover:bg-slate-800/70">
            <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-orange-500/20 transition-colors group-hover:bg-orange-500/30">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6 text-orange-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h5.25c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
              </svg>
            </div>
            <div class="flex-1">
              <h3 class="font-semibold text-white transition-colors group-hover:text-orange-400">Guide de démarrage</h3>
              <p class="mt-1 text-xs text-neutral-400">Comment jouer à un blind test</p>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5 text-neutral-400 transition-colors group-hover:text-orange-400">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
          </Link>

          <Link :href="route('docs.create-content')" class="group flex items-center gap-4 rounded-lg border border-slate-700 bg-slate-800/50 p-4 transition-all hover:bg-slate-800/70">
            <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-teal-500/20 transition-colors group-hover:bg-teal-500/30">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6 text-teal-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
              </svg>
            </div>
            <div class="flex-1">
              <h3 class="font-semibold text-white transition-colors group-hover:text-teal-400">Créer du contenu</h3>
              <p class="mt-1 text-xs text-neutral-400">Rooms, playlists, tracks et réponses</p>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5 text-neutral-400 transition-colors group-hover:text-teal-400">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
          </Link>
        </div>
      </div>

      <!-- Summary -->
      <div class="rounded-2xl border border-indigo-700/50 bg-gradient-to-br from-indigo-900/20 to-indigo-800/20 p-6">
        <h2 class="mb-4 text-2xl font-bold text-indigo-400">En bref</h2>
        <div class="space-y-3 text-neutral-300">
          <p><strong>Scores</strong> = Ton activité totale, toujours croissante, visible partout</p>
          <p><strong>Levels</strong> = Ton expérience et engagement, toujours croissant, visible dans les profils</p>
          <p><strong>ELO</strong> = Ta compétence réelle, peut varier, visible pendant les parties compétitives</p>
          <p class="mt-4 font-semibold text-white">Ces trois systèmes se complètent pour offrir une progression plus riche et équilibrée, qui récompense à la fois l'activité, l'engagement et la compétence.</p>
        </div>
      </div>

      <!-- Back link -->
      <div class="pt-4 text-center">
        <Link :href="route('home')" class="text-blue-400 transition-colors hover:text-blue-300"> ← Retour à l'accueil </Link>
      </div>
    </div>
  </AppLayout>
</template>
