<template>

  <div>

    <table class="table table-hover">

      <tbody>
        <tr v-for="(user, index) in orderedUsers" :key="user.user_name" :class="{'bg-success' : user.user_name === userScore.user_name}">
          <td>{{ index + 1 }}</td>
          <td>{{ user.user_name }}</td>
          <td class="text-right">            
            <span v-if="index === 0"><i class="text-gold fas fa-trophy"></i></span>
            <span v-if="index === 1"><i class="text-silver fas fa-trophy"></i></span>
            <span v-if="index === 2"><i class="text-bronze fas fa-trophy"></i></span>
          </td>
          <td class="text-right">
            <span class="badge badge-primary badge-pill">{{ user.score }} pts</span>
          </td>
        </tr>
      </tbody>

    </table>



    <!-- FINNISH MODAL -->
    <div class="modal fade" id="finnish" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title">La partie est terminée</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body py-1 text-center">

                <div v-if="userScore" class="alert alert-success mt-2">Mon score ({{ userScore.user_name }}) : {{ userScore.score }} points</div>

                <div v-if="users && podium" id="podium-box" class="row">
                    <div class="col-md-4 step-container m-0 p-0">
                        <div>
                            {{ podium[1].user_name }}<br>
                            ({{ podium[1].score }} points)
                        </div>
                        <div id="second-step" class="bg-secondary step centerBoth podium-number">
                            2
                        </div>
                    </div>
                    <div class="col-md-4 step-container m-0 p-0">
                        <div>
                            {{ podium[0].user_name }}<br>
                            ({{ podium[0].score }} points)
                        </div>
                        <div id="first-step" class="bg-secondary step centerBoth podium-number">
                            1
                        </div>
                    </div>
                    <div class="col-md-4 step-container m-0 p-0">
                        <div>
                            {{ podium[2].user_name }}<br>
                            ({{ podium[2].score }} points)
                        </div>
                        <div id="third-step" class="bg-secondary step centerBoth podium-number">
                            3
                        </div>
                    </div>
                </div>

                <h3 class="mt-2">Nouvelle partie dans</h3>
                <h1>{{ countdown }}</h1>
            </div>
            <div class="modal-footer">
              <a href="/" class="btn btn-primary">Rejoindre d'autres parties</a>
            </div>
          </div>
        </div>
    </div>



  </div>

</template>

<script>

    export default {

        name:"ranking",

        props:['game', 'users', 'userScore'],

        data() {
            return {
              countdown: 0,
              podium: false,
            }
        },

        mounted () {

          this.listenForNewScores();


          Echo.channel('endGame')
              .listen('EndGame', (data) => {
                this.endModal();
              })


        },

        created() {
            
        },

        methods: {

          listenForNewScores() {

            Echo.channel('newUserScore-' + this.game.id)
              .listen('UpdateScore', (data) => {

                if (this.users.findIndex(x => x.user_name === data.update.user_name) !== -1 ) {
                  var index = this.users.findIndex(x => x.user_name === data.update.user_name);
                  this.users.splice(index, 1);
                }

                this.users.push(data.update);                
                
              })
          },

          endModal() {

            this.countdown = 15;
            this.countDownTimer();

            if(this.users.length > 2) {

              var user1 = orderedUsers[0],
                  user2 = orderedUsers[1],
                  user3 = orderedUsers[2]

              this.podium = [
                user1, user2, user3
              ];

            } else {

              this.podium = false;

            }

            $('#finnish').modal('show');
          },

          countDownTimer() {
              if(this.countdown > 0) {
                  setTimeout(() => {
                      this.countdown -= 1
                      this.countDownTimer()
                  }, 1000)
              }
          },

        },

        computed: {

          orderedUsers: function () {
            return _.orderBy(this.users, 'score', 'desc')
          }

        }

    };

</script>

<style scoped>

  .text-gold {
    color: gold;
  }
  .text-silver {
    color: silver;
  }
  .text-bronze {
    color: brown;
  }

  #podium-box {
    margin: 0 auto;
    display: flex;
    height: 20vh;
  }

  .podium-number {
    font-weight: bold;
    font-size: 4em;
    color: white;
  }

  .step-container {
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  .step-container>div:first-child {
    margin-top: auto;
    text-align: center;
  }

  .step {
    text-align: center;
  }

  #first-step {
    height: 100%;
  }

  #second-step {
    height: 65%;
  }

  #third-step {
    height: 55%;
  }

</style>