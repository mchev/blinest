<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
const steps = [
  {
    number: 1,
    title: 'Créer un compte ou se connecter',
    description: 'Créez un compte gratuit sur Blinest ou connectez-vous avec votre compte existant.',
    details: [
      'Vous pouvez utiliser Google, Facebook, Discord ou Deezer pour vous connecter rapidement',
      'Aucune carte bancaire requise - Blinest est entièrement gratuit',
      'La création de compte prend moins d\'une minute',
    ],
  },
  {
    number: 2,
    title: 'Choisir une room',
    description: 'Parcourez les rooms disponibles sur la page d\'accueil.',
    details: [
      'Les rooms sont organisées par catégories : Années 2000, Disney, Chanson française, Rock, Pop, etc.',
      'Chaque room affiche le nombre de joueurs actifs et le nombre de rounds joués',
      'Cliquez sur une room pour voir ses détails et la rejoindre',
    ],
  },
  {
    number: 3,
    title: 'Rejoindre la room',
    description: 'Entrez dans la room et attendez que la partie commence.',
    details: [
      'Si la room est privée, entrez le mot de passe fourni par le créateur',
      'Les rooms publiques sont accessibles à tous sans mot de passe',
      'Une fois dans la room, vous verrez la liste des joueurs et les paramètres de la partie',
    ],
  },
  {
    number: 4,
    title: 'Écouter et identifier',
    description: 'Un extrait audio sera joué - écoutez attentivement pour identifier la chanson.',
    details: [
      'La durée par défaut est de 30 secondes par track',
      'Vous pouvez voir le temps restant pendant l\'écoute',
      'Concentrez-vous sur la mélodie, les paroles et les instruments pour identifier la chanson',
    ],
  },
  {
    number: 5,
    title: 'Saisir votre réponse',
    description: 'Tapez votre réponse dans le champ de saisie.',
    details: [
      'Vous pouvez indiquer le titre, l\'artiste, ou les deux',
      'Plus votre réponse est précise et rapide, plus vous gagnez de points',
      'Les réponses partielles peuvent aussi rapporter des points selon la précision',
    ],
  },
  {
    number: 6,
    title: 'Voir les résultats',
    description: 'Après chaque track, les bonnes réponses sont affichées.',
    details: [
      'Vous verrez votre score et votre position dans le classement en temps réel',
      'Les autres joueurs qui ont trouvé la bonne réponse sont également affichés',
      'Votre progression est mise à jour automatiquement',
    ],
  },
  {
    number: 7,
    title: 'Continuer le round',
    description: 'Le round continue avec plusieurs tracks jusqu\'à la fin.',
    details: [
      'Un round contient généralement plusieurs tracks (configurable par le créateur de la room)',
      'À la fin du round, un podium affiche les trois meilleurs joueurs',
      'Votre score total, votre level et votre ELO sont mis à jour automatiquement',
    ],
  },
]
</script>

