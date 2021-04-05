<template>

	<div class="google-communication" class="my-4 text-center w-100" v-html="adsenseContent"></div>

</template>

<script>

    export default {
      
      	name: 'AdSense',

		data() {
			return {
				adsenseContent: '',
			}
		},

      	mounted() {
      		this.adsenseContent = document.getElementById('adsense').innerHTML;
      	}

    };

</script>