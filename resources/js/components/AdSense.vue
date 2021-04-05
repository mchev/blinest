<template>

	<div class="google-communication" v-html="adsenseContent"></div>

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