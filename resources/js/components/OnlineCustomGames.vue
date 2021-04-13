<template>

<div class="w-100 d-block">

    <button class="btn btn-sm btn-primary d-block w-100 text-left mb-2" @click="toggleCustomGames">
        <span v-if="displayCustomGames"><i class="fas fa-eye"></i> Parties joueurs</span>
        <span v-else><i class="fas fa-eye-slash"></i> Parties joueurs</span>
    </button>

    <div class="row my-4" v-show="displayCustomGames">

        <div class="col-md-12">
            <b>Parties privées <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" :title="tooltip"></i></b>
        </div>

        <div v-if="loadedGames.length == 0" class="col-md-12">
            <p>Aucune partie en cours</p>
        </div>

        <div v-for="game in loadedGames" class="col-md-12">

          <a class="portfolio-item mx-auto" :class="game.slug" :href="'/parties/' + game.slug">
            <div class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
                <div title="Jouer" class="portfolio-item-caption-content text-center text-white">
                    <i class="fas fa-play fa-3x"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-center h-100 w-100 game-item">
                <div class="text-center text-white">
                    <h3 class="game-title">{{ game.title | badFilter }}</h3>
                    <small>Animateur : {{ game.user.name }}</small>
                </div>
            </div>
          </a>

        </div>

    </div>

</div>

</template>

<script>

    const badWords = require('leo-profanity');
    badWords.loadDictionary('fr');

    export default {

        data() {
            return {
                interval: null,
                colorCache: {},
                loadedGames: [],
                displayCustomGames: false,
                tooltip: "Pour apparaitre dans cette liste vous devez avoir lancé votre partie et celle-ci ne doit pas être protégée par un mot de passe.",
            }
        },
        mounted() {

            $('[data-toggle="tooltip"]').tooltip();

            if (localStorage.displayCustomGames === 'show') {
              this.displayCustomGames = 'show'
              this.fetch();
              this.initInterval();
            } else {
              this.displayCustomGames = false;
            }

        },

        methods: {

            toggleCustomGames() {

                if(this.displayCustomGames) {
                    localStorage.displayCustomGames = false;
                    this.displayCustomGames = false;
                    clearInterval(this.interval);
                } else {
                    localStorage.displayCustomGames = 'show';
                    this.displayCustomGames = 'show';
                    this.fetch();
                    this.initInterval();
                }

            },

            initInterval() {
                let vm = this;

                this.interval = setInterval(function() {
                    vm.fetch();
                }, 10000);

            },

            fetch() {
                let vm = this;

                axios.get('/games/privates').then((response) => {
                    vm.loadedGames = response.data;
                }).catch((error) => {
                    console.warn(error);
                });

            }
        },

        filters: {

          badFilter: function(text) {
              return badWords.clean(text);
          },

        }

    };

</script>


<style scoped>

    .private-title {
        font-weight: 100;
    }

    .portfolio-item {
        min-height: 100px;
        background-color: #2c3e50;
    }
    .game-item {
        height: 100px !important;
        font-size: 150%;
    }
    .game-title {
        font-size: 1.2rem;
    }

    a.portfolio-item:hover {
        text-decoration: none;
    }
    .img-circle {
        height: 150px;
        width: 150px;
        border-radius: 50%;
        object-fit: cover;
        background: #FFFFFF;
    }

</style>