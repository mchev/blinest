<template>

<div class="row text-center">

    <div v-if="spinner" class="spinner-border" role="status">
      <span class="sr-only">Loading...</span>
    </div>

    <div v-if="games.length == 0" class="col-md-12 text-center">
        <p>Aucune partie privée</p>
        <a class="btn btn-success btn-lg" href="/games/create">Créer une partie privée</a>
    </div>
    <div v-for="game in games" class="col-md-6 col-lg-4">

      <div v-if="game.user_id == $userId" class="portfolio-item mx-auto" :style="{backgroundColor: randomColor(game.id)}">
        <div class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
            <a :href="'/parties/' + game.slug" title="Jouer" class="portfolio-item-caption-content text-center text-white">
                <i class="fas fa-play fa-3x"></i>
            </a>
            <a :href="'/games/' + game.id + '/edit'" title="Modifier" class="portfolio-item-caption-content text-center text-white ml-4">
                <i class="fas fa-edit fa-3x"></i>
            </a>
        </div>
        <div class="d-flex align-items-center justify-content-center h-100 w-100 game-item">
            <div class="text-center text-white">
                <img class="img-circle" :alt="game.title" v-if="game.thumbnail" :src="'/storage/games/' + game.thumbnail"><br>
                <h3>{{ game.title }}</h3>
            </div>
        </div>
      </div>

      <a v-else class="portfolio-item mx-auto" :href="'/parties/' + game.slug" :style="{backgroundColor: randomColor(game.id)}">
        <div class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
            <div title="Jouer" class="portfolio-item-caption-content text-center text-white">
                <i class="fas fa-play fa-3x"></i>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-center h-100 w-100 game-item">
            <div class="text-center text-white">
                <img class="img-circle" :alt="game.title" v-if="game.thumbnail" :src="'/storage/games/' + game.thumbnail"><br>
                <h3>{{ game.title }}</h3>
            </div>
        </div>
      </a>

    </div>
</div>

</template>

<script>
    export default {

        props: ['games'],

        data() {
            return {
                colorCache: {},
                spinner: true
            }
        },
        mounted() {
            //console.log('Component mounted.')
            this.spinner = false;
        },
        created() {
            /*
            axios.get('/api/games').then((response) => {
                this.games = response.data;
            }).catch((error) => {
                console.warn(error);
            });
            */
        },
        methods: {
            randomColor(id) {
                const r = () => Math.floor(256 * Math.random());
                return this.colorCache[id] || (this.colorCache[id] = `rgb(${r()}, ${r()}, ${r()})`);
            }
        }
    }
</script>


<style scoped>

    .portfolio-item {
        min-height: 210px;
    }
    .game-item {
        height: 210px !important;
        font-size: 150%;
    }
    a.portfolio-item:hover {
        text-decoration: none;
    }
    .img-circle {
        height: 150px;
        width: 150px;
        border-radius: 50%;
        object-fit: cover;
    }


</style>