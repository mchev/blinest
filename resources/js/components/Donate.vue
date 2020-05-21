<template>

  <div class="card bg-secondary mb-4 text-white">

    <div class="card-header">
      <h5>Je soutiens blinest</h5>
    </div>

    <div class="card-body">

      <div v-if="error" class="alert alert-danger">Une erreur a eu lieu lors de la transaction.</div>

      <div class="row mb-4 d-flex justify-content-center text-center">

        <div class="col-md-4">

          <div class="input-group input-group-lg">
            <input type="number" step="1" v-model="amount" class="form-control">
            <div class="input-group-append">
              <span class="input-group-text">€</span>
            </div>
          </div>

          <small>Montant libre</small>

        </div>

      </div>


      <div class="form-group">
          <input class="form-control" type="text" v-model="user.name" required placeholder="Nom">
      </div>

      <div class="form-group">
        <input class="form-control" type="email" v-model="user.email" required placeholder="Email">
      </div>

      <stripe-elements
        id="stripeModule"
        ref="elementsRef"
        :pk="publishableKey"
        :amount="amount"
        locale="fr"
        @token="tokenCreated"
        @loading="loading = $event"
      >
      </stripe-elements>

      <small><i class="text-success fas fa-lock"></i> Paiement sécurisé via <a target="_blank" href="https://stripe.com">Stripe</a>.</small>

    </div>

    <div class="card-footer text-right">
      <loader v-if="loading"></loader>
      <button v-else class="btn btn-success" @click="submit">Faire un don de {{amount}}€</button>
    </div>

  </div>

</template>

<script>

  import { StripeElements } from 'vue-stripe-checkout';

  export default {

    components: {
      StripeElements
    },

    props: ['user'],

    data: () => ({
      loading: false,
      amount: 10,
      publishableKey: 'pk_live_fzRDxcsWGGIECf7As8JZlELt00tYK4Kz1f', //process.env.PUBLISHABLE_KEY, 
      token: null,
      charge: null,
      error: false,
    }),

    methods: {

      submit () {
        this.$refs.elementsRef.submit();
      },

      tokenCreated (token) {
        this.token = token;
        // for additional charge objects go to https://stripe.com/docs/api/charges/object
        this.charge = {
          source: token.id,
          amount: parseFloat(this.amount) * 100, // the amount you want to charge the customer in cents. $100 is 1000 (it is strongly recommended you use a product id and quantity and get calculate this on the backend to avoid people manipulating the cost)
          description: this.description, // optional description that will show up on stripe when looking at payments
          email: this.user.email,
          name: this.user.name,
          user_id: this.user.id,
        }
        this.sendTokenToServer(this.charge);
      },

      sendTokenToServer (charge) {

        this.loading = true;
        // Documentation here: https://stripe.com/docs/api/charges/create

        axios.post('/donate', charge).then((response) => {
          console.log(response);
          if( response.data.paid) {
            window.location.href = '/thankyou';
          } else {
            this.error = true;
          }
          this.loading = false;
        }).catch((error) => {
          console.warn(error);
          this.error = true;
          this.loading = false;
        });

      }

    }

  };

</script>