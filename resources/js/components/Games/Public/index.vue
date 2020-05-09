<template>

    <div class="public-game bg-primary" :class="{darkmode: game.darkmode}">

      <header class="masthead text-white text-center pb-2">
          <div class="container d-flex align-items-center flex-column">

            <!-- Masthead Heading -->
            <h1>Blind-Test {{ game.title }}</h1>

            <p>{{ game.description }}</p>

          </div>
      </header>

      <div v-if="launched" class="public-game-section d-flex flex-column">

        <game-player @score="countScore" :track="track" :game="game" :users="users"></game-player>

        <section class="container-fluid d-flex flex-grow-1 pb-5">

              <div class="row mt-4 d-flex flex-grow-1 justify-content-center">

                <div class="col-md-4">

                  <div class="card mb-2">

                    <div class="card-header">
                      <h5>Tu viens d'écouter <span v-if="answers[0]" class="float-right">{{ answers[0].counter }} / {{ answers[0].total }}</span></h5>
                    </div>

                    <div class="card-body p-0 card-multiplayer">
                      <game-answers v-if="answers.length > 0" :answers="answers"></game-answers>
                    </div>

                  </div>
                  
                </div>


                <div class="col-md-4">

                  <game-scores v-if="launched && initscore" :game="game" :track="track" :users="users"></game-scores>

                </div>


                <div v-if="$userId" class="col-md-4">

                  <div class="card mb-2">

                    <div class="card-header">
                      <h5>Salon {{ game.title }}</h5>
                    </div>

                    <div  class="card-body d-flex">
                      <Chat :game="game"></Chat>
                    </div>

                  </div>

                </div>

              </div>

        </section>

      </div>

      <section class="page-section bg-white text-center mb-0">
        <div class="container">

          <!-- About Section Heading -->
          <h2 class="page-section-heading text-uppercase">Podium</h2>

          <!-- Icon Divider -->
          <div class="divider-custom">
            <div class="divider-custom-line"></div>
            <div class="divider-custom-icon">
              <i class="fas fa-trophy"></i>
            </div>
            <div class="divider-custom-line"></div>
          </div>

          <h3>Le top 5 de {{ game.title }}</h3>

          <game-podiums :game="game"></game-podiums>

        </div>
      </section>

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

  import Chat from './Chat'

    export default {

      name: "publicGame",

      props:['game'],

      components: {
          Chat
      },

      data() {
          return {
              answers: [],
              score: 0,
              users: null,
              track: null,
              waiting: false,
              launched: false,
              finnish: false,
              initscore: false,
          }
      },

      mounted() {

        $("#startModal").modal('show');

      },

      methods: {

        init() {

          this.waiting = true;
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
              console.log(users);
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
        },

        newGame() {
          this.finnish = false;
          $("#finnishModal").modal('hide');
          this.answers = [];
          this.track.score = {};
          this.score = 0;
          this.track = null;
          this.waiting = true;
        },

        endGame() {
          
          this.finnish = true;
          $("#startModal").modal('hide');
          $("#finnishModal").modal('show');
          this.track = null;

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
                
        }


      },

      computed: {

        orderedUsers: function () {
          return _.orderBy(this.users, 'score.total', 'desc')
        },

      }

    };

</script>