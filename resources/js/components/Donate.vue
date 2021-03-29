<template>

  <div class="card bg-secondary mb-4 text-white">

    <div class="card-header">
      <h5>Je soutiens blinest</h5>
    </div>

    <div class="card-body">

      <div v-if="error" class="alert alert-danger">Une erreur a eu lieu lors de la transaction.</div>

      <div class="form-group">
        <label>Montant de votre don</label>
        <div class="input-group mb-3">
          <input type="number" class="form-control" v-model="quantity" @change="updateLineItems">
          <div class="input-group-append">
            <span class="input-group-text" id="basic-addon2">x 10€</span>
          </div>
        </div>
      </div>

      <h2 class="text-center">{{ quantity * 10 }}€</h2>

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

    props: ['error'],

    data: () => ({
      publishableKey: process.env.MIX_STRIPE_PUBLISHABLE_KEY, //process.env.PUBLISHABLE_KEY, 
      loading: false,
      quantity: 1,
      lineItems: [
        {
          price: 'price_1IaHrSFz8PGMiHyirHUcEBPE', // The id of the one-time price you created in your Stripe dashboard
          quantity: 1,
        }
      ],
      successURL: 'https://blinest.com/donate/success',
      cancelURL: 'https://blinest.com/donate/error',
    }),

    methods: {

      updateLineItems() {

        this.lineItems = [
          {
            price: 'price_1IaHrSFz8PGMiHyiVwAekGIF', // The id of the one-time price you created in your Stripe dashboard
            quantity: parseInt(this.quantity),
          }
        ];

      },

      submit () {
        // You will be redirected to Stripe's secure checkout page
        this.$refs.checkoutRef.redirectToCheckout();
      },

    }

  };

</script>