<template>

	<div class="google-communication my-4 text-center w-100" v-html="adsenseContent"></div>

</template>

<script>

    export default {
      
      	name: 'AdSense',

      	props: ['position'],

		data() {
			return {
				adsenseContent: '',
			}
		},

      	mounted() {
      		this.adsenseContent = document.getElementById(this.position).innerHTML;
      	}

    };

</script>