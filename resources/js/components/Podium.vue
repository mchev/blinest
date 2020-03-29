<template>

    <div class="row mt-4">

        <div v-if="week.length" class="col-md-4">
            <h4>Top de la semaine</h4>
            <ul class="list-group">
                <li v-for="(user, index) in week" :key="user.id" class="list-group-item text-center">
                    <a :href="'/profils/' + user.user.name">{{ user.user.name }} <br>({{ user.score }} points)</a>
                </li>
            </ul>
        </div>

        <div v-if="month.length" class="col-md-4">
            <h4>Top du mois</h4>
            <ul class="list-group">
                <li v-for="(user, index) in month" :key="user.id" class="list-group-item text-center">
                    <a :href="'/profils/' + user.user.name">{{ user.user.name }} <br>({{ user.score }} points)</a>
                </li>
            </ul>
        </div>

        
        <div class="col-md-4">
            <h4>Tous les temps</h4>
            <ul class="list-group">
                <li v-for="(user, index) in alltime" :key="user.id" class="list-group-item text-center">
                    <a :href="'/profils/' + user.user.name">{{ user.user.name }} <br>({{ user.score }} points)</a>
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
                month: {},
                week: {}
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
                    this.week = response.data.week;
                }).catch((error) => {
                    console.warn(error);
                });
            }
        }

    };

</script>

<style scoped>

    .list-group .list-group-item:nth-of-type(1) {
        background:linear-gradient(to right, #BF953F, #FCF6BA, #B38728);
    }
    .list-group .list-group-item:nth-of-type(1) a {
        font-weight: bold;
    }
    .list-group .list-group-item:nth-of-type(2) {
        background:linear-gradient(to right, #D7D7D7, #FFF, #D7D7D7);
    }
    .list-group .list-group-item:nth-of-type(3) {
        background:linear-gradient(to right, #A77044, #FFF, #A77044);
    }


</style>