<template>

    <div>

        <div class="form-group">
            <label for="volume">Volume</label>
            <input type="range" class="form-control-range" min="0" max="1" id="volume" step="0.01" v-model="volume" @change="setVolume">
        </div>

        <div class="custom-control custom-switch">
          <input type="checkbox" class="custom-control-input" id="darkmode" v-model="darkmode" @change="setDarkMode">
          <label class="custom-control-label" for="darkmode">Dark mode</label>
        </div>

    </div>

</template>

<script>

    export default {

        name: "settings",

        props:['game'],

        data() {
            return {
                volume: 1,
                darkmode: false,
            }
        },

        mounted() {

            if(localStorage.getItem('volume')) {
                this.volume = localStorage.getItem('volume');
                this.game.volume = this.volume;
            } else {
                this.setVolume();
            }
            if(localStorage.getItem('darkmode')) {
                this.darkmode = localStorage.getItem('darkmode');
                this.game.darkmode = this.darkmode;
            } else {
                this.setDarkMode();
            }

        },

        methods: {

            setVolume() {
                localStorage.setItem('volume', this.volume);
                this.game.volume = this.volume;
                if (this.game.player) this.game.player.volume = this.volume;
            },

            setDarkMode() {
                //this.darkmode = (this.darkmode == "true") ? 1 : 0;
                localStorage.setItem('darkmode', this.darkmode);
                this.game.darkmode = this.darkmode;
            }

        }

    };

</script>

<style scoped>


</style>