<template>

    <div class="card mb-3">
        <div class="card-header">
            <div class="btn-group btn-block my-2">
                <button v-if="!playing" class="btn btn-success" title="Démarrer" @click="play()"><i class="fas fa-play"></i></button>
                <button v-if="paused && playing && !resumed" class="btn btn-success" title="Démarrer" @click="resume"><i class="fas fa-play"></i></button>
                <button v-if="playing && !paused" class="btn btn-secondary" title="Pause" @click="pause"><i class="fas fa-pause"></i></button>
                <button class="btn btn-secondary" title="Suivant" @click="next"><i class="fas fa-forward"></i></button>
                <button class="btn btn-secondary" title="Arret"><i class="fas fa-stop"></i></button>
            </div>
        </div>
        <div class="card-body track-list p-0">
            <ol class="list-group text-left">
                <li v-for="(track, index) in tracks" class="list-group-item p-0" :class="{'bg-success text-white': index === track_index}">

                    <button v-if="index === track_index && playing" class="btn" @click="pause"><i class="fas fa-pause" ></i></button> 
                    <button v-else class="btn" @click="play(track)"><i class="fas fa-play" ></i></button>
                    <button v-else class="btn" @click="resume"><i class="fas fa-play" ></i></button>

                    <strong>{{ track.artist_name }}</strong> - {{ track.track_name }}


                </li>
            </ol>
        </div>
    </div>

</template>

<script>

    export default {

        name: 'customControls',

        props: ['game', 'tracks'],

        data() {
            return {
                track: {},
                track_index: 0,
                player: null,
                playing: false,
                paused: false,
                resumed: false,
            }
        },

        computed: {


        },

        watch: {


        },

        methods: {

            play( track = this.tracks[this.track_index] ) {

                console.log(track);

                this.track = track;
                this.track_index = this.tracks.indexOf(track);
                this.playing = true;
                this.paused = false;
                this.resumed = false;

                axios.get('/parties/privees/play/' + this.track.id).then((resp) => {
                    console.log('Lancement de la lecture');
                });

            },

            pause() {

                this.resumed = false;
                this.paused = true;

                axios.get('/parties/privees/pause/' + this.track.id).then((resp) => {
                    console.log('Mise en pause de la lecture');
                });

            },

            resume() {

                this.resumed = true;
                this.paused = false;

                axios.get('/parties/privees/resume/' + this.track.id).then((resp) => {
                    console.log('Reprise de la lecture');
                });

            },

            next() {

                this.track_index++;
                this.play();

            },

            end() {

                this.resumed = false;
                this.paused = false;
                this.playing = false;

                axios.get('/parties/privees/' + this.game.id + '/end').then((resp) => {
                    console.log('Fin de la partie');
                });

            }

        }
    };

</script>

<style scoped>

    .track-list {
        max-height: 50vh;
        overflow-y: auto;
    }

    .list-group-item {
        border-radius: 0;
        border: 0;
        border-bottom: 1px solid #CCC;
        text-overflow: ellipsis;
        width: 100%;
        overflow: hidden;
        max-height: 2.4rem;
    }


</style>