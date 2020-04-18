<template>

    <div class="container-fluid h-100 custom-game">

        <div class="row h-100">

            <div v-if="$userId == game.user_id" class="col-md-3 col-lg-2 order-1 p-0 sidebar sidebar-left">

                <div class="sticky-top sticky-offset text-center">

                    <span @click="hideControlsSidebar" class="btn toggle-controls-sidebar badge badge-secondary p-2" title="Masquer le panneau d'animation"><i class="fas fa-chevron-left"></i> Animation</span>

                    <div class="sidebar-content">

                        <div class="p-2">

                            <VideoCam></VideoCam>

                            <hr>

                            <Controls v-if="tracks" ref="controls" :game="game" :tracks="tracks" v-on:play:track="track = $event"></Controls>

                            <div v-if="!tracks" class="card mb-3">

                                <div class="card-body">

                                    <div class="form-group">
                                        <input type="number" v-model="item.tracks_number" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <select v-model="item.random" class="form-control">
                                            <option value="0">Lecture dans l'ordre de la playlist</option>
                                            <option value="1">Lecture aléatoire</option>
                                        </select>
                                    </div>

                                    <button type="button" @click="fetchTracks" class="btn btn-secondary">Charger une nouvelle partie</button>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col order-2 p-0 bg-dark" id="main">

                <header class="row text-white text-center my-5">

                    <div class="col-md-12">

                        <h1 class="masthead-heading text-uppercase mb-0">Blind-Test {{ game.title }}</h1>

                        <p class="masthead-subheading font-weight-light mb-0">{{ game.description }}</p>

                        <Player :game="game" :track="track" v-on:updateScore="score = $event" v-on:updateUsers="users = $event" v-on:updateAnswers="answers = $event" v-on:game:end="tracks = null" v-on:track:end="$refs.controls.next()"></Player>

                    </div>

                </header>

                <div class="container">

                    <div class="row">

                        <div class="col-md-6">

                            <div class="card">

                                <div class="card-header bg-secondary text-white">
                                    <h5>Tu viens d'écouter <span v-if="answers[0]" class="float-right">{{ answers[0].counter }} / {{ answers[0].total }}</span></h5>
                                </div>

                                <div class="card-body p-0 card-multiplayer">
                                    <answers v-if="answers.length > 0" :answers="answers"></answers>
                                </div>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="card">

                                <div class="card-header bg-secondary text-white">
                                    <h5>Scores</h5>
                                </div>

                                <div class="card-body p-0 card-multiplayer">
                                    <ranking :game="game" :users="users" :userScore="score"></ranking>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-md-3 col-lg-2 order-3 p-0 sidebar sidebar-right">

                <div class="sticky-top sticky-offset h-100">

                    <Chat :game="game"></Chat>

                </div>

            </div>

        </div>

    </div>

</template>

<script>

    import VideoCam from './Video'
    import Controls from './Controls'
    import Player from './Player'
    import Chat from './Chat'

    export default {

        name:"customGame",

        components: {
            VideoCam,
            Controls,
            Player,
            Chat
        },

        props:['game'],

        data() {
            return {
                item: this.game,
                tracks: false,
                answers: [],
                track: null,
                player: null,
                users: [],
                score: 0
            }
        },

        mounted() {
            //console.log(this.$userId);
        },

        methods: {

            hideControlsSidebar() {
                $('.sidebar.sidebar-left').toggleClass('hide');
            },

            fetchTracks() {
                axios.post('/parties/privees/' + this.game.id + '/fetch', this.game).then((response) => {
                    this.tracks = response.data;
                    this.$emit('update:tracks', this.tracks);
                }).catch((error) => {
                    console.warn(error);
                });
            },

        }

    };

</script>

<style scoped>

    .toggle-controls-sidebar {
        position: absolute;
        left: 100%;
        top: 8%;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border: 1px solid rgba(255,255,255,0.5);
        border-left: 0;
    }

    .bg-dark {
        background-color: #636f7b !important;
    }

    .custom-game {
        overflow-x: hidden;
    }

    .sidebar {
        background: #61617d;
        transition: all .25s ease;
        min-height: calc(100vh - 72px);
    }

    .sidebar-content {
        overflow-x: hidden;
    }

    .sidebar.hide {
        max-width: 0%;
    }

    .sidebar-left {
        border-right: 1px solid rgba(255,255,255,0.5);
    }
    .sidebar-right {
        border-left: 1px solid rgba(255,255,255,0.5);
    }

    .card {
        background-color: rgba(255,255,255,0.8);
    }

    hr {
        border-top: 1px solid #FFF;
    }

</style>