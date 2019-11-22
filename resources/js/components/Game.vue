<template>

    <div>

      <header class="masthead bg-primary text-white text-center pb-2">
          <div class="container d-flex align-items-center flex-column">

            <!-- Masthead Heading -->
            <h1 class="masthead-heading text-uppercase mb-0">Blind-Test {{ game.title }}</h1>

            <!-- Icon Divider -->
            <div class="divider-custom divider-light">
              <div class="divider-custom-line"></div>
              <div class="divider-custom-icon">
                <i class="fas fa-star"></i>
              </div>
              <div class="divider-custom-line"></div>
            </div>

            <p class="masthead-subheading font-weight-light mb-0">{{ game.description }}</p>

          </div>
      </header>

      <private v-on:updateScore="score = $event" @updateAnswers="answers = $event" :game="game"></private>

      <section>
          <div v-if="answers.length > 0" class="container mt-4">
              <div class="row">
                <div class="col-md-12">
                  <button class="btn btn-success">Score : {{ score }} / {{ answers.length }}</button>
                </div>
              </div>
          </div>
          <div class="container">
              <answers class="mt-4" v-if="answers.length > 0" :answers="answers"></answers>
          </div>
      </section>


      <section class="page-section text-center mb-0">
        <div class="container">

          <!-- About Section Heading -->
          <h2 class="page-section-heading text-uppercase">Classement</h2>

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

        props:['game'],

        data() {
            return {
                answers: [],
                score: 0
            }
        },

        mounted() {

        },

        created() {

        },

        methods: {

            updatePodium() {
                console.log("Update score");
            }

        }

    }
</script>

<style scoped>

</style>