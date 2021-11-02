<template>
    <div>

        <div class="card">
            <div class="card-header bg-secondary text-white">Gestion des extraits</div>

            <div class="card-body">

                <div class="input-group mb-4">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">Ajout depuis la bibliothèque</span>
                    </div>
                    <select v-model="origin" class="form-control">
                        <option value="deezer" selected="selected">Deezer</option>
                        <option value="playlist">Playlist Deezer</option>
                        <option value="spotify">Spotify</option>
                        <option value="spotifyplaylist">Playlist Spotify</option>
                        <option value="itunes">Apple music</option>
                    </select>
                    <input v-if="origin == 'deezer'" type="search" v-model="query" @keyup="searchFromDeezer" class="form-control" style="width:50%" placeholder="Rechercher sur Deezer">
                    <input v-if="origin == 'spotify'" type="search" v-model="query" @keyup="searchFromSpotify" class="form-control" style="width:50%" placeholder="Rechercher sur Spotify">
                    <input v-if="origin == 'itunes'" type="search" v-model="query" @keyup="searchFromItunes" class="form-control" style="width:50%" placeholder="Rechercher sur Apple music">
                    <input v-if="origin == 'playlist'" type="search" v-model="query" @keyup="searchFromDeezer" class="form-control" style="width:50%" placeholder="ID de la playlist">
                    <button v-if="origin == 'playlist'" type="button" @click="addPlaylist()" class="btn btn-success">Valider</button>
                    <input v-if="origin == 'spotifyplaylist'" type="search" v-model="query" @keyup="searchFromDeezer" class="form-control" style="width:50%" placeholder="ID de la playlist">
                    <button v-if="origin == 'spotifyplaylist'" type="button" @click="addSpotifyPlaylist()" class="btn btn-success">Valider</button>
                </div>

                <div class="panel-footer" v-if="results.length">
                    <ul class="list-group result-list text-dark">
                        <li class="list-group-item text-left" v-for="result in results">
                            <div class="row w-100">
                                <div class="col-auto">
                                    <audio controls preload="none" :src="result.preview" style="width:50px"></audio>
                                </div>
                                <div class="col">
                                    {{ result.artist.name }} - {{ result.title }}
                                </div>
                                <div class="col-auto">
                                    <button @click="addtrack(result)" class="btn btn-sm btn-success">Ajouter</button>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                <hr>

                <div class="row mb-3">

                    <div class="col-md-3">
                        <input type="search" @keyup="searchTracks()" v-model="search" class="form-control mb-2" placeholder="Rechercher dans les extraits">
                    </div>

                    <div class="col-md-6 text-center">
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

<!--
                    <div class="col-md-2">
                        <button v-if="tracks.length" class="btn btn-danger" @click="deleteTracks()">Tout supprimer</button>
                    </div>
