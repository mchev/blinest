<template>

    <table class="table table-hover">

      <tbody>
        <tr v-for="(user, index) in orderedUsers" :key="user.user_name">
          <td>{{ index + 1 }}</td>
          <td>{{ user.user_name }}</td>
          <td class="text-right"><span class="badge badge-primary badge-pill">{{ user.score }} pts</span></td>
        </tr>
      </tbody>

    </table>

</template>

<script>

    export default {

        name:"ranking",

        props:['game', 'users'],

        data() {
            return {
              //users: []
            }
        },

        mounted () {
          this.listenForNewScores();
        },

        created() {
            
        },

        methods: {

          listenForNewScores() {

            Echo.channel('newUserScore-' + this.game.id)
              .listen('UpdateScore', (data) => {

                if (this.users.findIndex(x => x.user_name === data.update.user_name) !== -1 ) {
                  var index = this.users.findIndex(x => x.user_name === data.update.user_name);
                  this.users.splice(index, 1);
                }

                this.users.push(data.update);                
                
              })
          }

        },

        computed: {

          orderedUsers: function () {
            return _.orderBy(this.users, 'score', 'desc')
          }

        }

    };

</script>