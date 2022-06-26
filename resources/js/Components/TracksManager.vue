<template>
  <div>
    <div class="-mb-8 flex flex-wrap p-8">
      <!-- Search in playlist -->
      <div class="w-full p-2 lg:w-1/3">
        <text-input v-model="form.search" prepend-icon="search" :placeholder="__('Search in playlist') + '...'" />
      </div>

      <!-- Search on streaming platforms -->
      <div class="relative w-full p-2 lg:w-2/3">
        <text-input v-model="search_online" prepend-icon="plus" :loading="loading" :placeholder="__('Add from streaming platforms')" />
        <ul v-if="results.length" class="top-100 absolute left-2 right-2 mt-1 max-h-60 w-full overflow-y-auto rounded border border-gray-200 bg-white text-gray-700 shadow">
          <li v-for="result in results" class="relative cursor-pointer border-b border-gray-200 px-1 py-2 hover:bg-gray-100 hover:text-gray-900">
            <div class="flex items-center">
              <div><Icon :name="result.provider" :title="result.provider" class="mr-2 h-6 w-6 flex-shrink-0 fill-gray-500" /></div>
              <div><Icon name="play" class="mr-2 h-6 w-6 flex-shrink-0 fill-gray-500" @click="play(result.preview_url)" /></div>
              <div class="flex-1">
                <span class="font-bold">{{ result.artist_name }}</span> {{ result.track_name }}
              </div>
              <div v-if="!result.added" class="self-end">
                <button :disabled="loading" class="btn-primary" type="button" @click="addTrack(result)">{{ __('Add') }}</button>
              </div>
              <div v-else class="self-end">
                <button :disabled="loading" class="btn-danger" type="button" @click="removeTrack(result)">{{ __('Remove') }}</button>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>

    <div class="mx-4">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="px-6 pb-4 pt-6" colspan="3">{{ __('Answers') }}</th>
          <th class="px-6 pb-4 pt-6" colspan="2">{{ __('Votes') }}</th>
          <th class="px-6 pb-4 pt-6" colspan="2">{{ __('Created at') }}</th>
        </tr>
        <tr v-for="track in tracks.data" :key="track.id" class="focus-within:bg-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700">
          <td class="border-t">
            <a target="_blank" :href="track.provider_url" class="flex items-center justify-center px-2 py-4 focus:text-blinest-500">
              <Icon :name="track.provider" :title="track.provider" class="mr-2 h-6 w-6 flex-shrink-0" />
            </a>
          </td>
          <td class="border-t">
            <div class="flex items-center justify-center px-2 py-4 focus:text-blinest-500">
              <Icon name="play" class="mr-2 h-6 w-6 flex-shrink-0 cursor-pointer" @click="play(track.preview_url)" />
            </div>
          </td>
          <td class="border-t">
            <div class="flex flex-col items-start px-6 py-4 text-sm focus:text-blinest-500">
              <div v-for="answer in track.answers" :key="answer.id" class="cursor-pointer whitespace-normal break-words" @click="editAnswer(track, answer)">
                <span class="font-bold">{{ __(answer.type.name) }}:</span> {{ answer.value }} ({{ answer.score }}pts)
              </div>
              <button class="text-gray-400" @click="editAnswer(track)"><Icon name="plus" class="inline-block h-4 w-4 flex-shrink-0 fill-gray-400" />{{ __('Add an answer') }}</button>
            </div>
          </td>
          <td class="border-t">
            <div class="flex items-start px-6 py-4 text-center text-sm focus:text-blinest-500"><Icon name="thumb-up" class="mr-2 h-6 w-6 flex-shrink-0 fill-blue-400" /> {{ track.up_votes }}</div>
          </td>
          <td class="border-t">
            <div class="flex items-start px-6 py-4 text-center text-sm focus:text-blinest-500"><Icon name="thumb-down" class="mr-2 h-6 w-6 flex-shrink-0 fill-gray-400" /> {{ track.down_votes }}</div>
          </td>
          <td class="border-t">
            <div class="flex flex-col items-start px-6 py-4 text-sm focus:text-blinest-500">
              {{ track.created_at }}
            </div>
          </td>
          <td class="border-t">
            <Icon name="delete" class="mr-2 h-6 w-6 flex-shrink-0 cursor-pointer fill-red-400" />
          </td>
        </tr>
        <tr v-if="tracks.length === 0">
          <td class="border-t px-6 py-4" colspan="4">{{ __('No tracks found') }}.</td>
        </tr>
      </table>
    </div>

    <pagination class="p-8" :links="tracks.links" />

    <answer-form :answer="selectedAnswer" :show="editingAnswer" max-width="md" @close="closeModal" @update="form.update" />
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'

import { v4 as uuid } from 'uuid'
import Icon from '@/Components/Icon'
import TextInput from '@/Components/TextInput'
import SelectInput from '@/Components/SelectInput'
import DialogModal from '@/Components/DialogModal'
import Pagination from '@/Components/Pagination'

import AnswerForm from '@/Components/AnswerForm'

import pickBy from 'lodash/pickBy'
import mapValues from 'lodash/mapValues'
import debounce from 'lodash/debounce'
import throttle from 'lodash/throttle'

export default {
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
  inheritAttrs: false,

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
      },
    },
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
        this.fecthMusicProviders()
      }, 300),
    },
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get(route('admin.playlists.edit', this.playlist), pickBy(this.form), {
          preserveScroll: true,
          preserveState: true,
        })
      }, 150),
    },
  },

  methods: {
    fecthMusicProviders() {
      let vm = this
      if (vm.search_online.length > 1) {
        vm.loading = true
        axios.get(route('tracks.search', this.playlist.id) + '?term=' + vm.search_online).then(function (response) {
          vm.results = response.data.tracks
          vm.loading = false
        })
      } else {
        vm.results = []
      }
    },

    editAnswer(track, answer = null) {
      this.selectedAnswer = answer
      this.editingAnswer = track.id
    },

    closeModal() {
      this.editingAnswer = false
    },

    play(track) {
      this.player.pause()
      this.player.src = track
      this.player.play()
    },

    addTrack(track) {
      this.$inertia.post(route('playlists.tracks.store', this.playlist), track, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
          this.fecthMusicProviders()
        },
      })
    },

    removeTrack(track) {
      let id = track.added ? track.added.id : track.id

      this.$inertia.delete(route('tracks.delete', id), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
          this.fecthMusicProviders()
        },
      })
    },
  },
}
</script>
