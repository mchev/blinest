<template>

<div class="row text-center">

    <div v-if="games.length == 0" class="col-md-12 text-center">
        <p>Aucune partie privée</p>
        <a class="btn btn-success btn-lg" href="/games/create">Créer une partie privée</a>
    </div>

    <div v-for="game in reactiveGames" class="col-md-4 col-lg-2">

        <span v-if="game.counter" class="counter"><i class="fas fa-user-friends"></i> {{ game.counter }}</span>

      <div v-if="game.user_id == $userId" class="portfolio-item mx-auto" :class="game.slug" :style="{backgroundColor: color(game)}">
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
                <img loading="lazy" class="img-circle" :alt="game.title" v-if="game.thumbnail" :src="'/storage/games/' + game.thumbnail"><br>
                <h3 class="game-title">{{ game.title }}</h3>
            </div>
        </div>
      </div>

      <a v-else class="portfolio-item mx-auto" :class="game.slug" :href="'/parties/' + game.slug" :style="{backgroundColor: color(game)}">
        <div class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
            <div title="Jouer" class="portfolio-item-caption-content text-center text-white">
                <i class="fas fa-play fa-3x"></i>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-center h-100 w-100 game-item">
            <div class="text-center text-white">
                <img loading="lazy" class="img-circle" :alt="game.title" v-if="game.thumbnail" :src="'/storage/games/' + game.thumbnail"><br>
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
                users: [],
                reactiveGames: this.games,
                usersCount: 0,
                colorCache: {},
            }
        },

        created() {
            this.getPlayersCounter();
        },

        methods: {

            randomColor(id) {
                const r = () => Math.floor(256 * Math.random());
                return this.colorCache[id] || (this.colorCache[id] = `rgb(${r()}, ${r()}, ${r()})`);
            },

            color(game) {
                if(game.color && game.color.length === 6) {
                    return '#' + game.color;
                } else {
                    return this.randomColor(game.id);
                }
            },

            getPlayersCounter() {

                let vm = this;

                // Delay the listener to prevent for unecessaries requests
                setTimeout(function () {

                    $.each(vm.reactiveGames, function(key, value) {

                      Echo.private('game-' + value.id)
                        .listenForWhisper('counter', (data) => {
                            value.counter = data;
                            vm.$set(vm.reactiveGames, key, value);
                        });

                    });

                }, 15000);

            }


        }
    };

</script>


<style scoped>

    .counter {
        position: absolute;
        background: hsla(0,0%,100%,.6);
        display: block;
        height: 30px;
        min-width: 30px;
        top: 3px;
        right: 18px;
        z-index: 10;
        border-radius: .25rem;
        font-weight: 700;
        line-height: 30px;
        text-align: center;
        padding: 0 0.2rem;
        color: #2c3e50;
    }

    .portfolio-item {
        min-height: 150px;
    }

    .game-item {
        height: 150px !important;
        font-size: 150%;
    }
    
    .game-title {
        font-size: 1rem;
    }

    a.portfolio-item:hover {
        text-decoration: none;
    }

    .img-circle {
        height: 100px;
        width: 100px;
        border-radius: 50%;
        object-fit: cover;
        background: #FFFFFF;
    }


</style>