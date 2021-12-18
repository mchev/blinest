<template>
  <div class="my-4">

    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <div class="flex flex-wrap -mb-8 -mr-6 p-8">

        <div class="relative w-full">
          <text-input v-model="search_online" class="pr-6 w-full" label="Add tracks" />
        </div>
        <ul class="bg-white border rounded shadow border-gray-200 pr-6 w-full mt-2 max-h-52 overflow-y-auto">
          <li v-for="result in results" class="px-1 py-2 border-b border-gray-200 relative cursor-pointer hover:bg-gray-100 hover:text-gray-900">
            <div class="flex items-center">
              <div><Icon :name="result.provider" :title="result.provider" class="flex-shrink-0 mr-2 w-6 h-6 fill-gray-500"/></div>
              <div><Icon name="play" @click="play(result.preview_url)" class="flex-shrink-0 mr-2 w-6 h-6 fill-gray-500"/></div>
              <div class="flex-1"><span class="font-bold">{{ result.artist_name }}</span> {{ result.track_name }}</div>
              <div v-if="!results.added" class="self-end"><button @click="addTrack(result)" class="btn-indigo" type="button">Add</button></div>
              <div v-else class="self-end"><button class="btn-green" type="button">Added</button></div>
            </div>
          </li>
        </ul>

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
  import Icon from '@/Shared/Icon'
  import TextInput from '@/Shared/TextInput'

  import pickBy from 'lodash/pickBy'
  import mapValues from 'lodash/mapValues'
  import debounce from 'lodash/debounce'


  export default {

    inheritAttrs: false,

    components: {
      Head,
      Link,
      Icon,
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
        player: new Audio(),
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
              vm.results = response.data.tracks;
            })
        }, 300),
      },
    },

    methods: {

      play(track) {

        this.player.pause();
        this.player.src = track;
        this.player.play();

      },

      addTrack(track) {

          axios.post(route('playlists.tracks.store', this.playlist.id), track)
            .then(function (response) {
              track.added = true;
            })

      },

      removeTrack(track) {

          axios.delete(route('tracks.delete', track.id))
            .then(function (response) {
              track.added = false;
            })

      }

    },
  }

</script>
