<template>

  <div>

    <div class="form-group has-search">
      <span class="fa fa-search form-control-feedback"></span>
      <input type="text" placeholder="Chercher une partie" v-model="query" v-on:keyup="autoComplete" class="form-control search-form my-2">
    </div>

    <div class="search-results">

      <ul class="list-group search-results-list w-100" v-if="results.length">
        <li class="list-group-item text-dark text-left" v-for="result in results">
          <a :href="'/parties/' + result.slug">{{ result.title }}</a><br>
          <span class="badge badge-primary">{{ result.user.name }}</span> <span class="badge badge-info">{{ result.tracks_count }} extraits</span> <span v-if="result.password" class="badge badge-warning" title="Mot de passe"><i class="fas fa-lock"></i></span>
        </li>
      </ul>

    </div>

  </div>

</template>

<script>

  export default {

    data: () => {
      return {
        query: '',
        results: []
      }
    },

    methods: {

      autoComplete(){
        this.results = [];
        if(this.query.length > 2){
          axios.get('/api/games/search',{params: {query: this.query}}).then(response => {
            this.results = response.data.data;
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