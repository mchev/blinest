<template>

    <div class="container-fluid h-100 custom-game">

        <div class="row h-100">

            <div v-if="$userId == game.user_id" class="col-md-3 col-lg-2 order-1 p-0 sidebar sidebar-left">

                <div class="sticky-top sticky-offset text-center">

                    <span @click="hideControlsSidebar" class="btn toggle-controls-sidebar badge badge-secondary p-2" title="Masquer le panneau d'animation"><i class="fas fa-chevron-left"></i> Animation</span>

                    <div class="sidebar-content">

                        <div class="p-2">

                            <Controls v-if="tracks" ref="controls" :game="game" :tracks="tracks" v-on:play:track="track = $event"></Controls>

                            <div v-if="!tracks" class="card mb-3">

                                <div class="card-body">

                                    <div class="form-group">
                                        <label>Nombre de titres</label>
                                        <input type="number" v-model="item.tracks_number" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <select v-model="item.random" class="form-control">
                                            <option value="0">Lecture dans l'ordre de la playlist</option>
                                            <option value="1">Lecture aléatoire</option>
                                        </select>
                                    </div>

                                    <button type="button" @click="fetchTracks" class="btn btn-success">Charger une nouvelle partie</button>

                                </div>

                            </div>

                            <button type="button" @click="editGame" class="btn btn-secondary btn-block mb-3">Modifier la playlist</button>

                            <div class="card mb-3">

                                <div class="card-body">

                                    <div class="form-group">
                                        <label>Mot de passe</label>
                                        <input type="text" v-model="game.password" @blur="updatePassword" class="form-control">
                                        <small class="form-text text-muted">Si vide, la partie est ouverte à tous.</small>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col order-2 p-0 bg-dark" id="main">

                <header class="row text-white text-center my-5">

                    <div class="col-md-12">


                        <span class="badge badge-warning">Partie privée v2.0-beta : <a :href="'https://blinest.com/parties/' + game.slug">https://blinest.com/parties/{{ game.slug }}</a></span>

                        <h1 class="masthead-heading text-uppercase mb-0">Blind-Test {{ game.title }}</h1>

                        <p class="masthead-subheading font-weight-light mb-5">{{ game.description }}</p>

                        <game-player @score="countScore" :track="track" :game="game" v-on:track:end="endTrack" :users="users"></game-player>


                    </div>

                </header>

                <div class="container">

                    <div class="row">

                        <div class="col-md-6">

                            <div class="card">

                                <div class="card-header">
                                    <h5>Tu viens d'écouter <span v-if="answers.length > 0" class="float-right">{{ answers.length }} / {{ tracks.length }}</span></h5>
                                </div>

                                <div class="card-body p-0 card-multiplayer">
                                    <game-answers v-if="answers.length > 0" :answers="answers"></game-answers>
                                </div>

                            </div>

                        </div>

                        <div class="col-md-6">

                          <game-scores v-if="launched && initscore" :game="game" :track="track" :users="users"></game-scores>

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-md-3 col-lg-2 order-3 p-0 sidebar sidebar-right">

                <div class="sticky-top sticky-offset h-100">

                    <Chat :game="game"></Chat>

                </div>

            </div>

        </div>


        <div v-if="$userId == game.user_id" class="modal fade" id="editGame" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <game-edit v-if="showEditGame" :game="game"></game-edit>
                    </div>
                </div>
            </div>
        </div>



        <!-- START MODAL -->
        <div class="modal fade" id="startModal" tabindex="-1" role="dialog">
              <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                      <div class="modal-body text-center">
                          <h2>{{ game.title }}</h2>
                          <button v-if="waiting" class="btn btn-success btn-lg">
                              En attente du prochain morceau...
                          </button>
                          <p v-if="waiting"><small>Patience, le son arrive dans moins de 30s...</small></p>
                          <button v-else class="btn btn-success btn-lg" @click="init()">
                              <i class="pr-2 fas fa-play"></i> Rejoindre la partie
                          </button>
                          <loader v-if="waiting"></loader>
                      </div>
                  </div>
              </div>
        </div>


        <!-- FINNISH MODAL -->
        <div class="modal fade" id="finnishModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="card-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                  <div class="modal-body py-2 text-center">
                    <h2 class="p-0">Fin de partie</h2>
                    <small>Tout le monde lève les mains!</small>
                    <finnish v-if="finnish" :game="game" :users="users" class="mt-4"></finnish>
                  </div>
              </div>
            </div>
        </div>

    </div>

