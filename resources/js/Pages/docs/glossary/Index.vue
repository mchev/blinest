<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import GlossaryStructuredData from '@/Components/GlossaryStructuredData.vue'

const definitions = [
  {
    term: 'Blind test',
    definition: 'Un blind test est un jeu musical où les participants doivent identifier une chanson en écoutant uniquement un extrait audio, sans voir le titre, l\'artiste ou la pochette d\'album.',
    context: 'Sur Blinest, les blind tests sont organisés en rooms (salles de jeu) multijoueurs où plusieurs joueurs s\'affrontent en temps réel.',
    related: ['Quiz musical', 'Room', 'Track'],
  },
  {
    term: 'Quiz musical',
    definition: 'Un quiz musical est un jeu de questions-réponses basé sur la musique. Sur Blinest, les quiz musicaux prennent la forme de blind tests multijoueurs en ligne.',
    context: 'Les quiz musicaux sur Blinest sont organisés par catégories (Années 2000, Disney, Chanson française, Rock, Pop, etc.) et permettent aux joueurs de tester leurs connaissances musicales.',
    related: ['Blind test', 'Room', 'Category'],
  },
  {
    term: 'Room',
    definition: 'Une room (salle de jeu) est un espace de jeu multijoueur sur Blinest où les participants jouent ensemble à un blind test.',
    context: 'Les rooms peuvent être publiques (officielles, gérées par les modérateurs de Blinest) ou privées (créées par les joueurs, avec ou sans mot de passe). Chaque room contient des playlists de musiques et organise des rounds de jeu.',
    related: ['Playlist', 'Round', 'Public room', 'Private room'],
  },
  {
    term: 'Round',
    definition: 'Un round est une manche de jeu dans une room. Chaque round contient plusieurs tracks (morceaux de musique) que les joueurs doivent identifier.',
    context: 'Sur Blinest, un round se termine lorsque tous les tracks ont été joués. Les scores sont calculés en fonction des bonnes réponses et du temps de réponse.',
    related: ['Track', 'Score', 'ELO'],
  },
  {
    term: 'Track',
    definition: 'Un track est un morceau de musique joué pendant un round. Les joueurs doivent identifier le titre et/ou l\'artiste du track.',
    context: 'Les tracks sont organisés en playlists et peuvent provenir de différents fournisseurs musicaux (Deezer, YouTube, Apple Music, etc.).',
    related: ['Playlist', 'Music provider'],
  },
  {
    term: 'Score',
    definition: 'Le score est le nombre total de points accumulés par un joueur sur Blinest. Chaque bonne réponse rapporte des points.',
    context: 'Le score est toujours croissant et mesure l\'activité totale d\'un joueur. Il est affiché pendant les parties pour voir qui mène.',
    related: ['Level', 'ELO', 'Total score'],
  },
  {
    term: 'Level',
    definition: 'Le level (niveau) est un système de progression basé sur l\'XP (Experience Points) qui récompense l\'engagement global d\'un joueur sur Blinest.',
    context: 'Le level augmente en fonction de plusieurs facteurs : scores dans les rooms publiques, ancienneté, rooms créées, playlists créées, appartenance à une équipe. Le level est toujours croissant.',
    related: ['XP', 'Score', 'ELO'],
  },
  {
    term: 'ELO',
    definition: 'L\'ELO est un système de classement compétitif qui évalue la compétence réelle d\'un joueur. Contrairement au score et au level, l\'ELO peut augmenter ou diminuer selon les performances.',
    context: 'L\'ELO sur Blinest fonctionne uniquement dans les rooms publiques avec 3 joueurs ou plus. Il prend en compte le niveau des adversaires : battre un joueur avec un ELO élevé rapporte plus de points.',
    related: ['Score', 'Level', 'Competitive ranking'],
  },
  {
    term: 'XP (Experience Points)',
    definition: 'Les XP (Experience Points) sont les points d\'expérience qui déterminent le level d\'un joueur. Ils sont gagnés par diverses activités sur la plateforme.',
    context: 'Sur Blinest, les XP sont gagnés par : 1 point de score = 1 XP, 50 XP par mois d\'ancienneté (max 600 XP), 100 XP par room créée (max 1000 XP), 200 XP pour l\'appartenance à une équipe, 20 XP par playlist créée (max 2000 XP).',
    related: ['Level', 'Score'],
  },
  {
    term: 'Playlist',
    definition: 'Une playlist est une collection de tracks organisés par thème, genre ou catégorie. Les playlists sont utilisées pour créer des rooms de jeu.',
    context: 'Sur Blinest, les utilisateurs peuvent créer leurs propres playlists ou utiliser des playlists publiques. Les playlists peuvent être importées depuis Deezer ou créées manuellement.',
    related: ['Track', 'Room', 'Music provider'],
  },
  {
    term: 'Public room',
    definition: 'Une room publique est une room officielle gérée par les modérateurs de Blinest. Les scores, levels et ELO comptent uniquement dans les rooms publiques.',
    context: 'Les rooms publiques sont créées et maintenues par l\'équipe Blinest. Elles permettent de jouer avec des joueurs du monde entier et de progresser dans les classements globaux.',
    related: ['Private room', 'Room', 'Score'],
  },
  {
    term: 'Private room',
    definition: 'Une room privée est une room de la communauté créée et gérée par les joueurs. Elle peut être protégée par un mot de passe ou accessible sans mot de passe.',
    context: 'Les rooms privées permettent aux joueurs de créer leurs propres espaces de jeu. Les scores dans les rooms privées ne comptent pas pour le level ou l\'ELO, mais comptent pour le score total.',
    related: ['Public room', 'Room'],
  },
  {
    term: 'Category',
    definition: 'Une catégorie est un classement thématique des rooms et playlists (ex: Années 2000, Disney, Chanson française, Rock, Pop).',
    context: 'Les catégories permettent aux joueurs de trouver facilement des rooms correspondant à leurs goûts musicaux.',
    related: ['Room', 'Playlist'],
  },
  {
    term: 'Music provider',
    definition: 'Un music provider (fournisseur musical) est un service qui fournit les extraits audio pour les tracks (Deezer, YouTube, Apple Music, etc.).',
    context: 'Blinest supporte plusieurs fournisseurs musicaux pour permettre une grande variété de contenu musical.',
    related: ['Track', 'Playlist'],
  },
]
</script>

