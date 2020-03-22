<template>

    <div>

      <ul class="list-group">
        <li v-for="user in orderedUsers" class="list-group-item d-flex justify-content-between align-items-center">
          {{ user.user_name }}
          <span class="badge badge-primary badge-pill">{{ user.score }}</span>
        </li>
      </ul>

    </div>

</template>

<script>

    export default {

        name:"ranking",

        props:['game', 'me'],

        data() {
            return {
              users: []
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

    }
</script>

<style scoped>

</style>