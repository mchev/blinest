<template>

    <div class="card mb-3">
        <div class="card-header">
            <button v-if="!start" class="btn btn-info btn-block" @click="newGame()">Signaler le démarrage</button>
            <div v-else class="btn-group btn-block my-2">
                <button v-if="!playing" class="btn btn-success" title="Démarrer" @click="play()"><i class="fas fa-play"></i></button>
                <button v-if="paused && playing && !resumed" class="btn btn-success" title="Démarrer" @click="resume"><i class="fas fa-play"></i></button>
                <button v-if="playing && !paused" class="btn btn-secondary" title="Pause" @click="pause"><i class="fas fa-pause"></i></button>
                <button class="btn btn-secondary" title="Suivant" @click="next(false)"><i class="fas fa-forward"></i></button>
                <button class="btn btn-secondary" title="Arret" @click="stop"><i class="fas fa-stop"></i></button>
                <button class="btn btn-secondary" title="Voir les extraits" @click="trackList = !trackList"><i class="fas fa-eye"></i></button>
            </div>
        </div>
        <div v-if="trackList" class="card-body track-list p-0">
            <ol class="list-group text-left">
                <li v-for="(track, index) in tracks" class="list-group-item p-0" :class="{'bg-success text-white': index === track_index}" :title="track.artist_name + ' - ' + track.track_name">

                    <button v-if="index === track_index && playing && !paused" class="btn" @click="pause"><i class="fas fa-circle-notch fa-spin" ></i></button> 
                    <button v-else class="btn" @click="play(track)"><i class="fas fa-play" ></i></button>

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
                start: false,
                trackList: false,
            }
        },

        mounted() {

            this.online();

        },

        methods: {

            newGame() {

                axios.get('/partie/privee/' + this.game.id + '/start').then((resp) => {
                    console.log('Démarrage d\'une partie');
                    this.start = true;
                });

            },

            online() {

                let vm = this;

                setInterval(function() { 
                    axios.get('/games/' + vm.game.id + '/online');
                }, 10000);

            },

            play( track = this.tracks[this.track_index] ) {

                this.track = track;
                this.track_index = this.tracks.indexOf(track);
                this.playing = true;
                this.paused = false;
                this.resumed = false;

                axios.get('/partie/privee/play/' + this.track.id).then((resp) => {
                    console.log('Lancement de la lecture');
                });

            },

            pause() {

                this.resumed = false;
                this.paused = true;

                axios.get('/partie/privee/pause/' + this.track.id).then((resp) => {
                    console.log('Mise en pause de la lecture');
                });

            },

            resume() {

                this.resumed = true;
                this.paused = false;

                axios.get('/partie/privee/resume/' + this.track.id).then((resp) => {
                    console.log('Reprise de la lecture');
                });

            },

            next(wait = true) {

                var cofeebreak = (wait === true) ? 4000 : 0;

                let vm = this;

                if ( this.track_index + 1 == parseInt(this.game.tracks_number) ) {

                    this.stop();

                } else {

                    setTimeout(function(){
                        vm.track_index++;
                        vm.play();
                    }, cofeebreak);
                }

            },

            stop() {

                this.resumed = false;
                this.paused = false;
                this.playing = false;

                axios.get('/partie/privee/' + this.game.id + '/stop').then((resp) => {
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
        font-size: 80%;
    }

    .list-group-item {
        border-radius: 0;
        border: 0;
        border-bottom: 1px solid #CCC;
        text-overflow: ellipsis;
        word-break: break-all;
        width: 100%;
        overflow: hidden;
        max-height: 2.4rem;
    }


</style>