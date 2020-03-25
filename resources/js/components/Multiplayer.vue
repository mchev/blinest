<template>

    <div>

      <header class="masthead bg-primary text-white text-center pb-2">
          <div class="container d-flex align-items-center flex-column">

            <!-- Masthead Heading -->
            <h1 class="masthead-heading text-uppercase mb-0">Blind-Test {{ game.title }}</h1>

            <p class="masthead-subheading font-weight-light mb-0">{{ game.description }}</p>

          </div>
      </header>

      <play @updateScore="rank($event)" @updateUsers="users = $event" @updateAnswers="answers = $event" :game="game"></play>

        <section>

              <div class="row mx-0 mt-4">

                <div class="col-lg-5">

                  <div class="card mb-2">

                    <div class="card-header bg-secondary text-white">
                      <h5>Tu viens d'écouter <span v-if="answers[0]" class="float-right">{{ answers[0].counter }} / {{ answers[0].total }}</span></h5>
                    </div>

                    <div class="card-body p-0 card-multiplayer">
                      <answers v-if="answers.length > 0" :answers="answers"></answers>
                    </div>

                  </div>
                  
                </div>


                <div class="col-lg-3">

                  <div class="card mb-2">

                    <div class="card-header bg-secondary text-white">
                      <h5>Classement</h5>
                    </div>

                    <div class="card-body p-0 card-multiplayer">
                      <ranking :game="game" :me="user" :users="users"></ranking>
                    </div>

                  </div>

                </div>


                <div class="col-lg-4">

                  <div class="card mb-2">

                    <div class="card-header bg-secondary text-white">
                      <h5>Salon {{ game.title }}</h5>
                    </div>

                    <div class="card-body">
                      <chat :game="game" :user="user"></chat>
                    </div>

                  </div>

                </div>

              </div>

        </section>



      <section class="page-section text-center mb-0">
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

          <podium :game="game"></podium>

        </div>
      </section>

    </div>

</template>

<script>

    export default {

        props:['game', 'user'],

        data() {
            return {
                answers: [],
                users: [],
                scores: []
            }
        },

        mounted() {

        },

        created() {

        },

        methods: {

          rank(score) {

            this.scores[0] = {
              'username': this.user.name,
              'score': score
            };

          }

        }

    }
</script>

<style scoped>

</style>