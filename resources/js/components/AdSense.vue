<template>

  <div>

    <div v-if="adBlockEnabled" class="alert alert-info alert-dismissible fade show" role="alert">
      <small>Soutiens Blinest en désactivant ton bloqueur de publicité.</small>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

  	<div class="google-communication my-4 text-center w-100" v-html="adsenseContent"></div>

  </div>

</template>

<script>

    export default {
      
      	name: 'AdSense',

      	props: ['position'],

        data() {
          return {
            adsenseContent: '',
            adBlockEnabled: false,
          }
        },

      	mounted() {

          const ad = document.createElement('div')
          ad.innerHTML = '&nbsp;'
          ad.className = 'adsbox'
          document.body.appendChild(ad)
          window.setTimeout(function() {
            if (ad.offsetHeight === 0) {
              this.adBlockEnabled = true
            }
            ad.remove()
          }, 100)

      		this.adsenseContent = document.getElementById(this.position).innerHTML;
      	}

    };

</script>