</template>

<script>

    import Controls from './Controls'
    import Chat from './Chat'

    export default {

        name:"customGame",

        components: {
            Controls,
            Chat
        },

        props:['game'],

        data() {
            return {
                item: this.game,
                tracks: false,
                showEditGame: false,

                answers: [],
                score: 0,
                users: null,
                track: null,
                waiting: false,
                launched: false,
                finnish: false,
                darkmode: this.game.darkmode,
                initscore: false,
            }
        },

        mounted() {
            $("#startModal").modal('show');
        },

        methods: {

            init() {

              this.waiting = true;
              $("#startModal").modal('hide');
              
              this.listen();

              // Get the current user
              axios.get('/user').then((response) => {

                this.game.currentUser = response.data;
                this.launched = true;

              });

            },

            listen() {

              Echo.join('game-' + this.game.id)
                .here((users) => {
                  this.users = users;
                  this.usersCount = this.users.length;
                })
                .joining((user) => {
                  this.users.push(user);
                  this.usersCount = this.users.length;
                })
                .leaving((user) => {
                  this.users.splice(this.users.findIndex(f => f.id === user.id), 1);
                  this.usersCount = this.users.length;
                });

              Echo.channel('game-' + this.game.id)
                .listen('PlayTrack', (data) => {
                  this.playTrack(data);
                })
                .listen('EndTrack', (data) => {
                  this.endTrack();
                })
                .listen('NewGame', (data) => {
                  this.newGame();
                })
                .listen('EndGame', (data) => {
                  this.endGame();
                });

              // Scores
              Echo.private('game-' + this.game.id)
                .listenForWhisper('score', (data) => {
                  if(this.users.findIndex(f => f.id === data.id) !== -1) {
                    this.$set(this.users, this.users.findIndex(f => f.id === data.id), data);
                  } else {
                    this.users.push(data);
                  }
                });

            },

            playTrack(data) {
              $("#startModal").modal('hide');
              this.track = data.track;
              this.waiting = false;
            },

            endTrack() {
              if (this.track) {
                this.answers.unshift(this.track);
                this.track = null;
              } else {
                this.waiting = true;
              }
              if (this.$userId == this.game.user_id) {
                this.$refs.controls.next();
              }
            },

            newGame() {
              this.finnish = false;
              $("#finnishModal").modal('hide');
              this.answers = [];
              this.user.score = {};
              this.score = 0;
              this.track = null;
              this.waiting = true;
            },

            endGame() {
              this.finnish = true;
              $("#startModal").modal('hide');
              $("#finnishModal").modal('show');
              this.track = null;
              this.tracks = false;

              // Save score
              if (this.score > 0) {
                  axios.post('/games/' + this.game.id + '/score', {score: this.score}).then((response) => {
                      //console.log(response.data);
                  }).catch((error) => {
                      console.warn(error);
                  });
              }
          
            },

            countScore(event) {

              this.track.score = event;

              this.score = 0;

              for (var i = 0; i <  this.answers.length; i++) {
                  this.score += this.answers[i].score.track;
                  this.score += this.answers[i].score.artist;
                  this.score += this.answers[i].score.custom;
                  this.score += this.answers[i].score.bonus;
                  this.score += (this.answers[i].score.faster_bonus) ? this.answers[i].score.faster_bonus : 0;
              };

              if (this.track) {
                  this.score += this.track.score.track;
                  this.score += this.track.score.artist;
                  this.score += this.track.score.custom;
                  this.score += this.track.score.bonus;
                  this.score += (this.track.score.faster_bonus) ? this.track.score.faster_bonus : 0;
              }


              // Faster typer
              if(this.track.score.artist_time !== 0 && this.track.score.track_time !== 0) {
                this.track.score.total_time = (this.track.score.artist_time > this.track.score.track_time) ? this.track.score.artist_time :  this.track.score.track_time;
              }

              if(this.track.score.custom_time) {
                this.track.score.total_time = this.track.score.custom_time;
              }


              if (this.track.score.total_time !== 0 && !this.track.score.faster_bonus) {

                switch (this.countFaster()) {
                  case 0:
                    this.track.score.faster_num = 1;
                    this.track.score.faster_bonus = 0.5;
                    this.score += this.track.score.faster_bonus;
                    break;
                  case 1:
                    this.track.score.faster_num = 2;
                    this.track.score.faster_bonus = 0.5;
                    this.score += this.track.score.faster_bonus;
                    break;
                  case 2:
                    this.track.score.faster_num = 3;
                    this.track.score.faster_bonus = 0.5;
                    this.score += this.track.score.faster_bonus;
                    break;

                }

              }

              this.track.score.total = this.score;

              this.game.currentUser.score = this.track.score;

              Vue.set(this.users, this.users.findIndex(f => f.id === this.game.currentUser.id), this.game.currentUser);

              this.initscore = true;

              Echo.private('game-' + this.game.id)
                .whisper('score', this.game.currentUser);


            },

            countFaster() {

              var count = 0;
              for(let i=0; i<this.users.length; i++)
              if( this.users[i].score && this.users[i].score.faster_bonus )
                count++;
              return count;
                    
            },

            editGame() {
                this.showEditGame = true;
                $("#editGame").modal('show');
            },

            hideControlsSidebar() {
                $('.sidebar.sidebar-left').toggleClass('hide');
            },

            fetchTracks() {
                axios.post('/partie/privee/' + this.game.id + '/fetch', this.game).then((response) => {
                    this.tracks = response.data;
                    this.$emit('update:tracks', this.tracks);
                }).catch((error) => {
                    console.warn(error);
                });
            },

            copyUrl() {
                url = $('#gameUrl').val();
                url.select();
                document.execCommand('copy');
            },

            updatePassword() {
                axios.post('/partie/privee/' + this.game.id + '/password', {'password': this.game.password});
            }

        },

        computed: {

          orderedUsers: function () {
            return _.orderBy(this.users, 'score.total', 'desc')
          }

        }

    };

</script>

<style>

    .sticky-top {
        z-index: 2;
    }

    .toggle-controls-sidebar {
        position: absolute;
        left: 100%;
        top: 0;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border: 1px solid rgba(255,255,255,0.5);
        border-left: 0;
    }

    .bg-dark {
        background-color: #636f7b !important;
    }

    .custom-game {
        overflow-x: hidden;
    }

    .sidebar {
        background: #61617d;
        transition: all .25s ease;
        height: calc(100vh - 72px);
    }

    .sidebar-header {
        border-bottom: 1px solid rgba(255,255,255,0.5);
    }

    .sidebar-content {
        overflow-x: hidden;
        overflow-y: auto;
    }

    .sidebar.hide {
        max-width: 0%;
    }
    .sidebar.hide .sidebar-content {
        display: none;
    }

    .sidebar-left {
        border-right: 1px solid rgba(255,255,255,0.5);
    }
    .sidebar-right {
        border-left: 1px solid rgba(255,255,255,0.5);
    }

    .card {
        background-color: rgba(255,255,255,0.8);
    }

    hr {
        border-top: 1px solid #FFF;
    }

</style>