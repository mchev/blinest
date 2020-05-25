<template>

    <div>

        <loader v-if="loading"></loader>

        <div v-if="tickets.length === 0">Aucune demande en cours.</div>

        <div v-else v-for="ticket in tickets" :key="ticket.id" class="card text-dark mb-2">

            <div class="card-body p-2">
                <span class="badge badge-info">{{ ticket.game.title }}</span><br>
                {{ ticket.message }}
                <button type="button" title="Fermer le ticket" class="close" @click="close(ticket)">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="card-footer">
                <small>{{ ticket.created_at | moment("from", "now") }} par {{ ticket.user.name }}</small>
            </div>

        </div>

    </div>


</template>

<script>

    export default {

        name:"list-ticket",

        data() {
            return {
                tickets: {},
                success: false,
                loading: false,
            }
        },

        mounted() {

            this.fetch();

        },

        methods: {

            fetch() {

                axios.get('/moderator/tickets').then((response) => {
                    this.tickets = response.data;
                }).catch((error) => {
                    console.warn(error);
                });

            },

            close(ticket) {
                this.loading = true;
                axios.get('/moderator/tickets/' + ticket.id + '/close').then((response) => {
                    this.fetch();
                    this.loading = false;
                }).catch((error) => {
                    console.warn(error);
                });
            }

        }

    };

</script>