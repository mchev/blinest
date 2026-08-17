<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const currentDate = new Date().toLocaleDateString('fr-FR', { year: 'numeric', month: 'long', day: 'numeric' })
</script>

<template>
  <AppLayout>
    <div class="mx-auto max-w-5xl space-y-8 px-4 py-8">
      <!-- Header -->
      <div class="space-y-4 text-center">
        <h1 class="text-4xl font-bold text-white">Créer des rooms et playlists</h1>
        <p class="text-lg text-neutral-400">Guide complet pour créer et configurer vos contenus sur Blinest</p>
        <p class="text-sm text-neutral-500">Dernière mise à jour : {{ currentDate }}</p>
      </div>

      <!-- Introduction -->
      <div class="rounded-2xl border border-slate-700 bg-gradient-to-br from-slate-800 to-slate-900 p-6">
        <h2 class="mb-4 text-2xl font-bold text-white">Comprendre la différence entre rooms et playlists</h2>
        <div class="space-y-4 text-neutral-300">
          <p><strong>Une playlist est une collection de tracks (morceaux de musique)</strong> organisés par thème, genre ou catégorie. Les playlists sont réutilisables et peuvent être attachées à plusieurs rooms.</p>
          <p><strong>Une room est un espace de jeu</strong> qui utilise une ou plusieurs playlists pour organiser des parties de blind test. La room définit les règles de jeu (durée des tracks, nombre de tracks par round, etc.).</p>
          <div class="mt-4 rounded-lg bg-slate-800/50 p-4">
            <p class="text-sm text-neutral-300"><strong class="text-white">Relation :</strong> Une room peut utiliser plusieurs playlists, et une playlist peut être utilisée par plusieurs rooms. Les tracks appartiennent à une playlist unique, mais cette playlist peut être partagée entre plusieurs rooms.</p>
          </div>
        </div>
      </div>

      <!-- Playlists Section -->
      <div class="rounded-2xl border border-blue-700/50 bg-gradient-to-br from-blue-900/20 to-blue-800/20 p-6">
        <h2 class="mb-6 text-3xl font-bold text-blue-400">Créer une playlist</h2>

        <div class="space-y-6">
          <!-- Step 1: Create Playlist -->
          <div class="rounded-lg bg-slate-800/50 p-5">
            <h3 class="mb-3 text-xl font-bold text-white">Étape 1 : Créer la playlist</h3>
            <p class="mb-4 text-neutral-300">Pour créer une playlist, vous devez fournir :</p>
            <ul class="space-y-2 text-neutral-300">
              <li class="flex items-start gap-2">
                <span class="mt-1 text-blue-400">•</span>
                <span><strong>Nom</strong> : Maximum 50 caractères, doit être unique sur la plateforme</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="mt-1 text-blue-400">•</span>
                <span><strong>Description</strong> : Optionnelle, permet de décrire le thème ou le contenu de la playlist</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="mt-1 text-blue-400">•</span>
                <span><strong>Visibilité</strong> : Vous pouvez rendre la playlist publique (accessible à tous) ou privée (uniquement pour vous)</span>
              </li>
            </ul>
            <p class="mt-4 text-sm text-neutral-300"><strong>Note :</strong> Vous êtes automatiquement ajouté comme modérateur de votre playlist. Vous pouvez ajouter d'autres modérateurs pour gérer la playlist ensemble.</p>
          </div>

          <!-- Step 2: Add Tracks -->
          <div class="rounded-lg bg-slate-800/50 p-5">
            <h3 class="mb-3 text-xl font-bold text-white">Étape 2 : Ajouter des tracks à la playlist</h3>
            <p class="mb-4 text-neutral-300">Une fois la playlist créée, vous pouvez y ajouter des morceaux de musique. Pour cela :</p>
            <ol class="mb-4 list-inside list-decimal space-y-3 text-neutral-300">
              <li><strong>Utilisez la barre de recherche</strong> dans la section "Rechercher des morceaux" de votre playlist. Tapez le nom d'une chanson ou d'un artiste pour rechercher sur YouTube, Apple Music, Deezer et Blinest.</li>
              <li><strong>Parcourez les résultats</strong> qui affichent le nom de l'artiste, le titre de la chanson, l'album et la pochette. Vous pouvez écouter un extrait avant d'ajouter le morceau.</li>
              <li><strong>Cliquez sur "Ajouter"</strong> pour inclure le morceau dans votre playlist.</li>
              <li><strong>Importez depuis une autre plateforme</strong> : Vous pouvez également importer une playlist complète depuis Deezer si vous avez le lien.</li>
              <li><strong>Uploadez vos propres fichiers</strong> : Pour les morceaux non disponibles sur les plateformes, vous pouvez uploader vos propres fichiers audio.</li>
            </ol>
            <div class="mt-4 rounded-lg bg-blue-900/30 p-4">
              <p class="text-sm text-neutral-300"><strong class="text-blue-400">Astuce :</strong> Vous pouvez filtrer les résultats par fournisseur musical (YouTube, Apple Music, Deezer, Blinest) pour trouver plus facilement ce que vous cherchez.</p>
            </div>
          </div>

          <!-- Step 3: Configure Answers -->
          <div class="rounded-lg bg-slate-800/50 p-5">
            <h3 class="mb-3 text-xl font-bold text-white">Étape 3 : Configurer les réponses et les points</h3>
            <p class="mb-4 text-neutral-300">Après avoir ajouté un morceau à votre playlist, vous devez configurer les réponses que les joueurs devront trouver. Chaque morceau doit avoir au minimum deux réponses :</p>
            <ul class="mb-4 space-y-3 text-neutral-300">
              <li class="flex items-start gap-2">
                <span class="mt-1 text-blue-400">•</span>
                <div>
                  <span><strong>Réponse Artiste</strong> : Le nom de l'artiste ou du groupe</span>
                  <p class="mt-1 text-sm text-neutral-400">Cette réponse est obligatoire pour tous les morceaux.</p>
                </div>
              </li>
              <li class="flex items-start gap-2">
                <span class="mt-1 text-blue-400">•</span>
                <div>
                  <span><strong>Réponse Titre</strong> : Le titre de la chanson</span>
                  <p class="mt-1 text-sm text-neutral-400">Cette réponse est obligatoire pour tous les morceaux.</p>
                </div>
              </li>
            </ul>
            <p class="mb-4 text-neutral-300">
              <strong>Pour ajouter une réponse :</strong>
            </p>
            <ol class="mb-4 list-inside list-decimal space-y-2 text-neutral-300">
              <li>Cliquez sur le bouton <strong>"Ajouter une réponse"</strong> sous le morceau dans votre playlist</li>
              <li>Sélectionnez le <strong>type de réponse</strong> (Artiste, Titre, ou autre type disponible)</li>
              <li>Entrez la <strong>réponse exacte</strong> que les joueurs devront trouver</li>
              <li>Définissez le <strong>nombre de points</strong> attribués pour cette réponse (entre 0 et 99)</li>
              <li>Cliquez sur <strong>"Enregistrer"</strong> pour valider</li>
            </ol>
            <p class="mb-4 text-neutral-300"><strong>Gestion des réponses :</strong> Vous pouvez modifier ou supprimer une réponse existante en cliquant dessus. Les réponses s'affichent sous chaque morceau avec leur type et leur nombre de points.</p>
            <div class="mt-4 rounded-lg bg-blue-900/30 p-4">
              <p class="text-sm text-neutral-300"><strong class="text-blue-400">Système de points :</strong> Le score final d'un joueur pour une réponse = score de base de la réponse + bonus ordre (0.5 points si dans le top 3) + bonus vitesse (0.5 points si réponse donnée dans les 18% de la durée du morceau).</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Rooms Section -->
      <div class="rounded-2xl border border-purple-700/50 bg-gradient-to-br from-purple-900/20 to-purple-800/20 p-6">
        <h2 class="mb-6 text-3xl font-bold text-purple-400">Créer une room</h2>

        <div class="space-y-6">
          <!-- Step 1: Create Room -->
          <div class="rounded-lg bg-slate-800/50 p-5">
            <h3 class="mb-3 text-xl font-bold text-white">Étape 1 : Créer la room</h3>
            <p class="mb-4 text-neutral-300">Pour créer une room, vous devez fournir :</p>
            <ul class="space-y-2 text-neutral-300">
              <li class="flex items-start gap-2">
                <span class="mt-1 text-purple-400">•</span>
                <span><strong>Nom</strong> : Maximum 25 caractères, doit être unique sur la plateforme</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="mt-1 text-purple-400">•</span>
                <span><strong>Catégorie</strong> : Obligatoire, détermine dans quelle section la room apparaît (Années 2000, Disney, Chanson française, etc.)</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="mt-1 text-purple-400">•</span>
                <span><strong>Description</strong> : Optionnelle, permet de décrire le thème ou les règles spéciales de la room</span>
              </li>
            </ul>
            <p class="mt-4 text-sm text-neutral-300"><strong>Note :</strong> Vous êtes automatiquement ajouté comme modérateur de votre room. Vous pouvez ajouter d'autres modérateurs pour gérer la room ensemble.</p>
          </div>

          <!-- Step 2: Attach Playlists -->
          <div class="rounded-lg bg-slate-800/50 p-5">
            <h3 class="mb-3 text-xl font-bold text-white">Étape 2 : Attacher des playlists à la room</h3>
            <p class="mb-4 text-neutral-300">Une room peut utiliser plusieurs playlists. Les tracks de toutes les playlists attachées seront disponibles pour les parties.</p>
            <ul class="space-y-2 text-neutral-300">
              <li class="flex items-start gap-2">
                <span class="mt-1 text-purple-400">•</span>
                <span>Vous pouvez attacher vos propres playlists (publiques ou privées)</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="mt-1 text-purple-400">•</span>
                <span>Vous pouvez attacher des playlists publiques créées par d'autres utilisateurs</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="mt-1 text-purple-400">•</span>
                <span>Vous pouvez attacher des playlists dont vous êtes modérateur</span>
              </li>
            </ul>
            <div class="mt-4 rounded-lg bg-purple-900/30 p-4">
              <p class="text-sm text-neutral-300"><strong class="text-purple-400">Important :</strong> Une room sans playlist attachée ne peut pas démarrer de partie car il n'y a pas de tracks disponibles.</p>
            </div>
          </div>

          <!-- Step 3: Configure Options -->
          <div class="rounded-lg bg-slate-800/50 p-5">
            <h3 class="mb-3 text-xl font-bold text-white">Étape 3 : Configurer les options de jeu</h3>
            <p class="mb-4 text-neutral-300">Les options de jeu déterminent comment se déroulent les parties dans votre room :</p>

            <div class="space-y-4">
              <!-- Tracks by round -->
              <div class="border-l-4 border-purple-500 pl-4">
                <h4 class="mb-2 font-bold text-white">Tracks par round</h4>
                <p class="mb-2 text-sm text-neutral-300">Nombre de tracks joués dans chaque round.</p>
                <ul class="space-y-1 text-sm text-neutral-300">
                  <li>• Minimum : 1 track</li>
                  <li>• Maximum : 50 tracks</li>
                  <li>• Valeur par défaut : 15 tracks</li>
                </ul>
              </div>

              <!-- Track duration -->
              <div class="border-l-4 border-purple-500 pl-4">
                <h4 class="mb-2 font-bold text-white">Durée d'un track</h4>
                <p class="mb-2 text-sm text-neutral-300">Durée en secondes de l'extrait audio joué pour chaque track.</p>
                <ul class="space-y-1 text-sm text-neutral-300">
                  <li>• Minimum : 5 secondes</li>
                  <li>• Maximum : 30 secondes</li>
                  <li>• Valeur par défaut : 30 secondes</li>
                </ul>
              </div>

              <!-- Pause between tracks -->
              <div class="border-l-4 border-purple-500 pl-4">
                <h4 class="mb-2 font-bold text-white">Pause entre les tracks</h4>
                <p class="mb-2 text-sm text-neutral-300">Temps d'attente en secondes entre chaque track dans un round.</p>
                <ul class="space-y-1 text-sm text-neutral-300">
                  <li>• Minimum : 0 secondes</li>
                  <li>• Maximum : 60 secondes</li>
                  <li>• Valeur par défaut : 0 secondes</li>
                </ul>
              </div>

              <!-- Pause between rounds -->
              <div class="border-l-4 border-purple-500 pl-4">
                <h4 class="mb-2 font-bold text-white">Pause entre les rounds</h4>
                <p class="mb-2 text-sm text-neutral-300">Temps d'attente en secondes entre la fin d'un round et le début du suivant.</p>
                <ul class="space-y-1 text-sm text-neutral-300">
                  <li>• Minimum : 0 secondes</li>
                  <li>• Maximum : 60 secondes</li>
                  <li>• Valeur par défaut : 10 secondes</li>
                </ul>
              </div>

              <!-- Boolean options -->
              <div class="border-l-4 border-purple-500 pl-4">
                <h4 class="mb-2 font-bold text-white">Options booléennes</h4>
                <ul class="space-y-2 text-sm text-neutral-300">
                  <li><strong>Publique</strong> : Si activée, la room est accessible à tous. Si désactivée, la room est privée et nécessite un mot de passe.</li>
                  <li><strong>Autostart</strong> : Si activée, les rounds démarrent automatiquement après la pause entre rounds. Si désactivée, un modérateur doit démarrer manuellement chaque round.</li>
                  <li><strong>Aléatoire</strong> : Si activée, les tracks sont joués dans un ordre aléatoire. Si désactivée, les tracks sont joués dans l'ordre de la playlist.</li>
                  <li><strong>Chat actif</strong> : Si activée, les joueurs peuvent utiliser le chat pendant les parties. Si désactivée, le chat est désactivé.</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Workflow Section -->
      <div class="rounded-2xl border border-green-700/50 bg-gradient-to-br from-green-900/20 to-green-800/20 p-6">
        <h2 class="mb-6 text-3xl font-bold text-green-400">Workflow recommandé</h2>
        <div class="space-y-4 text-neutral-300">
          <div class="flex items-start gap-3">
            <span class="text-xl font-bold text-green-400">1.</span>
            <div>
              <p class="font-semibold text-white">Créer une ou plusieurs playlists</p>
              <p class="text-sm">Organisez vos tracks par thème ou genre dans des playlists réutilisables.</p>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <span class="text-xl font-bold text-green-400">2.</span>
            <div>
              <p class="font-semibold text-white">Ajouter des tracks aux playlists</p>
              <p class="text-sm">Pour chaque track, configurez les réponses (Artiste et Titre) et les points associés.</p>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <span class="text-xl font-bold text-green-400">3.</span>
            <div>
              <p class="font-semibold text-white">Créer une room</p>
              <p class="text-sm">Définissez le nom, la catégorie et la description de votre room.</p>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <span class="text-xl font-bold text-green-400">4.</span>
            <div>
              <p class="font-semibold text-white">Attacher des playlists à la room</p>
              <p class="text-sm">Sélectionnez une ou plusieurs playlists qui seront utilisées pour les parties.</p>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <span class="text-xl font-bold text-green-400">5.</span>
            <div>
              <p class="font-semibold text-white">Configurer les options de jeu</p>
              <p class="text-sm">Ajustez le nombre de tracks par round, la durée, les pauses et les options booléennes selon vos préférences.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Key Points -->
      <div class="rounded-2xl border border-indigo-700/50 bg-gradient-to-br from-indigo-900/20 to-indigo-800/20 p-6">
        <h2 class="mb-4 text-2xl font-bold text-indigo-400">Points importants à retenir</h2>
        <ul class="space-y-3 text-neutral-300">
          <li class="flex items-start gap-3">
            <span class="font-bold text-indigo-400">•</span>
            <span><strong>Les playlists sont réutilisables</strong> : Une même playlist peut être utilisée par plusieurs rooms différentes.</span>
          </li>
          <li class="flex items-start gap-3">
            <span class="font-bold text-indigo-400">•</span>
            <span><strong>Les tracks appartiennent à une seule playlist</strong> : Chaque track est lié à une playlist unique, mais cette playlist peut être partagée.</span>
          </li>
          <li class="flex items-start gap-3">
            <span class="font-bold text-indigo-400">•</span>
            <span><strong>Les réponses sont obligatoires</strong> : Chaque track doit avoir au minimum une réponse Artiste et une réponse Titre.</span>
          </li>
          <li class="flex items-start gap-3">
            <span class="font-bold text-indigo-400">•</span>
            <span><strong>Les points sont configurables</strong> : Vous pouvez définir le score de chaque réponse pour équilibrer la difficulté.</span>
          </li>
          <li class="flex items-start gap-3">
            <span class="font-bold text-indigo-400">•</span>
            <span><strong>Les modérateurs peuvent gérer</strong> : Vous pouvez ajouter des modérateurs pour gérer vos playlists et rooms ensemble.</span>
          </li>
        </ul>
      </div>

      <!-- Back link -->
      <div class="pt-4 text-center">
        <Link :href="route('docs.index')" class="text-blue-400 transition-colors hover:text-blue-300"> ← Retour à la documentation </Link>
      </div>
    </div>
  </AppLayout>
</template>
