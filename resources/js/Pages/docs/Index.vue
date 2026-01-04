<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3'
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
  <Head title="Les systèmes de progression - Guide" />
  <AppLayout>
    <div class="max-w-5xl mx-auto px-4 py-8 space-y-8">
      <!-- Header -->
      <div class="text-center space-y-4">
        <h1 class="text-4xl font-bold">Les systèmes de progression</h1>
        <p class="text-lg text-neutral-400">
          Tout ce qu'il faut savoir sur les Scores, Levels et ELO
        </p>
      </div>

      <!-- Introduction -->
      <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 border border-slate-700">
        <h2 class="text-2xl font-bold mb-4">Pourquoi 3 systèmes ?</h2>
        <p class="text-neutral-300 mb-4">
          On a créé trois systèmes différents pour récompenser différents aspects de ton expérience sur Blinest :
        </p>
        <ul class="list-disc list-inside space-y-2 text-neutral-300">
          <li><strong>Les Scores</strong> : Pour mesurer ton activité et ta participation</li>
          <li><strong>Les Levels</strong> : Pour récompenser ton engagement global sur la plateforme</li>
          <li><strong>L'ELO</strong> : Pour évaluer ta compétence réelle dans les parties compétitives</li>
        </ul>
        <p class="text-neutral-300 mt-4">
          Chaque système a son rôle et ils se complètent bien. Ensemble, ça donne une progression plus riche et équilibrée.
        </p>
      </div>

      <!-- Comparison Table -->
      <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 border border-slate-700 overflow-x-auto">
        <h2 class="text-2xl font-bold mb-6">Comparaison des systèmes</h2>
        <table class="w-full text-sm border-collapse">
          <thead>
            <tr class="border-b border-slate-700">
              <th class="text-left py-3 px-4 font-semibold bg-slate-800/50">Caractéristique</th>
              <th class="text-left py-3 px-4 font-semibold bg-slate-800/50">Scores</th>
              <th class="text-left py-3 px-4 font-semibold bg-slate-800/50">Levels</th>
              <th class="text-left py-3 px-4 font-semibold bg-slate-800/50">ELO</th>
            </tr>
          </thead>
          <tbody>
            <tr class="border-b border-slate-700/50 hover:bg-slate-800/30">
              <td class="py-3 px-4 font-semibold">Objectif</td>
              <td class="py-3 px-4">Mesurer l'activité</td>
              <td class="py-3 px-4">Récompenser l'engagement</td>
              <td class="py-3 px-4">Évaluer la compétence</td>
            </tr>
            <tr class="border-b border-slate-700/50 hover:bg-slate-800/30">
              <td class="py-3 px-4 font-semibold">Évolution</td>
              <td class="py-3 px-4">Toujours croissante</td>
              <td class="py-3 px-4">Toujours croissante</td>
              <td class="py-3 px-4">Peut augmenter ou diminuer</td>
            </tr>
            <tr class="border-b border-slate-700/50 hover:bg-slate-800/30">
              <td class="py-3 px-4 font-semibold">Où ça compte</td>
              <td class="py-3 px-4">Toutes les rooms</td>
              <td class="py-3 px-4">Rooms publiques uniquement</td>
              <td class="py-3 px-4">Rooms publiques, 3+ joueurs</td>
            </tr>
            <tr class="border-b border-slate-700/50 hover:bg-slate-800/30">
              <td class="py-3 px-4 font-semibold">Affichage pendant les parties</td>
              <td class="py-3 px-4">✅ Oui</td>
              <td class="py-3 px-4">❌ Non (dans les profils)</td>
              <td class="py-3 px-4">✅ Oui</td>
            </tr>
            <tr class="border-b border-slate-700/50 hover:bg-slate-800/30">
              <td class="py-3 px-4 font-semibold">Influence des adversaires</td>
              <td class="py-3 px-4">Aucune</td>
              <td class="py-3 px-4">Aucune</td>
              <td class="py-3 px-4">✅ Oui (plus fort = plus de points)</td>
            </tr>
            <tr class="border-b border-slate-700/50 hover:bg-slate-800/30">
              <td class="py-3 px-4 font-semibold">Valeur initiale</td>
              <td class="py-3 px-4">0</td>
              <td class="py-3 px-4">Level 1</td>
              <td class="py-3 px-4">1500</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Scores Section -->
      <div class="bg-gradient-to-br from-yellow-900/20 to-yellow-800/20 rounded-2xl p-6 border border-yellow-700/50">
        <div class="flex items-start gap-4 mb-4">
          <div class="flex-shrink-0">
            <div class="w-12 h-12 rounded-lg bg-yellow-500/20 flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6 text-yellow-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236v4.069m0 0a8.97 8.97 0 01-2.916.52m2.916-.52v4.069m0 0v2.613m0 0c-1.027-.083-2.05-.213-3.062-.38m3.062.38v-2.613m0 0h2.613m-2.613 0a8.97 8.97 0 01-2.916-.52m2.916.52v-2.613m0 0h-2.613m2.613 0a8.97 8.97 0 012.916-.52m-2.916.52v4.069m0 0h2.613m-2.613 0a8.97 8.97 0 00-2.916.52m2.916-.52v-2.613m0 0h-2.613m2.613 0a8.97 8.97 0 012.916-.52m-2.916.52v-2.613m0 0h-2.613m2.613 0a8.97 8.97 0 012.916-.52m-2.916.52v-2.613m0 0h-2.613m2.613 0a8.97 8.97 0 00-2.916-.52m2.916-.52v-4.069m0 0h-2.613m2.613 0a8.97 8.97 0 012.916-.52m-2.916-.52v4.069m0 0h-2.613" />
              </svg>
            </div>
          </div>
          <div class="flex-1">
            <h2 class="text-2xl font-bold mb-2 text-yellow-400">Scores</h2>
            <p class="text-neutral-300 mb-4">
              Les scores, c'est simple : chaque bonne réponse te rapporte des points. Plus tu joues, plus tu accumules de points.
            </p>
            <div class="space-y-2 text-sm text-neutral-300">
              <p><strong>En bref :</strong></p>
              <ul class="list-disc list-inside space-y-1 ml-4">
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
      <div class="bg-gradient-to-br from-blue-900/20 to-blue-800/20 rounded-2xl p-6 border border-blue-700/50">
        <div class="flex items-start gap-4 mb-4">
          <div class="flex-shrink-0">
            <div class="w-12 h-12 rounded-lg bg-blue-500/20 flex items-center justify-center">
              <LevelBadge :level="25" size="md" variant="compact" />
            </div>
          </div>
          <div class="flex-1">
            <h2 class="text-2xl font-bold mb-2 text-blue-400">Levels</h2>
            <p class="text-neutral-300 mb-4">
              Les levels récompensent ton engagement global sur Blinest. Tu gagnes de l'XP (Experience Points) en participant activement à la communauté.
            </p>
            <div class="space-y-2 text-sm text-neutral-300">
              <p><strong>En bref :</strong></p>
              <ul class="list-disc list-inside space-y-1 ml-4">
                <li>Ça compte uniquement dans les <strong>rooms publiques</strong></li>
                <li>C'est basé sur plusieurs trucs : scores, ancienneté, rooms créées, playlists, etc.</li>
                <li>Ça ne peut <strong>jamais baisser</strong> - progression continue</li>
                <li>C'est affiché dans les <strong>profils</strong> pour montrer ton expérience</li>
                <li>Chaque level demande plus d'XP que le précédent</li>
              </ul>
            </div>
            <div class="mt-4">
              <Link 
                :href="route('docs.level')" 
                class="inline-flex items-center gap-2 text-blue-400 hover:text-blue-300 transition-colors text-sm font-semibold"
              >
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
      <div class="bg-gradient-to-br from-purple-900/20 to-purple-800/20 rounded-2xl p-6 border border-purple-700/50">
        <div class="flex items-start gap-4 mb-4">
          <div class="flex-shrink-0">
            <div class="w-12 h-12 rounded-lg bg-purple-500/20 flex items-center justify-center">
              <EloBadge :elo="1850" size="md" variant="compact" />
            </div>
          </div>
          <div class="flex-1">
            <h2 class="text-2xl font-bold mb-2 text-purple-400">ELO</h2>
            <p class="text-neutral-300 mb-4">
              L'ELO, c'est un système de classement compétitif qui évalue ton niveau réel. Contrairement aux scores et levels, l'ELO peut monter ou descendre selon tes performances.
            </p>
            <div class="space-y-2 text-sm text-neutral-300">
              <p><strong>En bref :</strong></p>
              <ul class="list-disc list-inside space-y-1 ml-4">
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
              <Link 
                :href="route('docs.elo')" 
                class="inline-flex items-center gap-2 text-purple-400 hover:text-purple-300 transition-colors text-sm font-semibold"
              >
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
      <div class="bg-gradient-to-br from-green-900/20 to-green-800/20 rounded-2xl p-6 border border-green-700/50">
        <h2 class="text-2xl font-bold mb-4 text-green-400">Où les voir ?</h2>
        <div class="space-y-4">
          <div class="bg-slate-800/50 rounded-lg p-4">
            <h3 class="font-semibold text-white mb-2">Pendant une partie</h3>
            <p class="text-neutral-300 text-sm">
              Tu verras les <strong>scores</strong> (pour voir qui mène) et l'<strong>ELO</strong> (pour évaluer la compétence des joueurs). 
              Le level n'est pas affiché pendant les parties car il représente l'expérience globale, pas la compétence dans cette partie spécifique.
            </p>
          </div>
          <div class="bg-slate-800/50 rounded-lg p-4">
            <h3 class="font-semibold text-white mb-2">Dans un profil</h3>
            <p class="text-neutral-300 text-sm">
              Tu verras les <strong>scores totaux</strong>, le <strong>level</strong> (expérience) et l'<strong>ELO</strong> (compétence). 
              Ça donne une vue complète de l'activité et du niveau du joueur.
            </p>
          </div>
          <div class="bg-slate-800/50 rounded-lg p-4">
            <h3 class="font-semibold text-white mb-2">Dans les classements</h3>
            <p class="text-neutral-300 text-sm">
              Les classements peuvent être triés par <strong>score</strong> (activité), <strong>level</strong> (expérience) ou <strong>ELO</strong> (compétence). 
              Chaque classement montre un aspect différent de la progression.
            </p>
          </div>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 border border-slate-700">
        <h2 class="text-2xl font-bold mb-4">Pour aller plus loin</h2>
        <div class="grid gap-4 md:grid-cols-2">
          <Link 
            :href="route('docs.level')" 
            class="flex items-center gap-4 p-4 rounded-lg bg-slate-800/50 hover:bg-slate-800/70 border border-slate-700 transition-all group"
          >
            <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-blue-500/20 flex items-center justify-center group-hover:bg-blue-500/30 transition-colors">
              <LevelBadge :level="10" size="sm" variant="compact" />
            </div>
            <div class="flex-1">
              <h3 class="font-semibold text-white group-hover:text-blue-400 transition-colors">Système de Levels</h3>
              <p class="text-xs text-neutral-400 mt-1">Comment gagner de l'XP et progresser</p>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5 text-neutral-400 group-hover:text-blue-400 transition-colors">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
          </Link>

          <Link 
            :href="route('docs.elo')" 
            class="flex items-center gap-4 p-4 rounded-lg bg-slate-800/50 hover:bg-slate-800/70 border border-slate-700 transition-all group"
          >
            <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-purple-500/20 flex items-center justify-center group-hover:bg-purple-500/30 transition-colors">
              <EloBadge :elo="2000" size="sm" variant="compact" />
            </div>
            <div class="flex-1">
              <h3 class="font-semibold text-white group-hover:text-purple-400 transition-colors">Système ELO</h3>
              <p class="text-xs text-neutral-400 mt-1">Tout savoir sur le classement compétitif</p>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5 text-neutral-400 group-hover:text-purple-400 transition-colors">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
          </Link>
        </div>
      </div>

      <!-- Summary -->
      <div class="bg-gradient-to-br from-indigo-900/20 to-indigo-800/20 rounded-2xl p-6 border border-indigo-700/50">
        <h2 class="text-2xl font-bold mb-4 text-indigo-400">En bref</h2>
        <div class="space-y-3 text-neutral-300">
          <p>
            <strong>Scores</strong> = Ton activité totale, toujours croissante, visible partout
          </p>
          <p>
            <strong>Levels</strong> = Ton expérience et engagement, toujours croissant, visible dans les profils
          </p>
          <p>
            <strong>ELO</strong> = Ta compétence réelle, peut varier, visible pendant les parties compétitives
          </p>
          <p class="mt-4 text-white font-semibold">
            Ces trois systèmes se complètent pour offrir une progression plus riche et équilibrée, 
            qui récompense à la fois l'activité, l'engagement et la compétence.
          </p>
        </div>
      </div>

      <!-- Back link -->
      <div class="text-center pt-4">
        <Link :href="route('home')" class="text-blue-400 hover:text-blue-300 transition-colors">
          ← Retour à l'accueil
        </Link>
      </div>
    </div>
  </AppLayout>
</template>

