<template>

    <div class="btn-group my-2">
        <button class="btn btn-success" title="Démarrer" @click="start"><i class="fas fa-play"></i></button>
        <button class="btn btn-secondary" title="Pause"><i class="fas fa-pause"></i></button>
        <button class="btn btn-secondary" title="Suivant"><i class="fas fa-forward"></i></button>
        <button class="btn btn-secondary" title="Arret"><i class="fas fa-stop"></i></button>
    </div>

</template>

<script>

    export default {

        name: 'customControls',

        props: ['game'],

        data() {
            return {
                tracks: [],
            }
        },

        computed: {


        },

        watch: {


        },

        methods: {

            start() {
                axios.post('/parties/privees/' + this.game.id + '/start', this.game).then((response) => {
                    console.log(response.data);
                    this.tracks = response.data;
                }).catch((error) => {
                    console.warn(error);
                });
            }

        }
    };

</script>