<template>
<AppLayout>
    <div class="max-w-5xl mx-auto px-4 py-8 space-y-8">
      <!-- Header -->
      <div class="text-center space-y-4">
        <h1 class="text-4xl font-bold text-white">Comment jouer à un blind test sur Blinest</h1>
        <p class="text-lg text-neutral-400">
          Guide étape par étape pour débuter sur la plateforme de quiz musicaux multijoueurs
        </p>
        <p class="text-sm text-neutral-500">
          Dernière mise à jour : {{ new Date().toLocaleDateString('fr-FR', { year: 'numeric', month: 'long', day: 'numeric' }) }}
        </p>
      </div>

      <!-- Introduction -->
      <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 border border-slate-700">
        <h2 class="text-2xl font-bold mb-4 text-white">À propos de ce guide</h2>
        <p class="text-neutral-300 mb-4">
          Ce guide explique comment jouer à un blind test sur Blinest, étape par étape. 
          <strong>Blinest est une plateforme gratuite de quiz musicaux multijoueurs en ligne</strong> où vous pouvez tester vos connaissances musicales en temps réel avec d'autres joueurs.
        </p>
        <div class="bg-slate-800/50 rounded-lg p-4">
          <p class="text-neutral-300 text-sm">
            <strong class="text-white">Temps estimé :</strong> 5 minutes pour compléter votre première partie<br>
            <strong class="text-white">Coût :</strong> Gratuit - Aucun abonnement requis<br>
            <strong class="text-white">Prérequis :</strong> Compte Blinest, connexion Internet, haut-parleurs ou casque audio
          </p>
        </div>
      </div>

      <!-- Steps -->
      <div class="space-y-6">
        <div
          v-for="step in steps"
          :key="step.number"
          class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 border border-slate-700"
        >
          <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-blue-500/20 flex items-center justify-center border-2 border-blue-500/50">
              <span class="text-xl font-bold text-blue-400">{{ step.number }}</span>
            </div>
            <div class="flex-1">
              <h2 class="text-2xl font-bold mb-3 text-white">{{ step.title }}</h2>
              <p class="text-neutral-300 mb-4 text-lg">
                {{ step.description }}
              </p>
              <ul v-if="step.details && step.details.length" class="space-y-2">
                <li
                  v-for="(detail, index) in step.details"
                  :key="index"
                  class="flex items-start gap-2 text-neutral-300 text-sm"
                >
                  <span class="text-blue-400 mt-1">•</span>
                  <span>{{ detail }}</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Tips Section -->
      <div class="bg-gradient-to-br from-green-900/20 to-green-800/20 rounded-2xl p-6 border border-green-700/50">
        <h2 class="text-2xl font-bold mb-4 text-green-400">Conseils pour améliorer votre score</h2>
        <ul class="space-y-3 text-neutral-300">
          <li class="flex items-start gap-3">
            <span class="text-green-400 font-bold">✓</span>
            <span><strong>Répondez rapidement</strong> : Plus vous répondez vite, plus vous gagnez de points. La vitesse est un facteur clé dans le calcul du score.</span>
          </li>
          <li class="flex items-start gap-3">
            <span class="text-green-400 font-bold">✓</span>
            <span><strong>Soyez précis</strong> : Indiquez à la fois le titre et l'artiste pour maximiser vos points. Les réponses partielles rapportent moins de points.</span>
          </li>
          <li class="flex items-start gap-3">
            <span class="text-green-400 font-bold">✓</span>
            <span><strong>Jouez régulièrement</strong> : Plus vous jouez, plus vous progressez dans les levels et l'ELO. Les rooms publiques comptent pour votre progression.</span>
          </li>
          <li class="flex items-start gap-3">
            <span class="text-green-400 font-bold">✓</span>
            <span><strong>Créez vos propres rooms</strong> : Créer des rooms et des playlists vous rapporte de l'XP et améliore votre level.</span>
          </li>
        </ul>
      </div>

      <!-- Related Links -->
      <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 border border-slate-700">
        <h2 class="text-2xl font-bold mb-4">Pour aller plus loin</h2>
        <div class="grid gap-4 md:grid-cols-2">
          <Link
            :href="route('docs.index')"
            class="flex items-center gap-4 p-4 rounded-lg bg-slate-800/50 hover:bg-slate-800/70 border border-slate-700 transition-all group"
          >
            <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-blue-500/20 flex items-center justify-center group-hover:bg-blue-500/30 transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6 text-blue-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
              </svg>
            </div>
            <div class="flex-1">
              <h3 class="font-semibold text-white group-hover:text-blue-400 transition-colors">Documentation complète</h3>
              <p class="text-xs text-neutral-400 mt-1">Tout savoir sur les systèmes de progression</p>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5 text-neutral-400 group-hover:text-blue-400 transition-colors">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
          </Link>

          <Link
            :href="route('docs.glossary')"
            class="flex items-center gap-4 p-4 rounded-lg bg-slate-800/50 hover:bg-slate-800/70 border border-slate-700 transition-all group"
          >
            <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-green-500/20 flex items-center justify-center group-hover:bg-green-500/30 transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6 text-green-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
              </svg>
            </div>
            <div class="flex-1">
              <h3 class="font-semibold text-white group-hover:text-green-400 transition-colors">Glossaire</h3>
              <p class="text-xs text-neutral-400 mt-1">Définitions des termes techniques</p>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5 text-neutral-400 group-hover:text-green-400 transition-colors">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
          </Link>
        </div>
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

