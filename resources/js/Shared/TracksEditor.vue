<template>
  <div class="my-4">

    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <div class="flex flex-wrap -mb-8 -mr-6 p-8">

        <text-input v-model="search_online" class="pb-8 pr-6 w-full" label="Add tracks" />

      </div>
    </div>

    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Name</th>
        </tr>
        <tr v-for="track in tracks" :key="track.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
              {{ track.name }}
          </td>
        </tr>
        <tr v-if="tracks.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No tracks found.</td>
        </tr>
      </table>
    </div>

  </div>
</template>

<script>

  import { Head, Link } from '@inertiajs/inertia-vue3'

  import { v4 as uuid } from 'uuid'
  import TextInput from '@/Shared/TextInput'

  import pickBy from 'lodash/pickBy'
  import mapValues from 'lodash/mapValues'
  import debounce from 'lodash/debounce'


  export default {

    inheritAttrs: false,

    components: {
      Head,
      Link,
      TextInput,
    },

    props: {
      playlist: {
        type: Object,
      },
    },

    data() {
      return {
        search_online: '',
        search: '',
        results: [],
        tracks: [],
      }
    },

    mounted() {
      let vm = this;
      axios.get(route('playlists.tracks', this.playlist))
        .then(function (response) {
          vm.tracks = response.data;
        })
    },

    watch: {
      search_online: {
        handler: debounce(function () {
          let vm = this;
          axios.get(route('tracks.search', {term: this.search_online}))
            .then(function (response) {
              vm.results = response.data;
            })
        }, 300),
      },
    },

    methods: {

    },
  }

</script>
