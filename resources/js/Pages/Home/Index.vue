<template>
  <div>
    <Head title="Home" />
    <h1 class="mb-8 text-3xl font-bold">Home</h1>
    <p class="mb-8 leading-normal">Hey there! Welcome to Ping CRM, a demo app designed to help illustrate how <a class="text-indigo-500 hover:text-orange-600 underline" href="https://inertiajs.com">Inertia.js</a> works.</p>

    <h2>Parties publiques</h2>
    <rooms :rooms="public_rooms"/>

  </div>
</template>

<script>
import { Head } from '@inertiajs/inertia-vue3'
import Layout from '@/Shared/Layout'
import Rooms from './partials/Rooms'

export default {

  components: {
    Head,
    Rooms,
  },

  layout: Layout,

  props: {
    public_rooms: Object,
  }

}
</script>
