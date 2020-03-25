<template>

	<div class="lds-dual-ring"></div>

</template>

<script>

    export default {
        
        name: 'loader',

    };

</script>


<style>

	.lds-dual-ring {
	  display: inline-block;
	  width: 80px;
	  height: 80px;
	}
	.lds-dual-ring:after {
	  content: " ";
	  display: block;
	  width: 64px;
	  height: 64px;
	  margin: 8px;
	  border-radius: 50%;
	  border: 6px solid #1abc9c;
	  border-color: #1abc9c transparent #1abc9c transparent;
	  animation: lds-dual-ring 1.2s linear infinite;
	}
	@keyframes lds-dual-ring {
	  0% {
	    transform: rotate(0deg);
	  }
	  100% {
	    transform: rotate(360deg);
	  }
	}

</style>