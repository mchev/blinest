<template>

   <div class="row">

        <div class="col-md-12">

            <div class="card card-sm">
                <div class="row d-flex align-items-center justify-content-center">

                    <div class="col-xs-12 col-md-5 text-center">
                      <h1 class="rating-num" v-if="game.currentUser.score">{{ game.currentUser.score.total }} pts</h1>
                      <i class="fas fa-user"></i> {{ game.currentUser.name }}
                    </div>

                    <div class="col-xs-12 col-md-7">

                      <ul class="list-group text-left">

                        <li v-for="(item,index) in userList" v-if="index <= 2" class="list-group-item border-0"><strong><i class="fas fa-star"></i> {{ index + 1  }} {{ item.name }} ({{ item.score.total }} pts)</strong></li>

                      </ul>

                    </div>

                </div>
            </div>

            <p v-if="!game.public" class="mt-5">L'animateur va probablement en relancer une petite...</p>
            <p v-else class="mt-5">Une nouvelle partie commence dans {{ countdown }} secondes!</p>

        </div>
    </div>

</template>

<script>

    export default {

        name:"finnish",

        props:['game', 'users'],

        data() {
            return {
              countdown: 0,
            }
        },

        mounted() {
          this.countdown = 15;
          this.countDownTimer();
        },

        methods: {

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

          userCpd() {
            return this.game.user;
          },

          userList() {
            return _.orderBy(this.users, 'score.total', 'desc')
          },

        },

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