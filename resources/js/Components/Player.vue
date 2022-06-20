<template>
    <div
        class="w-full h-8 bg-teal-200 rounded-full"
        id="player"
    >
        <div v-if="error">
            {{ error }}
        </div>
        <div
            v-else-if="loading"
            class="h-8 w-full bg-green-600 rounded-full dark:bg-green-300 animate-pulse"
        ></div>
        <div
            v-else
            class="shine h-8 bg-gradient-to-br from-teal-300 to-teal-400 rounded-full transition-all duration-500 ease-linear"
            :style="'width:' + percent + '%'"
        ></div>
    </div>

    <button @click="play" type="button">Play</button>
    <button @click="pause" type="button">Pause</button>
    <button @click="stop" type="button">Stop</button>
</template>

<script>
export default {
    props: {
        //track: Object,
    },

    data() {
        return {
            track: {
                src: "https://audio-ssl.itunes.apple.com/itunes-assets/AudioPreview124/v4/8a/1e/03/8a1e0314-b2f1-2ac0-cff5-7298164da844/mzaf_10401051262985820957.plus.aac.p.m4a",
            },
            audio: new Audio(),
            loading: true,
            error: null,
            percent: 0,
            duration: 0,
        };
    },

    mounted() {
        this.play();
    },

    unmounted() {
        this.stop();
    },

    methods: {
        play() {
            this.loading = true;
            this.audio.src = this.track.src;

            this.audio.addEventListener("loadeddata", () => {
                this.duration = this.audio.duration;
            });

            this.audio.addEventListener("error", () => {
                this.error = this.audio.error.message;
            });

            this.audio.addEventListener("canplaythrough", () => {
                this.loading = false;
            });

            this.audio.addEventListener("canplaythrough", (event) => {
                this.audio.play();
            });

            this.audio.addEventListener("timeupdate", () => {
                this.percent = parseInt(
                    (100 / this.duration) * (this.audio.currentTime + 0.25)
                );
            });

            this.audio.addEventListener("ended", () => {
                console.log("Finnish!");
                this.$emit("track:ended", this.track);
            });
        },

        pause() {
            this.audio.pause();
            this.$emit("track:paused", this.track);
        },

        stop() {
            this.audio.currentTime = 0;
            this.audio.pause();
            this.$emit("track:stopped", this.track);
        },
    },
};
</script>

<style scoped>
    
  .shine {
    position: relative;
  }

  .shine::after {
    content: '';
    opacity: 0;
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: #fff;
    border-radius: 10px;
    animation: animate-shine 2.5s ease-out infinite;
    animation-delay: 10s;
  }

  @keyframes animate-shine {
    0% {
      opacity: 0;
      width: 0;
    }
    50% {
      opacity: .25;
    }
    100% {
      opacity: 0;
      width: 95%;
    }
  }

</style>
