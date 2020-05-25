<template>

    <div>

        <div v-if="success" class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Merci!</strong> Ton message a été envoyé aux modérateur.rice.s. Ils traiterons la demande très bientôt.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <textarea class="form-control mb-3" v-model="message" placeholder="Le message doit concerner cette partie et doit contenir toutes les informations necessaires pour aider les modérateurs à traiter la demande. Il est aussi possible de faire des demandes d'ajout." required></textarea>

        <button class="btn btn-success" @click="store"><i class="far fa-paper-plane"></i> Envoyer</button>

    </div>


</template>

<script>

    export default {

        name:"create-ticket",

        props:['game'],

        data() {
            return {
                message: '',
                success: false,
            }
        },

        methods: {

            store() {

                this.success = false;

                if (this.message !== '') {

                    axios.post('/games/' + this.game.id + '/create/ticket', { message: this.message}).then((response) => {
                        this.success = true;
                        this.message = '';
                    }).catch((error) => {
                        console.warn(error);
                    });

                }

            },

        }

    };

</script>