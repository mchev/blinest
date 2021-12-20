<template>
  <div class="my-4">

    <div class="max-w-4xl bg-white rounded-md shadow overflow-hidden">

      <div class="flex flex-wrap -mb-8 -mr-6 p-8">

        <div class="w-full flex flex-col">
          <text-input v-model="search_online" class="w-2/3" label="Add tracks" />
        </div>
        <ul v-if="results.length" class="bg-white border rounded shadow border-gray-200 pr-6 w-full mt-2 max-h-52 overflow-y-auto">
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

      <table class="mt-4 w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6" colspan="3">{{ __('Answers') }}</th>
          <th class="pb-4 pt-6 px-6" colspan="2">{{ __('Votes') }}</th>
          <th class="pb-4 pt-6 px-6" colspan="2">{{ __('Created at') }}</th>
        </tr>
        <tr v-for="track in tracks" :key="track.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <a target="_blank" :href="track.provider_url" class="flex items-center justify-center px-2 py-4 focus:text-indigo-500">
              <Icon :name="track.provider" :title="track.provider" class="flex-shrink-0 mr-2 w-6 h-6 fill-gray-500"/>
            </a>
          </td>
          <td class="border-t">
            <div class="flex items-center justify-center px-2 py-4 focus:text-indigo-500">
              <Icon name="play" @click="play(track.preview_url)" class="cursor-pointer flex-shrink-0 mr-2 w-6 h-6 fill-gray-500"/>
            </div>
          </td>
          <td class="border-t">
            <div class="flex flex-col items-start px-6 py-4 focus:text-indigo-500 text-sm">
              <div v-for="answer in track.answers" :key="answer.id" @click="editAnswer(track, answer)" class="cursor-pointer">
                <span class="font-bold">{{ answer.key }}:</span> {{ answer.value}} ({{ answer.score}}pts)
              </div>
              <button class="text-gray-400" @click="editAnswer(track)"><Icon name="plus" class="inline-block flex-shrink-0 w-4 h-4 fill-gray-400"/> Add an answer</button>
            </div>
          </td>
          <td class="border-t">
              <div class="flex text-center items-start px-6 py-4 focus:text-indigo-500 text-sm">
                <Icon name="thumb-up" class="flex-shrink-0 mr-2 w-6 h-6 fill-blue-400"/> {{ track.up_votes }}
              </div>
          </td>
          <td class="border-t">
              <div class="flex text-center items-start px-6 py-4 focus:text-indigo-500 text-sm">
                <Icon name="thumb-down" class="flex-shrink-0 mr-2 w-6 h-6 fill-gray-400"/> {{ track.down_votes }}
              </div>
          </td>
          <td class="border-t">
              <div class="flex flex-col items-start px-6 py-4 focus:text-indigo-500 text-sm">
                {{ track.created_at }}
              </div>
          </td>
          <td class="border-t">
              <Icon name="delete" class="cursor-pointer flex-shrink-0 mr-2 w-6 h-6 fill-red-400"/>
          </td>
        </tr>
        <tr v-if="tracks.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No tracks found.</td>
        </tr>
      </table>

    </div>

    <answer-form :answer="selectedAnswer" :show="editingAnswer" @close="closeModal" max-width="md"/>

  </div>
</template>

<script>

  import { Head, Link } from '@inertiajs/inertia-vue3'

  import { v4 as uuid } from 'uuid'
  import Icon from '@/Shared/Icon'
  import TextInput from '@/Shared/TextInput'
  import SelectInput from '@/Shared/SelectInput'
  import DialogModal from '@/Shared/DialogModal'

  import AnswerForm from '@/Shared/AnswerForm'

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
      SelectInput,
      DialogModal,
      AnswerForm,
    },

    props: {
      playlist: {
        type: Object,
      },
    },

    data() {
      return {
        selectedAnswer: null,
        form: {},
        editingAnswer: false,
        search_online: '',
        search: '',
        loading: false,
        results: [],
        tracks: [],
        player: new Audio(),
      }
    },

    mounted() {
      let vm = this;
      axios.get(route('playlists.tracks', this.playlist))
        .then(function (response) {
          vm.tracks = response.data.tracks;
        })
    },

    watch: {
      search_online: {
        handler: debounce(function () {
          let vm = this;
          vm.results = [];
          if (vm.search_online.length > 1) {
            vm.loading = true;
            axios.get(route('tracks.search', {term: vm.search_online}))
              .then(function (response) {
                vm.results = response.data.tracks;
                vm.loading = false;
              })
          }
        }, 300),
      },
    },

    methods: {

      editAnswer(track, answer = null) {
        this.selectedAnswer = answer;
        this.editingAnswer = track.id;
      },

      closeModal() {
        this.editingAnswer = false;
      },

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
