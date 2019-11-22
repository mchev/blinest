<template>

    <div>

      <ul v-if="answers.length" class="list-group">
          <li v-for="answer in answers" class="list-group-item">
              <div class="row">
                  <div class="col-md-2"><img style="max-height:100px" class="img-fluid" :src="answer.artwork_url"></div>
                  <div class="col-md-10">
                      <div v-if="answer.custom_answer">
                          <strong>{{ answer.custom_answer }} <i v-if="answer.bonus_score !== 0" title="Bonus rapidité" class="text-warning fas fa-fire"></i> <span class="text-success" v-if="answer.custom_score == 1"><i class="fas fa-check"></i></span></strong><br>
                          {{ answer.artist_name }} - {{ answer.track_name }}<br>
                          <i>Bonus artiste : {{ answer.artist_score }} - Bonus titre : {{ answer.track_score }}</i>
                      </div>
                      <div v-if="!answer.custom_answer">
                          <strong>{{ answer.artist_name }}</strong>
                          <i v-if="answer.bonus_score !== 0" title="Bonus rapidité" class="text-warning fas fa-fire"></i>
                          <br>
                          {{ answer.track_name }}<br>
                          <i>Artiste : {{ answer.artist_score }} - Titre : {{ answer.track_score }}</i>
                      </div>
                      <i v-if="down_clicked !== answer.id" @click="rateDown(answer.id)" class="fas fa-thumbs-down float-right pointer" title="Signaler ce titre comme inapproprié"></i>
                  </div>
              </div>
          </li>
      </ul>

    </div>

</template>

<script>

    export default {

        name:"answers",

        props:['answers'],

        data() {
            return {
                down_clicked: false
            }
        },

        methods: {

            rateDown(id) {
                axios.post('/tracks/' + id + '/updatetrackrate').then((response) => {
                  console.log("Le titre à été signalé comme inapproprié.")
                  this.down_clicked = id;
                }).catch((error) => {
                    console.warn(error);
                });
            }

        }

    }
</script>

<style scoped>

  .pointer {
    cursor: pointer;
  }

</style>