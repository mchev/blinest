<template>

    <div>

        <button class="btn btn-primary" @click="start">Start</button>

        <div v-if="listening" class="alert alert-info">L'extrait précédent est bientôt terminé, c'est bientôt à ton tour...</div>

    </div>
	
</template>

<script>

    export default {

    	name: 'stream',

        props:['game'],

        data() {
            return {
                track: {},
                listening: false,
                player: null,
            }
        },
        mounted() {
            console.log('Component mounted.');
        },

        methods: {

            start() {
                this.listening = true;
                this.listenForNewTrack();
            },

            listenForNewTrack() {
                Echo.channel('newTrack-' + this.game.id)
                    .listen('NewTrack', (data) => {
                        this.listening = false;
                        this.track = data.track;
                        this.playTrack();
                    })
            },

            playTrack() {

                if(this.player) this.player.pause();
                this.player = new Audio(this.track.preview_url);
                this.player.play();

                this.player.onended = function() {
                    
                }

            }

        }

    };

</script>