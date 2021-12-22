<template>

  <div>

    <div class="flex flex-wrap -mb-8 p-8">

      <!-- Search in playlist -->
      <div class="w-full lg:w-1/3 p-2">
        <text-input v-model="form.search" prependIcon="search" :placeholder="__('Search in playlist') + '...'" />
      </div>

      <!-- Search on streaming platforms -->
      <div class="w-full relative lg:w-2/3 p-2">
        <text-input v-model="search_online" prependIcon="plus" :loading="loading" :placeholder="__('Add from streaming platforms')" />
        <ul v-if="results.length" class="absolute top-100 left-2 right-2 bg-white text-gray-700 border rounded shadow border-gray-200 mt-1 w-full max-h-60 overflow-y-auto">
          <li v-for="result in results" class="px-1 py-2 border-b border-gray-200 relative cursor-pointer hover:bg-gray-100 hover:text-gray-900">
            <div class="flex items-center">
              <div><Icon :name="result.provider" :title="result.provider" class="flex-shrink-0 mr-2 w-6 h-6 fill-gray-500"/></div>
              <div><Icon name="play" @click="play(result.preview_url)" class="flex-shrink-0 mr-2 w-6 h-6 fill-gray-500"/></div>
              <div class="flex-1"><span class="font-bold">{{ result.artist_name }}</span> {{ result.track_name }}</div>
              <div v-if="!result.added" class="self-end">
                <button @click="addTrack(result)" :disabled="loading" class="btn-indigo" type="button">{{ __("Add") }}</button>
              </div>
              <div v-else class="self-end">
                <button @click="removeTrack(result)" :disabled="loading" class="btn-danger" type="button">{{ __("Remove") }}</button>
              </div>
            </div>
          </li>
        </ul>
      </div>
  
    </div>

    <div class="mx-4">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6" colspan="3">{{ __('Answers') }}</th>
          <th class="pb-4 pt-6 px-6" colspan="2">{{ __('Votes') }}</th>
          <th class="pb-4 pt-6 px-6" colspan="2">{{ __('Created at') }}</th>
        </tr>
        <tr v-for="track in tracks.data" :key="track.id" class="hover:bg-gray-100 dark:hover:bg-gray-700 focus-within:bg-gray-100">
          <td class="border-t">
            <a target="_blank" :href="track.provider_url" class="flex items-center justify-center px-2 py-4 focus:text-indigo-500">
              <Icon :name="track.provider" :title="track.provider" class="flex-shrink-0 mr-2 w-6 h-6"/>
            </a>
          </td>
          <td class="border-t">
            <div class="flex items-center justify-center px-2 py-4 focus:text-indigo-500">
              <Icon name="play" @click="play(track.preview_url)" class="cursor-pointer flex-shrink-0 mr-2 w-6 h-6"/>
            </div>
          </td>
          <td class="border-t">
            <div class="flex flex-col items-start px-6 py-4 focus:text-indigo-500 text-sm">
              <div v-for="answer in track.answers" :key="answer.id" @click="editAnswer(track, answer)" class="break-words whitespace-normal cursor-pointer">
                <span class="font-bold">{{ __(answer.key) }}:</span> {{ answer.value}} ({{ answer.score}}pts)
              </div>
              <button class="text-gray-400" @click="editAnswer(track)"><Icon name="plus" class="inline-block flex-shrink-0 w-4 h-4 fill-gray-400"/>{{ __("Add an answer") }}</button>
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
          <td class="px-6 py-4 border-t" colspan="4">{{ __("No tracks found") }}.</td>
        </tr>
      </table>
    </div>

    <pagination class="p-8" :links="tracks.links" />

    <answer-form :answer="selectedAnswer" :show="editingAnswer" @close="closeModal" @update="form.update" max-width="md"/>

  </div>

</template>

<script>

  import { Head, Link } from '@inertiajs/inertia-vue3'

  import { v4 as uuid } from 'uuid'
  import Icon from '@/Shared/Icon'
  import TextInput from '@/Shared/TextInput'
  import SelectInput from '@/Shared/SelectInput'
  import DialogModal from '@/Shared/DialogModal'
  import Pagination from '@/Shared/Pagination'

  import AnswerForm from '@/Shared/AnswerForm'

  import pickBy from 'lodash/pickBy'
  import mapValues from 'lodash/mapValues'
  import debounce from 'lodash/debounce'
  import throttle from 'lodash/throttle'


  export default {

    inheritAttrs: false,

    components: {
      Head,
      Link,
      Icon,
      TextInput,
      SelectInput,
      DialogModal,
      Pagination,
      AnswerForm,
    },

    props: {
      playlist: {
        type: Object,
      },
      tracks: {
        type: Object,
        default: {},
      },
      filters: {
        type: Object,
        default: {
          search: '',
        }
      }
    },

    data() {
      return {
        selectedAnswer: null,
        form: {
          search: this.filters.search,
        },
        editingAnswer: false,
        search_online: '',
        search: '',
        loading: false,
        results: [],
        player: new Audio(),
      }
    },

    watch: {
      search_online: {
        handler: debounce(function () {
          this.fecthMusicProviders();
        }, 300),
      },
      form: {
        deep: true,
        handler: throttle(function () {
          this.$inertia.get(route('admin.playlists.edit', this.playlist), pickBy(this.form), { 
            preserveScroll: true, 
            preserveState: true 
          })
        }, 150),
      },
    },

    methods: {

      fecthMusicProviders() {
        let vm = this;
        if (vm.search_online.length > 1) {
          vm.loading = true;
          axios.get(route('tracks.search', this.playlist.id) + '?term=' + vm.search_online)
            .then(function (response) {
              vm.results = response.data.tracks;
              vm.loading = false;
            })
        } else {
          vm.results = [];
        }
      },

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

          this.$inertia.post(route('playlists.tracks.store', this.playlist), track, { 
            preserveScroll: true, 
            preserveState: true,
            onSuccess: () => {
              this.fecthMusicProviders();
            }
          })
      },

      removeTrack(track) {

          let id = (track.added) ? track.added.id : track.id;

          this.$inertia.delete(route('tracks.delete', id), { 
            preserveScroll: true, 
            preserveState: true,
            onSuccess: () => {
              this.fecthMusicProviders();
            }
          })
      }

    },
  }

</script>
