<template>

  <div class="card">

    <div class="card-header bg-secondary text-white">
      Modérateurs
    </div>

    <div class="card-body">

      <div class="form-group">

        <label>Ajouter un modérateur</label>

        <div class="moderator-form">

          <input type="text" class="form-control" @keyup="search" v-model="query">

          <ul class="list-group result-list" v-if="results.length && query !== ''">
              <li class="list-group-item d-flex justify-content-between align-items-center" v-for="result in results">
                  {{ result.name }} ({{ result.email }})
                  <button @click="add(result)" class="btn btn-sm btn-success">Ajouter</button>
              </li>
          </ul>

        </div>

      </div>
    
      <ul class="list-group">
        <li v-for="moderator in moderators" class="list-group-item d-flex justify-content-between align-items-center">
          {{ moderator.name }}
          <button type="button" class="btn btn-danger btn-sm" @click="remove(moderator)">Retirer</button>
        </li>
      </ul>

    </div>

  </div>

</template>

<script>

    export default {

        name:"moderators",

        props:['game'],

        data() {
            return {
              query: '',
              results: {},
              moderators: this.game.moderators
            }
        },

        methods: {

          search() {

            if(this.query.length > 2) {
              axios.get('/admin/users/search?query=' + this.query).then((response) => {
                this.results = response.data;
              });
            }
          },

          add(user) {

              axios.post('/admin/users/'+user.id+'/role', {game_id: this.game.id}).then((response) => {
                this.moderators.push(user);
                this.query = '';
              });

          },

          remove(user) {

              axios.delete('/admin/users/'+user.id+'/game/'+this.game.id+'/role').then((response) => {
                this.moderators.splice(this.moderators.indexOf(user), 1);
              });

          },

        },

    };

</script>

<style>

  .moderator-form {
    position: relative;
  }

  .result-list {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    z-index: 10;
  }

</style>