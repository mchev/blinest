<template>

  <div>

    <div class="form-group has-search">
      <span class="fa fa-search form-control-feedback"></span>
      <input type="text" placeholder="Chercher un.e joueur.se" v-model="search" v-on:keyup="autoComplete" class="form-control search-form my-2">
    </div>

    <div class="search-results">

      <ul class="list-group search-results-list w-100" v-if="users.length">
        <li class="list-group-item text-light bg-dark text-left" v-for="user in users" :key="user.id">
          <span class="pointer text-break" @click="showModal(user)">{{ user.name }}</span>
        </li>
      </ul>

      <ul class="list-group search-results-list w-100" v-if="loading">
        <li class="list-group-item text-light bg-dark text-left">
          Recherche en cours...
        </li>
      </ul>

    </div>


    <!-- Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div v-if="currentUser" class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ currentUser.name }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body p-2">

            <div class="alert alert-warning">
              <p>
                <strong>Inscrit.e le :</strong> {{ currentUser.created_at | moment("DD/MM/Y HH:mm") }}<br>
                <strong>Dernière adresse IP connue :</strong> {{ currentUser.last_login_ip }}<br>
                <strong>Dernière partie jouée le : </strong> {{ currentUser.latest_score.created_at | moment("DD/MM/Y HH:mm") }}<br>
              </p>
            </div>

            <div class="alert alert-info">
              <strong>Derniers messages publics</strong>
              <table class="table table-striped mt-1">
                <tbody>
                  <tr v-for="message in currentUser.messages" :key="message.id">
                    <th>{{ message.game.title }}</th>
                    <td class="text-break">{{ message.message }}</td>
                    <td>{{ message.created_at | moment("DD/MM/Y HH:mm:ss") }}</td>
                    <td>
                      <button type="button" class="btn btn-sm text-danger" @click="deleteMessage(message)">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button v-if="currentUser.banned_until" @click="unban(currentUser)" class="btn btn-primary">
              <i class="fas fa-unlock"></i> Débloquer
            </button>
            <button v-else @click="ban(currentUser)" class="btn btn-danger" title="Bannir 24h">
              <i class="fas fa-lock"></i> Bloquer 24h
            </button>
            <a :href="'/profils/' + currentUser.name" class="btn btn-primary">
              <i class="fas fa-eye"></i> Voir le profil
            </a>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
              Fermer
            </button>
          </div>
        </div>
      </div>
    </div>


  </div>

</template>

<script>

  export default {

    name: "SearchUsers",

    data: () => {
      return {
        loading: false,
        search: '',
        users: {},
        currentUser: null
      }
    },

    methods: {

      showModal(user) {
        this.currentUser = user;
        $('#userModal').modal('show');
      },

      closeModal() {
        $('#userModal').modal('hide');
        this.currentUser = null;
      },

      ban(user) {
          axios.get('/moderator/user/' + user.id + '/block').then(response => {
            this.currentUser.banned_until = response.data.banned_until;
          });
      },

      unban(user) {
          axios.get('/moderator/user/' + user.id + '/unblock').then(response => {
            this.currentUser.banned_until = response.data.banned_until;
          });
      },

      deleteMessage(message) {
        axios.post('/messages/delete', {
          message_id: message.id,
        }).then((resp) => {
          console.log("Message supprimé");
          this.currentUser.messages.splice(this.currentUser.messages.indexOf(message), 1);
        })
      },

      autoComplete(){
        this.users = {};
        this.loading = true;
        if(this.search.length > 2){
          axios.post('/moderator/users/search', {search: this.search}).then(response => {
            this.users = response.data.data;
            this.loading = false;
          });
        }
      }
      
    },

  };

</script>

<style scoped>

  .search-form {
    border-radius: 0;
    border:none;
  }

  .has-search {
    margin-bottom: 0;
  }

  .has-search .form-control {
      padding-left: 2.375rem;
  }

  .has-search .form-control-feedback {
      position: absolute;
      z-index: 2;
      display: block;
      width: 2.375rem;
      height: 2.375rem;
      line-height: 2.375rem;
      text-align: center;
      pointer-events: none;
      color: #aaa;
  }

  .search-results {
    position: relative;
  }

  .search-results-list {
    position: absolute;
    z-index: 20;
    top: 0;
    left: 0;
  }

</style>