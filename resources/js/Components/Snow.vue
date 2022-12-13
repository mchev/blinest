<template>
  <!-- tsParticles Repository: https://github.com/matteobruni/tsparticles -->
  <!-- tsParticles Website: https://particles.js.org -->
  <Particles
    id="tsparticles"
    :options="options"
    :particlesInit="particlesInit"
  />
</template>

<script lang="ts">
import type { Engine } from "tsparticles-engine";
import { loadSnowPreset } from "tsparticles-preset-snow"; // loads tsparticles-slim
//import { loadFull } from "tsparticles"; // loads tsparticles
export default {
  data() {
    return {
      options: {
        fullScreen: {
          enable: true, // enabling this will make the canvas fill the entire screen, it's enabled by default
          zIndex: -1, // this is the z-index value used when the fullScreen is enabled, it's 0 by default
        },
        particles: {
          links: {
            distance: 200, // maximum distance for linking the particles
            enable: true, // enabling this will make the particles linked together
          },
          move: {
            enable: true, // enabling this will make particles move in the canvas
            speed: { min: 0.5, max: 2 }, // using a range in speed value will make particles move in a random speed between min/max values, each particle has its own value, it won't change over time by default
          },
          opacity: {
            value: { min: 0.3, max: 0.7 }, // using a different opacity, to have some semitransparent effects
          },
          size: {
            value: { min: 1, max: 3 }, // let's randomize the particles size a bit
          },
        },
      },
    };
  },
  methods: {
    async particlesInit(engine: Engine) {
      await loadSnowPreset(engine);
      // await loadFull(engine); // for this sample the slim version is enough, choose whatever you prefer, slim is smaller in size but doesn't have all the plugins and the mouse trail feature
    },
  },
};
</script>