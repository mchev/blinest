<template>

  <div class="card bg-secondary mb-4 text-white">

    <div class="card-header">
      <h5>Je soutiens blinest</h5>
    </div>

    <div class="card-body">

      <div v-if="error" class="alert alert-danger">Une erreur a eu lieu lors de la transaction.</div>

      <stripe-checkout
        ref="checkoutRef"
        mode="payment"
        :pk="publishableKey"
        :line-items="lineItems"
        :success-url="successURL"
        :cancel-url="cancelURL"
        @loading="v => loading = v"
      />

      <small><i class="text-success fas fa-lock"></i> Paiement sécurisé via <a target="_blank" href="https://stripe.com">Stripe</a>.</small>

    </div>

    <div class="card-footer text-right">
      <loader v-if="loading"></loader>
      <button v-else class="btn btn-success" @click="submit">Faire un don</button>
    </div>

  </div>

</template>

<script>

  import { StripeCheckout } from '@vue-stripe/vue-stripe';

  export default {

    components: {
      StripeCheckout,
    },

    props: ['user'],

    data: () => ({
      publishableKey: process.env.MIX_STRIPE_PUBLISHABLE_KEY, //process.env.PUBLISHABLE_KEY, 
      loading: false,
      lineItems: [
        {
          price: 'price_1IaHrSFz8PGMiHyiVwAekGIF', // The id of the one-time price you created in your Stripe dashboard
          quantity: 1,
        },
        {
          price: 'price_1IaHrSFz8PGMiHyirHUcEBPE', // The id of the one-time price you created in your Stripe dashboard
          quantity: 1,
        },
        {
          price: 'price_1IaHrSFz8PGMiHyiRaSLAIy6', // The id of the one-time price you created in your Stripe dashboard
          quantity: 1,
        },
        {
          price: 'price_1IaHrSFz8PGMiHyiOoWtz1Bw', // The id of the one-time price you created in your Stripe dashboard
          quantity: 1,
        },
      ],
      successURL: 'https://blinest.com/donate/success',
      cancelURL: 'https://blinest.com/donate/error',
      error: false,
    }),

    methods: {

      submit () {
        // You will be redirected to Stripe's secure checkout page
        this.$refs.checkoutRef.redirectToCheckout();
      },

    }

  };

</script>