<template>
  <GlossaryStructuredData />
  <Head>
    <title>Glossaire - Définitions des termes Blinest | Blinest</title>
    <meta name="description" content="Définitions claires et précises des termes utilisés sur Blinest : blind test, quiz musical, room, round, track, score, level, ELO, XP, playlist et plus encore." />
    <meta name="keywords" content="glossaire blinest, définition blind test, quiz musical définition, room définition, ELO définition, score définition, level définition" />
    <link rel="canonical" href="https://blinest.com/docs/glossary" />
  </Head>
  <AppLayout>
    <div class="max-w-5xl mx-auto px-4 py-8 space-y-8">
      <!-- Header -->
      <div class="text-center space-y-4">
        <h1 class="text-4xl font-bold text-white">Glossaire Blinest</h1>
        <p class="text-lg text-neutral-400">
          Définitions claires et précises des termes utilisés sur la plateforme
        </p>
      </div>

      <!-- Introduction -->
      <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 border border-slate-700">
        <div class="flex items-start justify-between mb-4">
          <h2 class="text-2xl font-bold text-white">À propos de ce glossaire</h2>
          <span class="text-xs text-neutral-500 bg-slate-800/50 px-3 py-1 rounded">
            Mis à jour le {{ new Date().toLocaleDateString('fr-FR', { year: 'numeric', month: 'long', day: 'numeric' }) }}
          </span>
        </div>
        <p class="text-neutral-300 mb-4">
          Ce glossaire définit de manière claire et assertive tous les termes techniques et concepts utilisés sur Blinest. 
          Chaque définition est structurée pour être facilement compréhensible et extractible par les moteurs de recherche génératifs.
        </p>
        <p class="text-neutral-300">
          <strong>Blinest est une plateforme de quiz musicaux multijoueurs en ligne</strong> où les joueurs s'affrontent 
          dans des blind tests organisés en rooms (salles de jeu). La plateforme utilise trois systèmes de progression 
          distincts : les scores (activité), les levels (engagement) et l'ELO (compétence).
        </p>
      </div>

      <!-- Definitions List -->
      <div class="space-y-6">
        <div
          v-for="(def, index) in definitions"
          :key="index"
          class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 border border-slate-700"
        >
          <h2 class="text-2xl font-bold mb-3 text-white">{{ def.term }}</h2>
          <p class="text-neutral-300 mb-3 text-lg">
            {{ def.definition }}
          </p>
          <div v-if="def.context" class="bg-slate-800/50 rounded-lg p-4 mb-3">
            <p class="text-neutral-300 text-sm">
              <strong class="text-white">Contexte Blinest :</strong> {{ def.context }}
            </p>
          </div>
          <div v-if="def.related && def.related.length" class="flex flex-wrap gap-2">
            <span class="text-xs text-neutral-400">Voir aussi :</span>
            <span
              v-for="(term, termIndex) in def.related"
              :key="termIndex"
              class="text-xs bg-slate-700/50 text-neutral-300 px-2 py-1 rounded"
            >
              {{ term }}
            </span>
          </div>
        </div>
      </div>

      <!-- Quick Facts -->
      <div class="bg-gradient-to-br from-indigo-900/20 to-indigo-800/20 rounded-2xl p-6 border border-indigo-700/50">
        <h2 class="text-2xl font-bold mb-4 text-indigo-400">Facts clés sur Blinest</h2>
        <ul class="space-y-3 text-neutral-300">
          <li class="flex items-start gap-3">
            <span class="text-indigo-400 font-bold">•</span>
            <span><strong>Blinest est gratuit</strong> : Tous les quiz musicaux sont accessibles sans abonnement ni paiement.</span>
          </li>
          <li class="flex items-start gap-3">
            <span class="text-indigo-400 font-bold">•</span>
            <span><strong>Multijoueur en temps réel</strong> : Les parties se déroulent en direct avec plusieurs joueurs simultanés.</span>
          </li>
          <li class="flex items-start gap-3">
            <span class="text-indigo-400 font-bold">•</span>
            <span><strong>Trois systèmes de progression</strong> : Score (activité), Level (engagement), ELO (compétence).</span>
          </li>
          <li class="flex items-start gap-3">
            <span class="text-indigo-400 font-bold">•</span>
            <span><strong>Rooms publiques et privées</strong> : Les rooms publiques sont officielles et gérées par Blinest. Les rooms privées sont créées par les joueurs et peuvent être protégées par un mot de passe ou non.</span>
          </li>
          <li class="flex items-start gap-3">
            <span class="text-indigo-400 font-bold">•</span>
            <span><strong>Plusieurs fournisseurs musicaux</strong> : Deezer, YouTube, Apple Music, Audius et plus encore.</span>
          </li>
        </ul>
      </div>

      <!-- Back link -->
      <div class="text-center pt-4">
        <Link :href="route('docs.index')" class="text-blue-400 hover:text-blue-300 transition-colors">
          ← Retour à la documentation
        </Link>
      </div>
    </div>
  </AppLayout>
</template>

