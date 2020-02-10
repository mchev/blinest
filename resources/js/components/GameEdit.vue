<template>
    <div>

        <div class="card">
            <div class="card-header">Questions</div>

            <div class="card-body">

                <div class="input-group mb-4">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">Chercher dans la bibliothèque</span>
                    </div>
                    <select v-model="origin" class="form-control">
                        <option value="deezer" selected="selected">Deezer</option>
                        <option value="playlist">Playlist Deezer</option>
                    </select>
                    <input v-if="origin == 'deezer'" type="search" v-model="query" @keyup="searchFromDeezer" class="form-control" style="width:50%" placeholder="Ajouter un titre">
                    <input v-if="origin == 'playlist'" type="search" v-model="query" @keyup="searchFromDeezer" class="form-control" style="width:50%" placeholder="ID de la playlist">
                    <button v-if="origin == 'playlist'" type="button" @click="addPlaylist()" class="btn btn-success">Valider</button>
                </div>

                <div class="panel-footer" v-if="results.length">
                    <ul class="list-group result-list">
                        <li class="list-group-item d-flex justify-content-between align-items-center" v-for="result in results">
                            {{ result.artist.name }} - {{ result.title }}
                            <button @click="addtrack(result)" class="btn btn-sm btn-success">Ajouter</button>
                        </li>
                    </ul>
                </div>

                <hr>

                <div class="row mb-3">

                    <div class="col-md-3">
                        <input type="search" @keyup="searchTracks()" v-model="search" class="form-control" placeholder="Recherche">
                    </div>

                    <div class="col-md-5 text-center">
                        <ul class="pagination">
                            <li v-if="current_page > 1" class="page-item">
                                <span @click="paginate('prev')" class="page-link">&laquo;</span>
                            </li>
                            <li v-if="last_page > 1" class="page-item" :class="{active: current_page == n}" v-for="n in last_page">
                                <span @click="paginate(n)" class="page-link">{{ n }}</span>
                            </li>
                            <li v-if="current_page < last_page" class="page-item">
                                <span  @click="paginate('next')" class="page-link">&raquo;</span>
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-2">
                        <button v-if="tracks.length" class="btn btn-danger" @click="deleteTracks()">Tout supprimer</button>
                    </div>

                    <div class="col-md-2 text-right">
                        <button class="btn btn-info">{{ tracks.length }} / {{ total }}</button>
                    </div>

                </div>

                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Artiste - Titre</th>
                            <th scope="col">Aperçu</th>
                            <th scope="col">Réponse personnalisée</th>
                            <th scope="col" class="text-right"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="track in tracks">
                            <th scope="row">{{ track.id }}</th>
                            <td>{{ track.artist_name }} - {{ track.track_name }}</td>
                            <td>
                                <audio controls preload="none" :src="track.preview_url"></audio>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" v-model="track.custom_answer" class="form-control">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-success" @click="saveCustomAnwser(track)" type="button"><i class="fas fa-check"></i></button>
                                    </div>
                                </div>
                            </td>
                            <td><button @click="deleteTrack(track)" class="btn btn-outline-danger">Supprimer</button></td>
                        </tr>
                    </tbody>
                </table>

            </div>

        </div>


        <!-- MODAL PLAYLIST -->
        <div class="modal fade" tabindex="-1" role="dialog" id="playlistModal">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Importer une playlist</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div v-if="playlist.title" class="modal-body">
                <h1>{{ playlist.title }} ({{ playlist.nb_tracks }} pistes)</h1>
                <img class="img-fluid" :src="playlist.picture_medium">
              </div>
              <div class="modal-footer">
                <button type="button" @click="storePlaylist()" class="btn btn-primary">
                    <div v-if="spinner" class="spinner-border" role="status">
                      <span class="sr-only">Loading...</span>
                    </div>
                    Importer
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
              </div>
            </div>
          </div>
        </div>


    </div>
</template>

