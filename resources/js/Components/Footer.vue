<script setup>
import { Link, usePage } from '@inertiajs/inertia-vue3'

// const user = usePage().props.value.auth?.user
</script>
<template>
	<div class="md:flex md:flex-shrink-0 mt-8 border-t border-neutral-500 text-neutral-500 text-sm">
		<div class="flex w-full items-center justify-between py-2 md:flex-shrink-0">
			Blinest
			<ul class="flex gap-4 flex-col md:flex-row">
				<li><Link href="/contact">Contact</Link></li>
				<li><a target="_blank" href="https://github.com/mchev/blinest/issues/new">{{ __('Report a bug') }}</a></li>
				<li><Link :href="route('pages.show', 'mentions-legales')">Mentions légales</Link></li>
				<li><Link :href="route('pages.show', 'politique-de-confidentialite')">Politique de confidentialité</Link></li>
				<li><a target="_blank" href="https://donate.stripe.com/00g2bvf8i08X8De6oo">Faire un don</a></li>
			</ul>
		</div>
	</div>
</template>