<template>

    <div>

        <div v-if="exists" class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Bonne idée!</strong> Mais bon, qu'est ce que tu crois, il est déjà dans la liste ;)
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div v-if="success" class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Hop!</strong> Le titre {{ success.track_name }} est ajouté dans {{ game.title }}.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <input type="text" class="form-control form-control-lg" placeholder="Recherche sur Deezer..." v-model="query" @keyup="searchFromDeezer">

        <div class="panel-footer text-dark" v-if="results.length">
            <ul class="list-group result-list">
                <li class="list-group-item d-flex justify-content-between align-items-center" v-for="result in results">
                    {{ result.artist.name }} - {{ result.title }}
                    <button @click="addtrack(result)" class="btn btn-sm btn-success">Ajouter</button>
                </li>
            </ul>
        </div>

    </div>


</template>

<script>

    export default {

        name:"add-track",

        props:['game'],

        data() {
            return {
                query: '',
                results: [],
                debounce: null,
                success: null,
                exists: null,
            }
        },

        methods: {

            searchFromDeezer() {

                this.results = [];

                clearTimeout(this.debounce);

                this.debounce = setTimeout(() => {

                    if (this.query.length > 2) {

                        this.errors = [];

                        axios.get('/api/media/search?q=' + this.query).then((response) => {
                            this.results = response.data.data;
                        }).catch((error) => {
                            console.warn(error);
                        });

                    }

                }, 400)

            },

            addtrack(track) {
                this.success = null;
                this.exists = null;
                this.results = [];
                this.query = '';
                const app = this;
                var post = {
                    'game_id': this.game.id,
                    'provider_item_id': track.id,
                    'provider': 'deezer',
                    'artist_name': track.artist.name,
                    'track_name': track.title_short,
                    'artwork_url': track.album.cover_medium,
                    'preview_url': track.preview,
                    'filetype': 'mp3'
                };
                axios.post('/games/'+this.game.id+'/tracks', post).then((response) => {
                    if (response.data == true) this.exists = true;
                    else this.success = response.data;
                }).catch((error) => {
                    console.warn(error);
                });
            },

        }

    };

</script>