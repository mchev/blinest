<script setup>
const appUrl = 'https://blinest.com'
const currentDate = new Date().toISOString().split('T')[0]
</script>
<template>
  <teleport to="head">
    <component :is="'script'" type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "url": "{{ appUrl }}",
            "name": "Blinest",
            "logo": "https://blinest.com/images/statics/logo_blinest.png",
            "description": "Plateforme gratuite de quiz musicaux multijoueurs en ligne. Jouez à des blind tests avec des milliers de joueurs du monde entier.",
            "foundingDate": "2019",
            "sameAs": [
                "https://discord.com/invite/uKyVgcxcFa"
            ],
            "contactPoint": {
                "@type": "ContactPoint",
                "contactType": "Customer Service",
                "url": "{{ appUrl }}/contact"
            }
        }
    </component>
    <component :is="'script'" type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "WebSite",
          "url": "{{ appUrl }}",
          "name": "Blinest",
          "description": "Quiz musicaux multijoueurs gratuits en ligne",
          "inLanguage": ["fr", "en", "es"],
          "dateModified": "{{ currentDate }}",
          "potentialAction": [{
            "@type": "SearchAction",
            "target": {
              "@type": "EntryPoint",
              "urlTemplate": "{{ appUrl }}/?search={search_term_string}"
            },
            "query-input": "required name=search_term_string"
          }]
        }
    </component>
  </teleport>
</template>