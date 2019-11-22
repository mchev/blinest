<template>

    <div class="row mt-4">

        <div v-if="month.length" class="col-md-6">
            <h4>Joueurs du mois</h4>
          <ul class="list-group">
              <li v-for="(user, index) in month" :key="user.id" class="list-group-item d-flex justify-content-between align-items-center">
                <div class="row w-100">

                    <div class="col-2 text-left">
                        {{ index + 1 }}.
                    </div>

                    <div class="col-6 text-left">
                        <a :href="'/profils/' + user.user.name">{{ user.user.name }}</a>
                    </div>

                    <div class="col-4 text-warning text-right">
                        {{ user.score }} points
                    </div>

                </div>
              </li>
          </ul>
        </div>

        
        <div class="col-md-6">
            <h4>Joueurs de l'année</h4>
          <ul class="list-group">
              <li v-for="(user, index) in alltime" :key="user.id" class="list-group-item d-flex justify-content-between align-items-center">
                <div class="row w-100">

                    <div class="col-2 text-left">
                        {{ index + 1 }}.
                    </div>

                    <div class="col-6 text-left">
                        <a :href="'/profils/' + user.user.name">{{ user.user.name }}</a>
                    </div>

                    <div class="col-4 text-warning text-right">
                        {{ user.score }} points
                    </div>

                </div>
              </li>
          </ul>
        </div>

        
    </div>

</template>

<script>
    export default {

        props:['game'],

        data() {
            return {
                alltime: {},
                month: {}
            }
        },
        mounted() {
            
        },
        created() {

            this.getPodium();

        },

        methods: {
            getPodium() {
                axios.get('/games/' + this.game.id + '/podium').then((response) => {
                    this.alltime = response.data.alltime;
                    this.month = response.data.month;
                }).catch((error) => {
                    console.warn(error);
                });
            }
        }

    }
</script>

<style scoped>

</style>