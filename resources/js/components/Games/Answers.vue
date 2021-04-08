<template>

    <table v-if="results" class="table align-middle">

        <tbody name="table-row" is="transition-group">

            <tr v-for="answer in results" :key="answer.id">

                <td style="width:20%"><img style="max-height:100px" class="img-fluid" :src="answer.artwork_url"></td>

                <td>

                    <template v-if="answer.custom_answer">
                        <strong>
                            <a target="_blank" :href="'https://www.deezer.com/fr/track/' + answer.provider_item_id" title="Ecouter sur Deezer"><img src="/img/deezer.png" class="deezer_icon">{{ answer.custom_answer }}</a> 
                            <i v-if="answer.bonus_score !== 0" title="Bonus rapidité" class="text-warning fas fa-fire"></i>
                        </strong><br>
                        {{ answer.artist_name }} - {{ answer.track_name }}
                    </template>
                    <template v-else>
                        <strong><a target="_blank" :href="'https://www.deezer.com/fr/track/' + answer.provider_item_id" title="Écouter sur Deezer"><img src="/img/deezer.png" class="deezer_icon">{{ answer.artist_name }}</a></strong><br>{{ answer.track_name }}
                    </template>
                    <br>
                    <template v-if="answer.score">
                        <button v-if="answer.score.bonus" type="button" class="btn btn-sm p-0"><i class="text-warning fas fa-fire"></i></button>
                        <button v-if="answer.score.faster_bonus" type="button" class="btn btn-sm p-0 text-success">{{ answer.score.faster_num }} <i class="far fa-keyboard" ></i></button>
                        <button v-if="answer.score.custom" type="button" class="btn btn-sm p-0"><i class="fas fa-film"></i></button>
                        <button v-if="answer.score.artist" type="button" class="btn btn-sm p-0"><i class="fas fa-microphone"></i></button>
                        <button v-if="answer.score.track" type="button" class="btn btn-sm p-0"><i class="fas fa-music"></i></button>
                    </template>

                </td>

                <td class="text-right">
                    <i v-if="!ids.includes(answer.id)" @click="rateUp(answer.id)" class="text-success fas fa-thumbs-up pointer" title="Génial!"></i>
                    <i v-if="!ids.includes(answer.id)" @click="rateDown(answer.id)" class="text-danger fas fa-thumbs-down pointer" title="Ce morceau n'a rien à faire ici!"></i>
                </td>

            </tr>

        </tbody>

    </table>

</template>

<script>

    export default {

        name:"answers",

        props:['answers'],

        data() {
            return {
                ids: [],
                results: this.answers,
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

    .deezer_icon {
        height: 22px;
        vertical-align: sub;
        width: auto;
    }

    .table-row-item {

    }
    
    .table-row-enter-active, .table-row-leave-active {
        transition: all .5s ease-in-out;
    }

    .table-row-enter, .table-row-leave-to /* .list-leave-active below version 2.1.8 */ {
        opacity: 0;
        transform: translateY(-30px);
    }

</style>