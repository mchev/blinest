<template>

<div class="row text-center">

    <div v-if="loadedGames.length == 0" class="col-md-12 text-center">
        <p>Aucune partie privée</p>
        <a class="btn btn-success btn-lg" href="/games/create">Créer une partie privée</a>
    </div>
    <div v-for="game in loadedGames" class="col-md-4 col-lg-3">

      <div v-if="game.user_id == $userId" class="portfolio-item mx-auto" :class="game.slug" :style="{backgroundColor: randomColor(game.id)}">
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
                <h3 class="game-title">{{ game.title }}</h3>
            </div>
        </div>
      </div>

      <a v-else class="portfolio-item mx-auto" :class="game.slug" :href="'/parties/' + game.slug" :style="{backgroundColor: randomColor(game.id)}">
        <div class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
            <div title="Jouer" class="portfolio-item-caption-content text-center text-white">
                <i class="fas fa-play fa-3x"></i>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-center h-100 w-100 game-item">
            <div class="text-center text-white">
                <img class="img-circle" :alt="game.title" v-if="game.thumbnail" :src="'/storage/games/' + game.thumbnail"><br>
                <h3 class="game-title">{{ game.title }}</h3>
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
                loadedGames: this.games,
            }
        },
        mounted() {
            //this.spinner = false;
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
    };

</script>


<style scoped>

    .portfolio-item {
        min-height: 210px;
    }
    .game-item {
        height: 210px !important;
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

    .quiz-general {
        background-color: #F24335 !important;
    }
    .annees-2000 {
        background-color: #B87BFD !important;
    }
    .disney {
        background-color: #9D27B0 !important;
    }
    .chanson-francaise {
        background-color: #6739B6 !important;
    }
    .annees-80 {
        background-color: #3F51B6 !important;
    }
    .series-tv {
        background-color: #02A8F4 !important;
    }
    .le-blindtest-mouth {
        background-color: #2097F3 !important;
    } 
    .musiques-de-film {
        background-color: #01BCD4 !important;
    }
    .pop-rock {
        background-color: #189687 !important;
    }
    .jeux-video {
        background-color: #8AC349 !important;
    }
    .youtubeurs {
        background-color: #4BAF4F !important;
    }
    .moustachus {
        background-color: #CDDC3A !important;
    }
    .musique-classique {
        background-color: #FEC106 !important;
    }
    .metal {
        background-color: #FFEB3C !important;
    }
    .k-pop {
        background-color: #FF9800 !important;
    }
    .rap {
        background-color: #ED6F48 !important;
    }
    .kids {
        background-color: #795749 !important;
    }

</style>