<script>
    export default {

        name: 'gameEdition',

        props: ['game'],

        data() {
            return {
                query: '',
                debounce: null,
                spinner: false,
                origin: 'deezer',
                playlist: [],
                playlistModal: false,
                results: [],
                tracks: [],
                current_page: 1,
                last_page: 0,
                prev_page_url: '',
                next_page_url: '',
                total: 0,
                page_url: '/games/' + this.game.id + '/tracks',
                search: '',
                search_url: '/games/' + this.game.id + '/tracks/search',
            }
        },

        mounted() {
            //console.log('Component mounted.')
        },

        created() {
            this.getTracks();
        },

        methods: {

            searchTracks() {
                if( this.search.length > 2) {
                    axios.get(this.search_url + '?q=' + this.search).then((response) => {
                        this.tracks = response.data.data;
                        this.current_page = response.data.current_page;
                        this.last_page = response.data.last_page;
                        this.prev_page_url = response.data.prev_page_url + '&q=' + this.search;
                        this.next_page_url = response.data.next_page_url + '&q=' + this.search;
                        this.total = response.data.total;
                    }).catch((error) => {
                        console.warn(error);
                    });
                } else {
                    this.getTracks();
                }
            },

            paginate(direction) {
                switch(direction) {
                  case 'prev':
                    this.page_url = this.prev_page_url;
                    break;
                  case 'next':
                    this.page_url = this.next_page_url;
                    break;
                  default:
                    if (this.search.length) {
                        this.page_url = '/games/' + this.game.id + '/tracks?page=' + direction;
                    } else {
                        this.page_url = '/games/' + this.game.id + '/tracks/search?page=' + direction + '&q=' + this.search;
                    }
                }
                if (this.search.length) {
                    this.search_url = this.page_url;
                    this.search();
                } else {
                    this.getTracks();
                }
            },

            searchFromDeezer() {

                this.results = [];

                clearTimeout(this.debounce);

                this.debounce = setTimeout(() => {

                    if (this.query.length > 2) {

                        this.errors = [];

                        axios.get('/api/media/search?q=' + this.query).then((response) => {
                            this.results = response.data.data;
                        }).catch((error) => {
                            console.warn(error);
                        });

                    }

                }, 400)

            },

            getTracks() {
                axios.get(this.page_url).then((response) => {
                    this.tracks = response.data.data;
                    this.current_page = response.data.current_page;
                    this.last_page = response.data.last_page;
                    this.prev_page_url = response.data.prev_page_url;
                    this.next_page_url = response.data.next_page_url;
                    this.total = response.data.total;
                }).catch((error) => {
                    console.warn(error);
                });
            },

            addtrack(track) {
                this.results = [];
                this.query = '';
                const app = this;
                var post = {
                    'game_id': this.game.id,
                    'provider_item_id': track.id,
                    'provider': 'deezer',
                    'artist_name': track.artist.name,
                    'track_name': track.title_short,
                    'artwork_url': track.album.cover_medium,
                    'preview_url': track.preview,
                    'filetype': 'mp3'
                };
                axios.post('/games/'+this.game.id+'/tracks', post).then((response) => {
                    app.tracks.push(response.data);
                }).catch((error) => {
                    console.warn(error);
                });
            },

            addPlaylist() {

                axios.get('/api/deezer/playlist?q=' + this.query).then((response) => {
                    this.playlist = response.data;
                    $('#playlistModal').modal('show');
                }).catch((error) => {
                    console.warn(error);
                });

            },

            storePlaylist() {
                this.spinner = true;
                var params = {
                    'game_id': this.game.id,
                    'provider': 'deezer',
                };
                let vm = this;
                axios.post('/api/deezer/store/playlist', {'tracks': this.playlist.tracks.data, 'params': params}).then((response) => {
                    this.spinner = false;
                    vm.tracks =response.data;
                    $('#playlistModal').modal('hide');
                }).catch((error) => {
                    console.warn(error);
                });
            },

            saveCustomAnwser(track) {
                console.log(track.custom_answer);
                axios.post('/tracks/'+track.id+'/save/custom/awnser', track).then((response) => {
                    console.log('Track updated');
                }).catch((error) => {
                    console.warn(error);
                });
            },

            deleteTrack(track) {
                axios.delete('/games/'+this.game.id+'/tracks/' + track.id).then((response) => {
                    this.tracks.splice(this.tracks.indexOf(track), 1);
                }).catch((error) => {
                    console.warn(error);
                });
            },

            deleteTracks() {
                axios.get('/games/'+this.game.id+'/tracks/delete').then((response) => {
                    this.tracks = [];
                }).catch((error) => {
                    console.warn(error);
                });
            }
        }
    }
</script>

<style scoped>

    .panel-footer {
        position: relative;
    }
    .result-list {
        position: absolute;
        z-index: 10;
        width: 100%;
    }
    .pagination {
        max-width: 100%;
        overflow: auto;
    }

</style>