-->

                    <div class="col-md-3 text-right">
                        <button class="btn btn-info">{{ tracks.length }} / {{ total }}</button>
                        <a :href="'/games/' + game.id + '/tracks/export'" class="btn btn-primary"><i class="far fa-file-excel"></i> XLS</a>
                    </div>

                </div>

                <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">
                                <div class="pointer" @click="sort('artist_name')">
                                    Artiste
                                    <i v-if="currentSort === 'artist_name' && currentSortDir === 'desc'" class="fas fa-sort-up"></i>
                                    <i v-if="currentSort === 'artist_name' && currentSortDir === 'asc'" class="fas fa-sort-down"></i>
                                </div>
                            </th>
                            <th scope="col">Acronyme</th>
                            <th scope="col">
                                <div class="pointer" @click="sort('track_name')">
                                    Titre
                                    <i v-if="currentSort === 'track_name' && currentSortDir === 'desc'" class="fas fa-sort-up"></i>
                                    <i v-if="currentSort === 'track_name' && currentSortDir === 'asc'" class="fas fa-sort-down"></i>
                                </div>
                            </th>
                            <th scope="col">Aperçu</th>
                            <th scope="col">
                                <div class="pointer" @click="sort('custom_answer')">
                                    Réponse personnalisée
                                    <i v-if="currentSort === 'custom_answer' && currentSortDir === 'desc'" class="fas fa-sort-up"></i>
                                    <i v-if="currentSort === 'custom_answer' && currentSortDir === 'asc'" class="fas fa-sort-down"></i>
                                </div>
                            </th>
                            <th width="8%" scope="col">
                                <div class="pointer" @click="sort('down_rate')">
                                    <i class="fas fa-thumbs-down"></i>
                                    <i v-if="currentSort === 'down_rate' && currentSortDir === 'desc'" class="fas fa-sort-up"></i>
                                    <i v-if="currentSort === 'down_rate' && currentSortDir === 'asc'" class="fas fa-sort-down"></i>
                                </div>
                            </th>
                            <th width="10%" scope="col">
                                <div class="pointer" @click="sort('created_at')">
                                    Ajouté le
                                    <i v-if="currentSort === 'created_at' && currentSortDir === 'desc'" class="fas fa-sort-up"></i>
                                    <i v-if="currentSort === 'created_at' && currentSortDir === 'asc'" class="fas fa-sort-down"></i>
                                </div>
                            </th>
                            <th scope="col"></th>
                            <th scope="col" class="text-right"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="track in tracks">
                            <td><input type="text" class="form-control" @blur="updateTrack(track)" v-model="track.artist_name"></td>
                            <td><input type="text" class="form-control" @blur="updateTrack(track)" v-model="track.acronyme"></td>
                            <td><input type="text" class="form-control" @blur="updateTrack(track)" v-model="track.track_name"></td>
                            <td>
                                <audio controls preload="none" :src="track.preview_url" style="width:50px"></audio>
                            </td>
                            <td><input type="text" class="form-control" @blur="updateTrack(track)" v-model="track.custom_answer"></td>
                            <td><input type="text" class="form-control" @blur="updateTrack(track)" v-model="track.down_rate"></td>
                            <td>{{ track.created_at | moment("DD/MM/YY")}}</td>
                            <td><img :src="'/img/' + track.provider + '.png'"></td>
                            <td><button @click="deleteTrack(track)" class="btn text-danger"><i class="far fa-trash-alt"></i></button></td>
                        </tr>
                    </tbody>
                </table>
                </div>

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
              <div v-if="playlist.title" class="modal-body text-center p-1">
                <h2>{{ playlist.title }}</h2>
                <p>{{ playlist.nb_tracks }} pistes</p>
                <img class="img-fluid" :src="playlist.picture_medium">
              </div>
              <div class="modal-footer mt-4">
                <button type="button" class="btn btn-secondary" @click="playlist == null" data-dismiss="modal">Fermer</button>                
                <button type="button" @click="storePlaylist()" class="btn btn-primary">
                    <div v-if="spinner" class="spinner-border" role="status">
                      <span class="sr-only">Loading...</span>
                    </div>
                    Importer
                </button>
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
                currentSort:'down_rate',
                currentSortDir:'desc',
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

            sort:function(s) {
                //if s == current sort, reverse
                if(s === this.currentSort) {
                    this.currentSortDir = this.currentSortDir==='asc'?'desc':'asc';
                }
                this.currentSort = s;

                if (this.search.length) {
                    this.page_url = '/games/' + this.game.id + '/tracks?page=' + this.current_page + '&order=' + this.currentSort + '&direction=' + this.currentSortDir;
                } else {
                    this.page_url = '/games/' + this.game.id + '/tracks/search?page=' + this.current_page + '&q=' + this.search + '&order=' + this.currentSort + '&direction=' + this.currentSortDir;
                }

                this.getTracks();
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
                        this.page_url = '/games/' + this.game.id + '/tracks?page=' + direction + '&order=' + this.currentSort + '&direction=' + this.currentSortDir;
                    } else {
                        this.page_url = '/games/' + this.game.id + '/tracks/search?page=' + direction + '&q=' + this.search + '&order=' + this.currentSort + '&direction=' + this.currentSortDir;
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

                        axios.get('/media/search?q=' + this.query).then((response) => {
                            this.results = response.data.data;
                        }).catch((error) => {
                            console.warn(error);
                        });

                    }

                }, 400)

            },

            searchFromItunes() {

                this.results = [];

                clearTimeout(this.debounce);

                this.debounce = setTimeout(() => {

                    if (this.query.length > 2) {

                        this.errors = [];

                        axios.get('/api/itunes/search?q=' + this.query).then((response) => {
                            this.results = response.data.results.map(function(track) {
                                return {
                                    id: track.trackId,
                                    title: track.trackName,
                                    title_short : track.trackName,
                                    provider: 'itunes',
                                    artist: {
                                        name: track.artistName,
                                    },
                                    album: {
                                        cover_medium: track.artworkUrl100
                                    },
                                    preview: track.previewUrl
                                }
                            });
                        }).catch((error) => {
                            console.warn(error);
                        });

                    }

                }, 400)

            },

            searchFromSpotify() {

                this.results = [];

                clearTimeout(this.debounce);

                this.debounce = setTimeout(() => {

                    if (this.query.length > 2) {

                        this.errors = [];

                        axios.get('/spotify/search?q=' + this.query).then((response) => {

                            var spotify = response.data.tracks.items;

                            spotify = spotify.filter(obj => obj.preview_url !== null);

                            this.results = spotify.map(function(obj) {
                                return {
                                    id: obj.id,
                                    title: obj.name,
                                    title_short : obj.name,
                                    provider: 'spotify',
                                    artist: {
                                        name: obj.artists[0].name,
                                    },
                                    album: {
                                        cover_medium: obj.album.images[1].url
                                    },
                                    preview: obj.preview_url
                                }
                            });

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
                    'provider': (track.provider) ? track.provider : 'deezer',
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

                axios.get('/deezer/playlist?q=' + this.query).then((response) => {
                    this.playlist = response.data;
                    this.playlist.provider = 'deezer';
                    $('#playlistModal').modal('show');
                }).catch((error) => {
                    console.warn(error);
                });

            },

            addSpotifyPlaylist() {
                axios.get('/spotify/playlist?q=' + this.query).then((response) => {

                    var spotify = response.data;

                    this.playlist = {
                        id: spotify.id,
                        title: spotify.name,
                        picture_medium: spotify.images[0].url,
                        nb_tracks: spotify.tracks.total,
                        tracks: {
                            data: spotify.tracks.items
                        },
                        provider: 'spotify'
                    }

                    $('#playlistModal').modal('show');

                }).catch((error) => {
                    console.warn(error);
                });
            },

            storePlaylist() {
                this.spinner = true;
                var params = {
                    'game_id': this.game.id,
                    'provider': this.playlist.provider
                };
                let vm = this;
                axios.post('/'+this.playlist.provider+'/store/playlist', {'playlist_id': this.playlist.id, 'params': params}).then((response) => {
                    if(response.data.success) {
                        setTimeout(function() {
                            $('#playlistModal').modal('hide');
                            vm.spinner = false;
                            vm.getTracks();
                        }, 5000);
                    } else {
                        $('#playlistModal').modal('hide');
                        alert("Une erreur à eu lieu lors de l'importation");
                        this.spinner = false;
                    }
                }).catch((error) => {
                    console.warn(error);
                    $('#playlistModal').modal('hide');
                    alert("L'importation s'est terminée sans ajouter tous les morceaux.");
                    this.spinner = false;
                });
            },

            updateTrack(track) {
                axios.patch('/tracks/' + track.id, track).then(response => {
                    console.log('Track updated');
                })
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
    };

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

    .thead-dark i {
        vertical-align: text-top;
    }

</style>