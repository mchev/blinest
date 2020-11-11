<template>

    <div>

        <loader v-if="loading"></loader>

        
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li v-for="(game, index) in games" class="nav-item mr-1">
                <a class="nav-link bg-light" :class="{active: index === 0}" data-toggle="tab" :href="'#ticket-tab-' + game.id" role="tab">
                    {{ game.title }} ({{game.pending_tickets.length }})
                </a>
            </li>
        </ul>

        <div class="tab-content">

            <div v-for="(game, index) in games" class="tab-pane" :class="{active: index === 0}" :id="'ticket-tab-' + game.id" role="tabpanel">

                <div v-for="ticket in game.pending_tickets" :key="ticket.id" class="card mt-4">

                    <div class="card-body p-2">
                        <pre>{{ ticket.message }}</pre>
                    </div>

                    <div class="card-footer">
                        <button class="btn btn-sm btn-secondary">
                            {{ ticket.created_at | moment("from", "now") }} par {{ ticket.user.name }}
                        </button>
                        <button type="button" title="Fermer le ticket" class="btn btn-sm btn-success" @click="close(ticket)">
                            <i class="fas fa-check"></i> Fermer le ticket
                        </button>
                    </div>

                </div>

            </div>

        </div>

    </div>


</template>

<script>

    export default {

        name:"list-ticket",

        data() {
            return {
                games: {},
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
                    this.games = response.data;
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