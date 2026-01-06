<script setup>
const appUrl = 'https://blinest.com'

const currentDate = new Date().toISOString().split('T')[0]

const structuredData = {
  '@context': 'https://schema.org',
  '@type': 'DefinedTermSet',
  'name': 'Glossaire Blinest',
  'description': 'Définitions claires et précises des termes techniques utilisés sur la plateforme Blinest',
  'url': `${appUrl}/docs/glossary`,
  'dateModified': currentDate,
  'publisher': {
    '@type': 'Organization',
    'name': 'Blinest',
    'url': appUrl,
    'logo': {
      '@type': 'ImageObject',
      'url': `${appUrl}/images/statics/logo_blinest.png`,
    },
  },
  'inLanguage': ['fr', 'en', 'es'],
  'hasDefinedTerm': [
    {
      '@type': 'DefinedTerm',
      'name': 'Blind test',
      'description': 'Jeu musical où les participants doivent identifier une chanson en écoutant uniquement un extrait audio, sans voir le titre, l\'artiste ou la pochette d\'album.',
      'inDefinedTermSet': `${appUrl}/docs/glossary`,
    },
    {
      '@type': 'DefinedTerm',
      'name': 'Quiz musical',
      'description': 'Jeu de questions-réponses basé sur la musique. Sur Blinest, les quiz musicaux prennent la forme de blind tests multijoueurs en ligne.',
      'inDefinedTermSet': `${appUrl}/docs/glossary`,
    },
    {
      '@type': 'DefinedTerm',
      'name': 'Room',
      'description': 'Espace de jeu multijoueur sur Blinest où les participants jouent ensemble à un blind test. Peut être publique (officielle, gérée par les modérateurs) ou privée (créée par les joueurs, avec ou sans mot de passe).',
      'inDefinedTermSet': `${appUrl}/docs/glossary`,
    },
    {
      '@type': 'DefinedTerm',
      'name': 'Round',
      'description': 'Manche de jeu dans une room. Chaque round contient plusieurs tracks que les joueurs doivent identifier.',
      'inDefinedTermSet': `${appUrl}/docs/glossary`,
    },
    {
      '@type': 'DefinedTerm',
      'name': 'Score',
      'description': 'Nombre total de points accumulés par un joueur sur Blinest. Chaque bonne réponse rapporte des points. Le score est toujours croissant.',
      'inDefinedTermSet': `${appUrl}/docs/glossary`,
    },
    {
      '@type': 'DefinedTerm',
      'name': 'Level',
      'description': 'Système de progression basé sur l\'XP qui récompense l\'engagement global d\'un joueur. Le level augmente en fonction des scores, ancienneté, rooms créées, playlists créées.',
      'inDefinedTermSet': `${appUrl}/docs/glossary`,
    },
    {
      '@type': 'DefinedTerm',
      'name': 'ELO',
      'description': 'Système de classement compétitif qui évalue la compétence réelle d\'un joueur. Contrairement au score et au level, l\'ELO peut augmenter ou diminuer selon les performances.',
      'inDefinedTermSet': `${appUrl}/docs/glossary`,
    },
    {
      '@type': 'DefinedTerm',
      'name': 'Public room',
      'description': 'Room officielle gérée par les modérateurs de Blinest. Les scores, levels et ELO comptent uniquement dans les rooms publiques.',
      'inDefinedTermSet': `${appUrl}/docs/glossary`,
    },
    {
      '@type': 'DefinedTerm',
      'name': 'Private room',
      'description': 'Room de la communauté créée et gérée par les joueurs. Peut être protégée par un mot de passe ou accessible sans mot de passe.',
      'inDefinedTermSet': `${appUrl}/docs/glossary`,
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

