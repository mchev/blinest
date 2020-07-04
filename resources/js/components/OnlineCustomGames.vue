<template>

<div class="row text-center border-right">

    <div class="col-md-12">
        <h2>Parties privées en cours</h2>
    </div>

    <div v-if="loadedGames.length == 0" class="col-md-12 text-center">
        <p>Aucune partie en cours</p>
        <a class="btn btn-success btn-lg" href="/games/create">Créer une partie privée</a>
    </div>

    <div v-for="game in loadedGames" class="col-md-12">

      <a class="portfolio-item mx-auto" :class="game.slug" :href="'/parties/' + game.slug" :style="{backgroundColor: randomColor(game.id)}">
        <div class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
            <div title="Jouer" class="portfolio-item-caption-content text-center text-white">
                <i class="fas fa-play fa-3x"></i>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-center h-100 w-100 game-item">
            <div class="text-center text-white">
                <h3 class="game-title">{{ game.title }}</h3>
                <small>Animateur : {{ game.user.name }}</small>
            </div>
        </div>
      </a>

    </div>

</div>

</template>

<script>
    export default {

        data() {
            return {
                colorCache: {},
                loadedGames: [],
            }
        },
        mounted() {
            //this.spinner = false;
        },
        created() {

            this.fetch();
            this.initInterval();
            
        },

        methods: {

            randomColor(id) {
                const r = () => Math.floor(256 * Math.random());
                return this.colorCache[id] || (this.colorCache[id] = `rgb(${r()}, ${r()}, ${r()})`);
            },

            initInterval() {
                let vm = this;

                setInterval(function() {
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
        }
    };

</script>


<style scoped>

    .portfolio-item {
        min-height: 100px;
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