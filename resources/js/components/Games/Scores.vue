<template>

  <div class="card mb-2">

    <div class="card-header">
      <h5>Scores ({{ userList.length }} joueur.se.s)</h5>
    </div>

    <div class="card-body p-0 card-multiplayer">

      <table v-if="userList" class="table table-hover">

         <tbody name="table-row" is="transition-group">
          <tr v-for="(user, index) in userList" :key="user.id" v-if="user.score" :class="{'bg-user': userCpd.id === user.id}">
            <td>{{ index + 1 }}</td>
            <td>
              {{ user.name }}
              <br>
              <button title="Bonus rapidité" type="button" class="btn btn-sm p-0"><i class="text-gray fas fa-fire" :class="{ 'text-warning': user.score.bonus }"></i></button>
              <button title="Titre" v-if="track && track.custom_answer" type="button" class="btn btn-sm p-0"><i class="text-gray fas fa-film" :class="{ 'text-success': user.score.custom }"></i></button>
              <button title="Artiste" type="button" class="btn btn-sm p-0"><i class="text-gray fas fa-microphone" :class="{ 'text-success': user.score.artist }"></i></button>
              <button title="Titre" type="button" class="btn btn-sm p-0"><i class="text-gray fas fa-music" :class="{ 'text-success': user.score.track }"></i></button>
              <button title="Top 3 rapidité" type="button" class="btn btn-sm p-0 text-gray" :class="{ 'text-success': user.score.faster_num }">{{ user.score.faster_num }} <i class="far fa-keyboard" ></i></button>
            </td>
            <td class="text-right">
              <span class="badge badge-primary badge-pill">{{ user.score.total }} pts</span>
            </td>
          </tr>
        </tbody>

      </table>
      
    </div>

  </div>

</template>

<script>

    export default {

        name:"scores",

        props:['game', 'track', 'users'],

        data() {
            return {
              
            }
        },

        mounted() {

        },

        methods: {


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

  .bg-user {
    background-color: #28a7454f !important;
  }

  .text-gold {
    color: gold;
  }
  .text-silver {
    color: silver;
  }
  .text-bronze {
    color: brown;
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