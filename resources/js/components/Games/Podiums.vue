<template>

    <div>

        <div v-if="user_score" class="row my-4">
            <div class="col text-center">
                Mon score : {{ user_score }} points
            </div>
        </div>


        <div class="row mt-4 d-flex justify-content-center">

            <div v-if="week.length" class="col-md-4">
                <h4>Top de la semaine</h4>
                <ul class="list-group">
                    <li v-for="(user, index) in week" :key="user.id" class="list-group-item d-flex justify-content-start align-items-center text-left">
                        <span class="badge badge-secondary mr-4 badge-pill">{{ index + 1 }}</span>
                        <a :href="'/user/profil/' + user.user.id">{{ user.user.name }} <br>({{ user.score }} points)</a>
                    </li>
                </ul>
            </div>

            <div v-if="month.length" class="col-md-4">
                <h4>Top du mois</h4>
                <ul class="list-group">
                    <li v-for="(user, index) in month" :key="user.id" class="list-group-item d-flex justify-content-start align-items-center text-left">
                        <span class="badge badge-secondary mr-4 badge-pill">{{ index + 1 }}</span>
                        <a :href="'/user/profil/' + user.user.id">{{ user.user.name }} <br>({{ user.score }} points)</a>
                    </li>
                </ul>
            </div>

            
            <div class="col-md-4">
                <h4>Tous les temps</h4>
                <ul class="list-group">
                    <li v-for="(user, index) in alltime" :key="user.id" class="list-group-item d-flex justify-content-start align-items-center text-left">
                        <span class="badge badge-secondary mr-4 badge-pill">{{ index + 1 }}</span>
                        <a :href="'/user/profil/' + user.user.id">{{ user.user.name }} <br>({{ user.score }} points)</a>
                    </li>
                </ul>
            </div>

            
        </div>

    </div>

</template>

<script>

    export default {

        name: "podiums",

        props:['game'],

        data() {
            return {
                alltime: {},
                month: {},
                week: {},
                user_score: 0,
            }
        },

        mounted() {

            this.getPodium();

            let vm = this;
            
            Echo.channel('game-' + this.game.id)
                .listen('EndGame', (data) => {
                    setTimeout(function() { vm.getPodium(); }, 2000);
                });

        },

        methods: {
            getPodium() {
                axios.get('/games/' + this.game.id + '/podium').then((response) => {
                    this.alltime = response.data.alltime;
                    this.month = response.data.month;
                    this.week = response.data.week;
                    this.user_score = response.data.user_score;
                }).catch((error) => {
                    console.warn(error);
                });
            }
        }

    };

</script>

<style scoped>

    .list-group .list-group-item a {
        color: #2c3e50;
    }

    .list-group .list-group-item:nth-of-type(1) {
        background:#2ecc71;
    }
    .list-group .list-group-item:nth-of-type(1) a {
        font-weight: bold;
    }
    .list-group .list-group-item:nth-of-type(2) {
        background:#f1c40f;
    }
    .list-group .list-group-item:nth-of-type(3) {
        background:#e74c3c;
    }


</style>