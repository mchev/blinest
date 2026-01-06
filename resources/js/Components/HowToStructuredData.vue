<script setup>
const appUrl = 'https://blinest.com'

const structuredData = {
  '@context': 'https://schema.org',
  '@type': 'HowTo',
  'name': 'Comment jouer à un blind test sur Blinest',
  'description': 'Guide étape par étape pour jouer à un quiz musical multijoueur sur Blinest. Apprenez à rejoindre une room, identifier des chansons et gagner des points.',
  'image': `${appUrl}/images/statics/screenshot.png`,
  'totalTime': 'PT5M',
  'estimatedCost': {
    '@type': 'MonetaryAmount',
    'currency': 'EUR',
    'value': '0',
  },
  'supply': [
    {
      '@type': 'HowToSupply',
      'name': 'Compte Blinest (gratuit)',
    },
    {
      '@type': 'HowToSupply',
      'name': 'Connexion Internet',
    },
    {
      '@type': 'HowToSupply',
      'name': 'Haut-parleurs ou casque audio',
    },
  ],
  'tool': [
    {
      '@type': 'HowToTool',
      'name': 'Navigateur web moderne',
    },
  ],
  'step': [
    {
      '@type': 'HowToStep',
      'position': 1,
      'name': 'Créer un compte ou se connecter',
      'text': 'Créez un compte gratuit sur Blinest ou connectez-vous avec votre compte existant. Vous pouvez utiliser Google, Facebook, Discord ou Deezer pour vous connecter rapidement.',
      'url': `${appUrl}/register`,
    },
    {
      '@type': 'HowToStep',
      'position': 2,
      'name': 'Choisir une room',
      'text': 'Parcourez les rooms disponibles sur la page d\'accueil. Les rooms sont organisées par catégories (Années 2000, Disney, Chanson française, Rock, Pop, etc.). Cliquez sur une room pour la rejoindre.',
      'url': `${appUrl}/`,
    },
    {
      '@type': 'HowToStep',
      'position': 3,
      'name': 'Rejoindre la room',
      'text': 'Si la room est privée, entrez le mot de passe. Une fois dans la room, attendez que la partie commence ou cliquez sur "Démarrer" si vous êtes le créateur de la room.',
    },
    {
      '@type': 'HowToStep',
      'position': 4,
      'name': 'Écouter et identifier',
      'text': 'Un extrait audio de 30 secondes (par défaut) sera joué. Écoutez attentivement et tentez d\'identifier le titre de la chanson et/ou le nom de l\'artiste.',
    },
    {
      '@type': 'HowToStep',
      'position': 5,
      'name': 'Saisir votre réponse',
      'text': 'Tapez votre réponse dans le champ de saisie. Vous pouvez indiquer le titre, l\'artiste, ou les deux. Plus votre réponse est précise et rapide, plus vous gagnez de points.',
    },
    {
      '@type': 'HowToStep',
      'position': 6,
      'name': 'Voir les résultats',
      'text': 'Après chaque track, les bonnes réponses sont affichées. Vous verrez votre score et votre position dans le classement en temps réel.',
    },
    {
      '@type': 'HowToStep',
      'position': 7,
      'name': 'Continuer le round',
      'text': 'Le round continue avec plusieurs tracks. À la fin du round, un podium affiche les trois meilleurs joueurs. Votre score total, votre level et votre ELO sont mis à jour automatiquement.',
    },
  ],
}
</script>

<template>
  <teleport to="head">
    <component :is="'script'" type="application/ld+json" v-bind="{}">
      {{ JSON.stringify(structuredData) }}
    </component>
  </teleport>
</template>

