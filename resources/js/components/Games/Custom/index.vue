<template>

    <div class="container-fluid h-100">

        <div class="row h-100">

            <div class="col-md-3 col-lg-2 order-1 py-3 sidebar">

                <div class="sticky-top sticky-offset text-center">

                    <VideoCam></VideoCam>

                    <hr>

                    <Controls :game="game"></Controls>

                    <hr>

                    <div class="card mb-3">

                        <div class="card-body">

                            <div class="form-group">
                                <input type="number" v-model="item.tracks_number" class="form-control">
                            </div>

                            <div class="form-group">
                                <select v-model="item.random" class="form-control">
                                    <option value="0">Lecture dans l'ordre</option>
                                    <option value="1">Lecture aléatoire</option>
                                </select>
                            </div>

                        </div>

                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="formControlRange"><i class="fas fa-volume-up"></i> Volume</label>
                                <input type="range" class="form-control-range" id="formControlRange">
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header">
                            Morceau en cours
                        </div>
                        <div class="card-body">

                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header">
                            Envoyer un indice
                        </div>
                        <div class="card-body">
                            <div class="input-group">
                                <input type="text" class="form-control" v-model="indice">
                                <div class="input-group-append">
                                    <button class="btn btn-secondary"><i class="fas fa-paper-plane"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>


            <div class="col order-2 text-center p-0" id="main">

                <header class="masthead bg-primary text-white text-center pb-2 mb-3">

                    <div class="d-flex align-items-center flex-column">

                        <h1 class="masthead-heading text-uppercase mb-0">Blind-Test {{ game.title }}</h1>

                        <p class="masthead-subheading font-weight-light mb-0">{{ game.description }}</p>

                        <Player :game="game"></Player>

                    </div>

                </header>

                <div class="card m-4">

                    <div class="card-header bg-secondary text-white">
                        <h5>Tu viens d'écouter <span v-if="answers[0]" class="float-right">{{ answers[0].counter }} / {{ answers[0].total }}</span></h5>
                    </div>

                    <div class="card-body p-0 card-multiplayer">
                        <answers v-if="answers.length > 0" :answers="answers"></answers>
                    </div>

                </div>

            </div>

            <div class="col-md-3 order-3 py-3 sidebar sidebar-right">

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
                answers: [],
                indice: '',
            }
        },

        methods: {


        }

    };

</script>

<style scoped>

    .sidebar {
        background: #61617d;
    }

    hr {
        border-top: 1px solid #FFF;
    }

</style>