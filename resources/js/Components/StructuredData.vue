<script setup>
	const appUrl = 'https://blinest.com'
</script>
<template>
  <teleport to="head">
    <component :is="'script'" type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "url": "{{ appUrl }}",
            "logo": "https://blinest.com/images/statics/logo_blinest.png"
        }
    </component>
    <component :is="'script'" type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "WebSite",
          "url": "{{ appUrl }}",
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