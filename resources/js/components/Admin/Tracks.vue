<template>

	<div>

        <div class="row mb-3">

            <div class="col-md-6">
                <input type="text" class="form-control" v-model="query" @keyup="fetch" placeholder="Rechercher">
            </div>

            <div class="col-md-6 text-right">
                <nav v-if="pagination.total > pagination.perPage">
                  <ul class="pagination">
                    <li class="page-item" :disabled="pagination.prevPage ? false : true"><a class="page-link" @click="gotoPage(pagination.prevPageId)" href="#" title="Précédent">
                        <i class="fas fa-chevron-left"></i></a>
                    </li>
                    <li v-if="i < 10" v-for="i in Math.ceil(pagination.total / pagination.perPage)" :key="i" class="page-item" :class="{active: i == pagination.currentPage}">
                        <a class="page-link" @click="gotoPage(i)" href="#">{{ i }}</a>
                    </li>
                    <li v-if="i == (Math.ceil(pagination.total / pagination.perPage) - 3)" v-for="i in Math.ceil(pagination.total / pagination.perPage)" :key="i" class="page-item" :class="{active: i == pagination.currentPage}">
                        <a class="page-link" @click="gotoPage(i)" href="#">...</a>
                    </li>
                    <li v-if="i > (Math.ceil(pagination.total / pagination.perPage) - 3)" v-for="i in Math.ceil(pagination.total / pagination.perPage)" :key="i" class="page-item" :class="{active: i == pagination.currentPage}">
                        <a class="page-link" @click="gotoPage(i)" href="#">{{ i }}</a>
                    </li>
                    <li class="page-item" :disabled="pagination.nextPage ? false : true">
                        <a class="page-link" @click="gotoPage(pagination.nextPageId)" href="#" title="Suivant"><i class="fas fa-chevron-right"></i></a>
                    </li>
                  </ul>
                </nav>
            </div>

        </div>

        <div class="table-responsive">

            <table class="table bg-white table-striped">
                
                <thead>
                    <tr>
                        <th>Artiste</th>
                        <th>Titre</th>
                        <th>Réponse</th>
                        <th>Extrait</th>
                        <th>Partie</th>
                        <th>Signalements</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="row in rows">
                        <td><input type="text" class="form-control" @blur="updateTrack(row)" v-model="row.artist_name"></td>
                        <td><input type="text" class="form-control" @blur="updateTrack(row)" v-model="row.track_name"></td>
                        <td><input type="text" class="form-control" @blur="updateTrack(row)" v-model="row.custom_answer"></td>
                        <td><audio :src="row.preview_url" preload="none" controls /></audio></td>
                        <td><a :href="'/games/' + row.game.id + '/edit'">{{ row.game.title }}</a></td>
                        <td style="width:20px"><input type="number" step="1" class="form-control" @blur="updateTrack(row)" v-model="row.down_rate"></td>
                        <td><button class="btn btn-sm btn-danger" @click="deleteTrack(row)"><i class="fas fa-trash"></i></button></td>
                    </tr>
                </tbody>

            </table>

        </div>



    </div>

</template>

<script>

    export default {

        name: 'track-list',

        props: ['project'],

        data(){
            return {
                rows: [],
                pagination: {
                    perPage: 20,
                    currentPage: 1,
                    total: 0,
                    nextPage: '',
                    prevPage: '',
                    nextPageId: 2,
                    prevPageId: 1,
                    firstPage: '',
                    lastPage: '',
                    from: '',
                    to: ''
                },
                query: '',
                order: 'down_rate'
            }
        },

        methods : {

            fetch() {
                axios.get('/admin/tracks/list?page=' + this.pagination.currentPage + '&paginate=' + this.pagination.perPage + '&q=' + this.query + '&order=' + this.order).then(response => {
                    this.rows = response.data.data;
                    this.pagination = {
                        perPage: this.pagination.perPage,
                        currentPage: response.data.current_page,
                        total: response.data.total,
                        nextPage: response.data.next_page_url,
                        prevPage: response.data.prev_page_url,
                        nextPageId: parseInt(response.data.current_page) + 1,
                        prevPageId: parseInt(response.data.current_page) - 1,
                        firstPage: response.data.first_page_url,
                        lastPage: response.data.last_page_url,
                        from: response.data.from,
                        to: response.data.to,
                    };
                });
            },

            changeOrder(field) {
                this.order = field;
                this.fetch();
            },

            gotoPage(id) {
                this.pagination.currentPage = id;
                this.fetch();
            },

            updateTrack(track) {
                axios.patch('/admin/tracks/' + track.id, track).then(response => {
                    this.fetch();
                })
            },

            deleteTrack(track) {
                axios.delete('/admin/tracks/' + track.id).then(response => {
                    this.fetch();
                })
            },

        },

        mounted() {
            this.fetch()
        },

    }

</script>