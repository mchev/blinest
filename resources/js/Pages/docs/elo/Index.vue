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
</script>

<template>
  <AppLayout>
    <div class="mx-auto max-w-4xl space-y-8 px-4 py-8">
      <!-- Header -->
      <div class="space-y-4 text-center">
        <h1 class="text-4xl font-bold">Système ELO - Documentation Complète</h1>
        <p class="text-lg text-neutral-400">Comprenez comment fonctionne le système ELO et comment votre classement évolue.</p>
      </div>

      <!-- Table of Contents -->
      <div class="rounded-2xl border border-blue-700/50 bg-gradient-to-br from-blue-900/20 to-blue-800/20 p-6">
        <h2 class="mb-4 text-2xl font-bold text-blue-400">Table des matières</h2>
        <ul class="space-y-2 text-neutral-300">
          <li><a href="#introduction" class="text-blue-400 hover:text-blue-300">1. Introduction</a></li>
          <li><a href="#quest-ce-que-lelo" class="text-blue-400 hover:text-blue-300">2. Qu'est-ce que l'ELO ?</a></li>
          <li><a href="#comment-fonctionne" class="text-blue-400 hover:text-blue-300">3. Comment fonctionne le système ELO ?</a></li>
          <li><a href="#rounds-placement" class="text-blue-400 hover:text-blue-300">4. Rounds de placement</a></li>
          <li><a href="#calcul-changements" class="text-blue-400 hover:text-blue-300">5. Calcul des changements d'ELO</a></li>
          <li><a href="#k-factor" class="text-blue-400 hover:text-blue-300">6. K-factor variable</a></li>
          <li><a href="#conditions" class="text-blue-400 hover:text-blue-300">7. Conditions pour compter l'ELO</a></li>
          <li><a href="#tracks-jouees" class="text-blue-400 hover:text-blue-300">8. Tracks jouées et calcul proportionnel</a></li>
          <li><a href="#metriques" class="text-blue-400 hover:text-blue-300">9. Métriques de performance</a></li>
          <li><a href="#win-streak" class="text-blue-400 hover:text-blue-300">10. Win Streak</a></li>
          <li><a href="#protection" class="text-blue-400 hover:text-blue-300">11. Protection contre l'inflation/déflation</a></li>
          <li><a href="#faq" class="text-blue-400 hover:text-blue-300">12. FAQ</a></li>
        </ul>
      </div>

      <!-- Introduction -->
      <section id="introduction" class="rounded-2xl border border-slate-700 bg-gradient-to-br from-slate-800 to-slate-900 p-6">
        <h2 class="mb-4 text-2xl font-bold">1. Introduction</h2>
        <p class="mb-4 text-neutral-300">Le système ELO est un système de classement qui évalue votre niveau de compétence en fonction de vos performances dans les parties. Plus votre ELO est élevé, plus vous êtes considéré comme un joueur expérimenté.</p>
        <div class="rounded border-l-4 border-blue-500 bg-blue-900/20 p-4">
          <p class="font-semibold text-white"><strong>Votre ELO initial :</strong> Tous les nouveaux joueurs commencent avec un ELO de <strong>1500 points</strong>.</p>
        </div>
      </section>

      <!-- Qu'est-ce que l'ELO -->
      <section id="quest-ce-que-lelo" class="rounded-2xl border border-slate-700 bg-gradient-to-br from-slate-800 to-slate-900 p-6">
        <h2 class="mb-4 text-2xl font-bold">2. Qu'est-ce que l'ELO ?</h2>
        <p class="mb-4 text-neutral-300">L'ELO est un nombre qui représente votre niveau de compétence. Il évolue après chaque partie selon :</p>
        <ul class="mb-4 list-inside list-disc space-y-2 text-neutral-300">
          <li>Votre position finale dans le round</li>
          <li>Le niveau ELO de vos adversaires</li>
          <li>Votre propre niveau ELO actuel</li>
        </ul>
        <p class="font-semibold text-white">
          <strong>Plus votre ELO est élevé, plus vous êtes considéré comme un bon joueur.</strong>
        </p>
      </section>

      <!-- Comment fonctionne -->
      <section id="comment-fonctionne" class="rounded-2xl border border-slate-700 bg-gradient-to-br from-slate-800 to-slate-900 p-6">
        <h2 class="mb-4 text-2xl font-bold">3. Comment fonctionne le système ELO ?</h2>

        <h3 class="mb-3 mt-6 text-xl font-semibold">Principe de base</h3>
        <p class="mb-4 text-neutral-300">À la fin de chaque round, votre ELO peut augmenter ou diminuer selon votre performance. Le système compare :</p>
        <ul class="mb-4 list-inside list-disc space-y-2 text-neutral-300">
          <li><strong>Votre score réel</strong> : basé sur votre position finale (1er, 2ème, 3ème, etc.)</li>
          <li><strong>Votre score attendu</strong> : basé sur votre ELO et celui de vos adversaires</li>
        </ul>

        <div class="mb-4 rounded border-l-4 border-green-500 bg-green-900/20 p-4">
          <p class="mb-2 font-semibold text-white"><strong>Si vous faites mieux que prévu</strong> → Votre ELO augmente</p>
          <p class="font-semibold text-white"><strong>Si vous faites moins bien que prévu</strong> → Votre ELO diminue</p>
        </div>

        <h3 class="mb-3 mt-6 text-xl font-semibold">Exemple concret</h3>
        <p class="mb-2 text-neutral-300">Imaginez que vous avez un ELO de 1500 et que vous jouez contre 3 adversaires avec des ELO de 1600, 1500 et 1400.</p>
        <ul class="list-inside list-disc space-y-2 text-neutral-300">
          <li><strong>Score attendu</strong> : Le système s'attend à ce que vous finissiez environ 2ème ou 3ème</li>
          <li><strong>Si vous finissez 1er</strong> : Vous avez fait mieux que prévu → Votre ELO augmente</li>
          <li><strong>Si vous finissez 4ème</strong> : Vous avez fait moins bien que prévu → Votre ELO diminue</li>
        </ul>
      </section>

      <!-- Rounds de placement -->
      <section id="rounds-placement" class="rounded-2xl border border-slate-700 bg-gradient-to-br from-slate-800 to-slate-900 p-6">
        <h2 class="mb-4 text-2xl font-bold">4. Rounds de placement</h2>

        <h3 class="mb-3 mt-6 text-xl font-semibold">Qu'est-ce que le placement ?</h3>
        <p class="mb-4 text-neutral-300">Les <strong>10 premiers rounds</strong> où votre ELO est compté sont appelés "rounds de placement". Pendant cette période :</p>
        <ul class="mb-4 list-inside list-disc space-y-2 text-neutral-300">
          <li>Votre ELO change <strong>plus rapidement</strong> pour s'ajuster à votre niveau réel</li>
          <li>Le système utilise un <strong>K-factor plus élevé</strong> (de 50 à 32) pour vous placer rapidement</li>
        </ul>

        <h3 class="mb-3 mt-6 text-xl font-semibold">Pourquoi un placement ?</h3>
        <p class="mb-4 text-neutral-300">Le placement permet au système de déterminer rapidement votre niveau réel. Après 10 rounds, votre ELO devrait être proche de votre niveau réel.</p>

        <div class="rounded border-l-4 border-amber-500 bg-amber-900/20 p-4">
          <p class="font-semibold text-white"><strong>Important :</strong> Seuls les rounds dans des <strong>rooms publiques avec au moins 3 joueurs</strong> comptent pour le placement et l'ELO.</p>
        </div>
      </section>

      <!-- Calcul des changements -->
      <section id="calcul-changements" class="rounded-2xl border border-slate-700 bg-gradient-to-br from-slate-800 to-slate-900 p-6">
        <h2 class="mb-4 text-2xl font-bold">5. Calcul des changements d'ELO</h2>

        <h3 class="mb-3 mt-6 text-xl font-semibold">Formule utilisée</h3>
        <p class="mb-4 text-neutral-300">Le changement d'ELO se calcule ainsi :</p>
        <div class="mb-4 rounded border border-purple-500/30 bg-purple-900/20 p-4 text-center">
          <p class="font-mono text-lg font-semibold text-white">
            <strong>Changement ELO = K × (Score réel - Score attendu)</strong>
          </p>
        </div>

        <h3 class="mb-3 mt-6 text-xl font-semibold">Score réel</h3>
        <p class="mb-4 text-neutral-300">Votre score réel dépend de votre position finale :</p>
        <div class="mb-4 overflow-x-auto">
          <table class="w-full border-collapse text-sm">
            <thead>
              <tr class="border-b border-slate-700">
                <th class="bg-slate-800/50 px-4 py-3 text-left font-semibold">Position</th>
                <th class="bg-slate-800/50 px-4 py-3 text-left font-semibold">Score réel (exemple avec 4 joueurs)</th>
              </tr>
            </thead>
            <tbody>
              <tr class="border-b border-slate-700/50 hover:bg-slate-800/30">
                <td class="px-4 py-3">1er</td>
                <td class="px-4 py-3">1.0 (100%)</td>
              </tr>
              <tr class="border-b border-slate-700/50 hover:bg-slate-800/30">
                <td class="px-4 py-3">2ème</td>
                <td class="px-4 py-3">0.75 (75%)</td>
              </tr>
              <tr class="border-b border-slate-700/50 hover:bg-slate-800/30">
                <td class="px-4 py-3">3ème</td>
                <td class="px-4 py-3">0.5 (50%)</td>
              </tr>
              <tr class="border-b border-slate-700/50 hover:bg-slate-800/30">
                <td class="px-4 py-3">4ème</td>
                <td class="px-4 py-3">0.25 (25%)</td>
              </tr>
            </tbody>
          </table>
        </div>
        <p class="mb-4 text-neutral-300"><strong>Formule :</strong> <code class="rounded bg-purple-900/20 px-2 py-1">(Nombre de joueurs - Position + 1) / Nombre de joueurs</code></p>

        <h3 class="mb-3 mt-6 text-xl font-semibold">Score attendu</h3>
        <p class="text-neutral-300">Le score attendu est calculé en comparant votre ELO <strong>individuellement avec chaque adversaire</strong>, puis en faisant la moyenne. Plus vos adversaires ont un ELO élevé, plus votre score attendu est bas.</p>
        <p class="mt-2 text-neutral-300"><strong>Exemple :</strong> Si vous avez 1500 ELO et que tous vos adversaires ont 1600 ELO, votre score attendu sera d'environ 0.36 (36%), ce qui signifie que le système s'attend à ce que vous finissiez plutôt en bas du classement.</p>
      </section>

      <!-- K-factor -->
      <section id="k-factor" class="rounded-2xl border border-slate-700 bg-gradient-to-br from-slate-800 to-slate-900 p-6">
        <h2 class="mb-4 text-2xl font-bold">6. K-factor variable</h2>
        <p class="mb-4 text-neutral-300">Le K-factor détermine <strong>l'ampleur des changements</strong> d'ELO. Plus le K-factor est élevé, plus votre ELO peut changer rapidement.</p>

        <h3 class="mb-3 mt-6 text-xl font-semibold">Pendant le placement (10 premiers rounds)</h3>
        <ul class="mb-4 list-inside list-disc space-y-2 text-neutral-300">
          <li><strong>1er round</strong> : K = 50</li>
          <li><strong>2ème round</strong> : K = 48</li>
          <li><strong>3ème round</strong> : K = 45</li>
          <li>...</li>
          <li><strong>10ème round</strong> : K = 32</li>
        </ul>

        <h3 class="mb-3 mt-6 text-xl font-semibold">Après le placement</h3>
        <p class="mb-4 text-neutral-300">Le K-factor dépend de votre ELO actuel :</p>
        <div class="mb-4 overflow-x-auto">
          <table class="w-full border-collapse text-sm">
            <thead>
              <tr class="border-b border-slate-700">
                <th class="bg-slate-800/50 px-4 py-3 text-left font-semibold">Votre ELO</th>
                <th class="bg-slate-800/50 px-4 py-3 text-left font-semibold">K-factor</th>
                <th class="bg-slate-800/50 px-4 py-3 text-left font-semibold">Explication</th>
              </tr>
            </thead>
            <tbody>
              <tr class="border-b border-slate-700/50 hover:bg-slate-800/30">
                <td class="px-4 py-3">&lt; 1200</td>
                <td class="px-4 py-3">40</td>
                <td class="px-4 py-3">Débutants : changements plus importants pour progresser rapidement</td>
              </tr>
              <tr class="border-b border-slate-700/50 hover:bg-slate-800/30">
                <td class="px-4 py-3">1200-1600</td>
                <td class="px-4 py-3">32</td>
                <td class="px-4 py-3">Intermédiaires : changements standard</td>
              </tr>
              <tr class="border-b border-slate-700/50 hover:bg-slate-800/30">
                <td class="px-4 py-3">1600-2000</td>
                <td class="px-4 py-3">24</td>
                <td class="px-4 py-3">Avancés : changements plus petits pour plus de stabilité</td>
              </tr>
              <tr class="border-b border-slate-700/50 hover:bg-slate-800/30">
                <td class="px-4 py-3">&gt; 2000</td>
                <td class="px-4 py-3">16</td>
                <td class="px-4 py-3">Experts : changements très petits pour maintenir la précision</td>
              </tr>
            </tbody>
          </table>
        </div>
        <p class="text-neutral-300"><strong>Pourquoi cette différence ?</strong> Les joueurs expérimentés ont un niveau plus stable, donc leur ELO doit changer moins rapidement pour rester précis.</p>
      </section>

      <!-- Conditions -->
      <section id="conditions" class="rounded-2xl border border-slate-700 bg-gradient-to-br from-slate-800 to-slate-900 p-6">
        <h2 class="mb-4 text-2xl font-bold">7. Conditions pour compter l'ELO</h2>

        <h3 class="mb-3 mt-6 text-xl font-semibold">Votre ELO est mis à jour uniquement si :</h3>
        <ul class="mb-4 space-y-2 text-neutral-300">
          <li class="flex items-start gap-2">
            <span class="mt-1 text-green-400">✅</span>
            <span><strong>La room est publique</strong> (les rooms privées ne comptent pas)</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="mt-1 text-green-400">✅</span>
            <span><strong>Il y a au moins 3 joueurs</strong> dans le round</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="mt-1 text-green-400">✅</span>
            <span><strong>Le round est terminé</strong> (vous avez participé jusqu'à la fin)</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="mt-1 text-green-400">✅</span>
            <span><strong>Vous avez joué au moins 80% des tracks</strong> du round</span>
          </li>
        </ul>

        <h3 class="mb-3 mt-6 text-xl font-semibold">Votre ELO n'est PAS mis à jour si :</h3>
        <ul class="mb-4 space-y-2 text-neutral-300">
          <li class="flex items-start gap-2">
            <span class="mt-1 text-red-400">❌</span>
            <span>La room est privée</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="mt-1 text-red-400">❌</span>
            <span>Il y a moins de 3 joueurs</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="mt-1 text-red-400">❌</span>
            <span>Vous avez joué moins de 80% des tracks du round</span>
          </li>
        </ul>

        <div class="rounded border-l-4 border-blue-500 bg-blue-900/20 p-4">
          <p class="font-semibold text-white"><strong>Important :</strong> Même si votre ELO n'est pas mis à jour, vos statistiques (score total, métriques de performance) sont toujours enregistrées pour l'affichage.</p>
        </div>
      </section>

      <!-- Tracks jouées -->
      <section id="tracks-jouees" class="rounded-2xl border border-slate-700 bg-gradient-to-br from-slate-800 to-slate-900 p-6">
        <h2 class="mb-4 text-2xl font-bold">8. Tracks jouées et calcul proportionnel</h2>

        <h3 class="mb-3 mt-6 text-xl font-semibold">Comment ça fonctionne ?</h3>
        <p class="mb-4 text-neutral-300">Le système enregistre toutes les tracks que vous avez jouées dans un round, même si vous êtes arrivé en milieu de partie. Cela permet un calcul d'ELO plus juste et équitable.</p>

        <h3 class="mb-3 mt-6 text-xl font-semibold">Seuil minimum : 80% des tracks</h3>
        <p class="mb-4 text-neutral-300">Pour que votre ELO soit compté, vous devez avoir joué <strong>au moins 80% des tracks</strong> du round. Par exemple :</p>
        <ul class="mb-4 list-inside list-disc space-y-2 text-neutral-300">
          <li><strong>Round de 10 tracks</strong> : Vous devez avoir joué au moins 8 tracks</li>
          <li><strong>Round de 5 tracks</strong> : Vous devez avoir joué au moins 4 tracks</li>
        </ul>
        <div class="mb-4 rounded border-l-4 border-amber-500 bg-amber-900/20 p-4">
          <p class="font-semibold text-white"><strong>Pourquoi 80% ?</strong> Ce seuil garantit que vous avez participé à une partie significative du round, ce qui rend le calcul d'ELO plus fiable.</p>
        </div>

        <h3 class="mb-3 mt-6 text-xl font-semibold">Calcul proportionnel du changement d'ELO</h3>
        <p class="mb-4 text-neutral-300">Si vous avez joué au moins 80% des tracks, votre changement d'ELO est <strong>proportionnel au nombre de tracks jouées</strong> :</p>
        <div class="mb-4 rounded border border-purple-500/30 bg-purple-900/20 p-4">
          <p class="text-center font-mono text-lg font-semibold text-white">
            <strong>Changement ELO final = Changement ELO calculé × (Tracks jouées / Tracks totales)</strong>
          </p>
        </div>

        <h3 class="mb-3 mt-6 text-xl font-semibold">Exemples concrets</h3>
        <div class="mb-4 space-y-4">
          <div class="rounded-lg bg-slate-800/50 p-4">
            <p class="mb-2 font-semibold text-white">Exemple 1 : Round complet (10/10 tracks)</p>
            <p class="text-sm text-neutral-300">
              Vous avez joué toutes les 10 tracks d'un round. Le système calcule un changement d'ELO de +20 points.
              <br /><strong>Résultat :</strong> Vous recevez 100% du changement → <strong>+20 points</strong>
            </p>
          </div>
          <div class="rounded-lg bg-slate-800/50 p-4">
            <p class="mb-2 font-semibold text-white">Exemple 2 : Arrivée en milieu de partie (9/10 tracks)</p>
            <p class="text-sm text-neutral-300">
              Vous êtes arrivé après le début et avez joué 9 tracks sur 10. Le système calcule un changement d'ELO de +20 points.
              <br /><strong>Résultat :</strong> Vous recevez 90% du changement → <strong>+18 points</strong> (20 × 0.9)
            </p>
          </div>
          <div class="rounded-lg bg-slate-800/50 p-4">
            <p class="mb-2 font-semibold text-white">Exemple 3 : Arrivée tardive (8/10 tracks)</p>
            <p class="text-sm text-neutral-300">
              Vous êtes arrivé tard et avez joué 8 tracks sur 10 (exactement le minimum). Le système calcule un changement d'ELO de +20 points.
              <br /><strong>Résultat :</strong> Vous recevez 80% du changement → <strong>+16 points</strong> (20 × 0.8)
            </p>
          </div>
          <div class="rounded-lg bg-slate-800/50 p-4">
            <p class="mb-2 font-semibold text-white">Exemple 4 : Pas assez de tracks (7/10 tracks)</p>
            <p class="text-sm text-neutral-300">
              Vous avez joué seulement 7 tracks sur 10 (70% &lt; 80%).
              <br /><strong>Résultat :</strong> Votre ELO <strong>n'est pas compté</strong> pour ce round
            </p>
          </div>
        </div>

        <h3 class="mb-3 mt-6 text-xl font-semibold">Avantages de ce système</h3>
        <ul class="mb-4 list-inside list-disc space-y-2 text-neutral-300">
          <li><strong>Équité</strong> : Les joueurs arrivés en milieu de partie peuvent quand même faire évoluer leur ELO</li>
          <li><strong>Proportionnalité</strong> : Plus vous jouez de tracks, plus votre ELO peut évoluer</li>
          <li><strong>Fiabilité</strong> : Le seuil de 80% garantit que seuls les joueurs ayant participé significativement comptent</li>
          <li><strong>Flexibilité</strong> : Vous n'êtes pas pénalisé si vous rejoignez une partie en cours</li>
        </ul>

        <div class="rounded border-l-4 border-green-500 bg-green-900/20 p-4">
          <p class="font-semibold text-white"><strong>En résumé :</strong> Si vous rejoignez une partie en cours et jouez au moins 80% des tracks restantes, votre ELO évoluera proportionnellement au nombre de tracks que vous avez réellement jouées.</p>
        </div>
      </section>

      <!-- Métriques -->
      <section id="metriques" class="rounded-2xl border border-slate-700 bg-gradient-to-br from-slate-800 to-slate-900 p-6">
        <h2 class="mb-4 text-2xl font-bold">9. Métriques de performance</h2>
        <p class="mb-4 text-neutral-300">En plus de l'ELO, le système enregistre plusieurs métriques pour chaque round :</p>

        <h3 class="mb-3 mt-6 text-xl font-semibold">Temps moyen de réponse</h3>
        <p class="mb-4 text-neutral-300">Le temps moyen (en secondes) que vous avez mis pour répondre correctement aux questions.</p>

        <h3 class="mb-3 mt-6 text-xl font-semibold">Nombre de réponses rapides</h3>
        <p class="mb-4 text-neutral-300">Le nombre de réponses que vous avez données rapidement (dans les 18% de la durée de la piste). Ces réponses rapides vous donnent un bonus de score.</p>

        <h3 class="mb-3 mt-6 text-xl font-semibold">Nombre total de réponses</h3>
        <p class="mb-4 text-neutral-300">Le nombre total de bonnes réponses que vous avez données dans le round.</p>

        <div class="rounded border-l-4 border-blue-500 bg-blue-900/20 p-4">
          <p class="font-semibold text-white"><strong>Ces métriques sont toujours enregistrées</strong>, même si l'ELO n'est pas compté (rooms privées, moins de 3 joueurs).</p>
        </div>
      </section>

      <!-- Win Streak -->
      <section id="win-streak" class="rounded-2xl border border-slate-700 bg-gradient-to-br from-slate-800 to-slate-900 p-6">
        <h2 class="mb-4 text-2xl font-bold">10. Win Streak</h2>

        <h3 class="mb-3 mt-6 text-xl font-semibold">Qu'est-ce que le Win Streak ?</h3>
        <p class="mb-4 text-neutral-300">Le Win Streak (série de victoires) compte le nombre de <strong>victoires consécutives</strong> (finir 1er) dans une même room.</p>

        <h3 class="mb-3 mt-6 text-xl font-semibold">Comment ça fonctionne ?</h3>
        <ul class="mb-4 list-inside list-disc space-y-2 text-neutral-300">
          <li>Si vous finissez <strong>1er</strong> dans un round, votre Win Streak est calculé</li>
          <li>Le système vérifie vos résultats dans les rounds précédents de la même room</li>
          <li>Il compte combien de fois vous avez fini 1er consécutivement</li>
          <li>Dès que vous ne finissez pas 1er, le streak est réinitialisé à 0</li>
        </ul>

        <h3 class="mb-3 mt-6 text-xl font-semibold">Exemple</h3>
        <ul class="mb-4 list-inside list-disc space-y-2 text-neutral-300">
          <li><strong>Round 1</strong> : Vous finissez 1er → Win Streak = 1</li>
          <li><strong>Round 2</strong> : Vous finissez 1er → Win Streak = 2</li>
          <li><strong>Round 3</strong> : Vous finissez 2ème → Win Streak = 0 (réinitialisé)</li>
          <li><strong>Round 4</strong> : Vous finissez 1er → Win Streak = 1 (nouveau streak)</li>
        </ul>

        <div class="rounded border-l-4 border-amber-500 bg-amber-900/20 p-4">
          <p class="font-semibold text-white"><strong>Note :</strong> Le Win Streak est calculé <strong>par room</strong>. Si vous changez de room, le streak recommence.</p>
        </div>
      </section>

      <!-- Protection -->
      <section id="protection" class="rounded-2xl border border-slate-700 bg-gradient-to-br from-slate-800 to-slate-900 p-6">
        <h2 class="mb-4 text-2xl font-bold">11. Protection contre l'inflation/déflation</h2>

        <h3 class="mb-3 mt-6 text-xl font-semibold">Pourquoi cette protection ?</h3>
        <p class="mb-4 text-neutral-300">Sans protection, l'ELO moyen de tous les joueurs pourrait augmenter (inflation) ou diminuer (déflation) au fil du temps, ce qui rendrait le système moins précis.</p>

        <h3 class="mb-3 mt-6 text-xl font-semibold">Protection locale</h3>
        <p class="mb-2 text-neutral-300">Le système applique automatiquement des ajustements :</p>
        <ul class="mb-4 list-inside list-disc space-y-2 text-neutral-300">
          <li><strong>Si votre ELO > 2000</strong> et que vous gagnez des points : le gain est réduit de 10%</li>
          <li><strong>Si votre ELO &lt; 1000</strong> et que vous perdez des points : la perte est réduite de 10%</li>
        </ul>
        <p class="mb-4 text-neutral-300">Cela empêche les ELO extrêmes de devenir trop volatils.</p>

        <h3 class="mb-3 mt-6 text-xl font-semibold">Protection globale</h3>
        <p class="text-neutral-300">L'équipe peut exécuter périodiquement une commande pour ajuster tous les ELO et maintenir une moyenne stable autour de 1500. Ces ajustements sont rares et appliqués équitablement à tous les joueurs.</p>
      </section>

      <!-- FAQ -->
      <section id="faq" class="rounded-2xl border border-slate-700 bg-gradient-to-br from-slate-800 to-slate-900 p-6">
        <h2 class="mb-4 text-2xl font-bold">12. FAQ - Questions Fréquentes</h2>

        <div class="space-y-6">
          <div class="rounded-lg bg-slate-800/50 p-5">
            <h3 class="mb-3 text-lg font-semibold text-blue-400">Mon ELO a diminué alors que j'ai bien joué, pourquoi ?</h3>
            <p class="mb-2 text-neutral-300">Plusieurs raisons possibles :</p>
            <ol class="list-inside list-decimal space-y-1 text-neutral-300">
              <li><strong>Vos adversaires avaient un ELO plus bas que vous</strong> : Le système s'attendait à ce que vous finissiez mieux. Si vous finissez 2ème au lieu de 1er, vous pouvez perdre des points.</li>
              <li><strong>Vous êtes encore en placement</strong> : Les changements sont plus importants pendant les 10 premiers rounds.</li>
              <li><strong>Vous avez un ELO très élevé</strong> : Les joueurs avec un ELO > 2000 gagnent moins de points pour éviter l'inflation.</li>
            </ol>
          </div>

          <div class="rounded-lg bg-slate-800/50 p-5">
            <h3 class="mb-3 text-lg font-semibold text-blue-400">Pourquoi mon ELO ne change pas après un round ?</h3>
            <p class="mb-2 text-neutral-300">Votre ELO ne change que si :</p>
            <ul class="list-inside list-disc space-y-1 text-neutral-300">
              <li>La room est <strong>publique</strong></li>
              <li>Il y a <strong>au moins 3 joueurs</strong></li>
              <li>Le round est <strong>terminé</strong></li>
              <li>Vous avez joué <strong>au moins 80% des tracks</strong> du round</li>
            </ul>
            <p class="mt-2 text-neutral-300">Si une de ces conditions n'est pas remplie, votre ELO reste inchangé, mais vos statistiques sont quand même enregistrées.</p>
          </div>

          <div class="rounded-lg bg-slate-800/50 p-5">
            <h3 class="mb-3 text-lg font-semibold text-blue-400">Que se passe-t-il si j'arrive en milieu de partie ?</h3>
            <p class="mb-2 text-neutral-300">Si vous rejoignez une partie en cours, votre ELO peut quand même évoluer, mais de manière proportionnelle :</p>
            <ul class="mb-2 list-inside list-disc space-y-1 text-neutral-300">
              <li>Si vous jouez <strong>au moins 80% des tracks</strong> restantes, votre ELO sera compté</li>
              <li>Le changement d'ELO sera <strong>proportionnel</strong> au nombre de tracks que vous avez jouées</li>
              <li>Par exemple : si vous jouez 9 tracks sur 10, vous recevrez 90% du changement d'ELO calculé</li>
            </ul>
            <p class="mt-2 text-neutral-300"><strong>Exemple :</strong> Le système calcule un changement de +20 points, mais vous avez joué 9/10 tracks. Vous recevrez +18 points (20 × 0.9).</p>
          </div>

          <div class="rounded-lg bg-slate-800/50 p-5">
            <h3 class="mb-3 text-lg font-semibold text-blue-400">Combien de points puis-je gagner ou perdre ?</h3>
            <p class="mb-2 text-neutral-300">Cela dépend de plusieurs facteurs :</p>
            <ul class="mb-2 list-inside list-disc space-y-1 text-neutral-300">
              <li><strong>Votre ELO actuel</strong> (détermine le K-factor)</li>
              <li><strong>Le nombre de rounds joués</strong> (placement ou non)</li>
              <li><strong>Votre position finale</strong></li>
              <li><strong>L'ELO de vos adversaires</strong></li>
            </ul>
            <p class="text-neutral-300">En général :</p>
            <ul class="list-inside list-disc space-y-1 text-neutral-300">
              <li><strong>Pendant le placement</strong> : Changements de ±20 à ±50 points possibles</li>
              <li><strong>Après le placement</strong> : Changements de ±5 à ±40 points selon votre niveau</li>
            </ul>
          </div>

          <div class="rounded-lg bg-slate-800/50 p-5">
            <h3 class="mb-3 text-lg font-semibold text-blue-400">Mon ELO peut-il devenir négatif ?</h3>
            <p class="text-neutral-300">Non, l'ELO minimum est de <strong>100 points</strong>. Si votre ELO descend très bas, il sera maintenu à 100 minimum.</p>
          </div>

          <div class="rounded-lg bg-slate-800/50 p-5">
            <h3 class="mb-3 text-lg font-semibold text-blue-400">Que se passe-t-il si je joue dans une room privée ?</h3>
            <p class="mb-2 text-neutral-300">Votre ELO <strong>ne change pas</strong>, mais :</p>
            <ul class="list-inside list-disc space-y-1 text-neutral-300">
              <li>Votre score total est enregistré</li>
              <li>Vos métriques de performance sont enregistrées</li>
              <li>Vous pouvez voir votre classement dans cette room</li>
            </ul>
          </div>

          <div class="rounded-lg bg-slate-800/50 p-5">
            <h3 class="mb-3 text-lg font-semibold text-blue-400">Puis-je voir mon historique ELO ?</h3>
            <p class="mb-2 text-neutral-300">Oui, tous vos résultats sont enregistrés dans les "standings" (classements finaux) de chaque round. Vous pouvez voir :</p>
            <ul class="list-inside list-disc space-y-1 text-neutral-300">
              <li>Votre ELO avant et après chaque round</li>
              <li>Le changement d'ELO</li>
              <li>Votre position finale</li>
              <li>Toutes vos métriques de performance</li>
            </ul>
          </div>

          <div class="rounded-lg bg-slate-800/50 p-5">
            <h3 class="mb-3 text-lg font-semibold text-blue-400">Le système est-il équitable ?</h3>
            <p class="mb-2 text-neutral-300">Oui, le système est conçu pour être équitable :</p>
            <ul class="list-inside list-disc space-y-1 text-neutral-300">
              <li><strong>Même formule pour tous</strong> : Tous les joueurs sont évalués avec la même formule</li>
              <li><strong>Calcul progressif</strong> : Plus vous battez des joueurs forts, plus vous gagnez de points</li>
              <li><strong>Protection contre l'inflation</strong> : Le système empêche l'ELO moyen de dériver</li>
              <li><strong>Transparence</strong> : Tous vos résultats sont enregistrés et consultables</li>
            </ul>
          </div>

          <div class="rounded-lg bg-slate-800/50 p-5">
            <h3 class="mb-3 text-lg font-semibold text-blue-400">Que faire si je pense qu'il y a une erreur ?</h3>
            <p class="mb-2 text-neutral-300">Si vous pensez qu'il y a une erreur dans le calcul de votre ELO :</p>
            <ol class="list-inside list-decimal space-y-1 text-neutral-300">
              <li>Vérifiez que le round était dans une room publique avec 3+ joueurs</li>
              <li>Vérifiez votre position finale dans le round</li>
              <li>Vérifiez l'ELO de vos adversaires</li>
              <li>Contactez l'équipe de modération avec les détails du round concerné</li>
            </ol>
          </div>
        </div>
      </section>

      <!-- Résumé -->
      <section class="rounded-2xl border border-green-700/50 bg-gradient-to-br from-green-900/20 to-green-800/20 p-6">
        <h2 class="mb-4 text-2xl font-bold text-green-400">Résumé des points clés</h2>
        <ul class="space-y-2 text-neutral-300">
          <li class="flex items-start gap-2">
            <span class="mt-1 text-green-400">✅</span>
            <span><strong>ELO initial</strong> : 1500 points pour tous les nouveaux joueurs</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="mt-1 text-green-400">✅</span>
            <span><strong>Placement</strong> : 10 premiers rounds avec changements plus rapides</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="mt-1 text-green-400">✅</span>
            <span><strong>K-factor variable</strong> : Plus vous êtes expérimenté, moins votre ELO change rapidement</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="mt-1 text-green-400">✅</span>
            <span><strong>Calcul progressif</strong> : Plus vous battez des joueurs forts, plus vous gagnez de points</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="mt-1 text-green-400">✅</span>
            <span><strong>Conditions</strong> : Seulement les rooms publiques avec 3+ joueurs et 80%+ de tracks jouées</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="mt-1 text-green-400">✅</span>
            <span><strong>Calcul proportionnel</strong> : L'ELO évolue proportionnellement au nombre de tracks jouées</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="mt-1 text-green-400">✅</span>
            <span><strong>Protection</strong> : Système anti-inflation/déflation pour maintenir la précision</span>
          </li>
          <li class="flex items-start gap-2">
            <span class="mt-1 text-green-400">✅</span>
            <span><strong>Transparence</strong> : Tous vos résultats sont enregistrés et consultables</span>
          </li>
        </ul>
      </section>

      <!-- Back link -->
      <div class="pt-4 text-center">
        <Link :href="route('home')" class="text-blue-400 transition-colors hover:text-blue-300"> ← Retour à l'accueil </Link>
      </div>
    </div>
  </AppLayout>
</template>
