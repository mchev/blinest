<template>

    <table class="table align-middle">

      <tr v-for="answer in answers">

        <td style="width:20%"><img style="max-height:100px" class="img-fluid" :src="answer.artwork_url"></td>

        <td>
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
        </td>

        <td class="text-right">
            <i v-if="!ids.includes(answer.id)" @click="rateUp(answer.id)" class="text-success fas fa-thumbs-up pointer" title="Génial!"></i>
            <i v-if="!ids.includes(answer.id)" @click="rateDown(answer.id)" class="text-danger fas fa-thumbs-down pointer" title="Ce morceau n'a rien à faire ici!"></i>
        </td>

      </tr>

    </table>

</template>

<script>

    export default {

        name:"answers",

        props:['answers'],

        data() {
            return {
                ids: [],
            }
        },

        methods: {

            rateDown(id) {
                axios.post('/tracks/' + id + '/rate/down').then((response) => {
                  //console.log("Le titre à été signalé comme inapproprié.")
                  this.ids.push(id);
                }).catch((error) => {
                    console.warn(error);
                });
            },

            rateUp(id) {
                axios.post('/tracks/' + id + '/rate/up').then((response) => {
                  //console.log("Le titre à été signalé comme inapproprié.")
                  this.ids.push(id);
                }).catch((error) => {
                    console.warn(error);
                });
            }

        }

    };

</script>

<style scoped>

  .pointer {
    cursor: pointer;
  }

</style>