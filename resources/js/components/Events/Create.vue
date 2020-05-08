<template>

    <div class="card">

        <form>

            <div class="card-header">
                Créer un évenement
            </div>

            <div class="form-group">
                <label>Photo de l'évènement</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="inputGroupFile01">
                    <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                </div>
            </div>

            <div class="form-group">
                <label>Nom de l'évènement</label>
                <input type="text" class="form-control" v-model="event.name">
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea class="form-control" v-model="event.description"></textarea>
            </div>

            <div class="form-group">
                <label>Lien vers l'évènement (url)</label>
                <input type="url" class="form-control" v-model="event.url">
            </div>

            <div class="form-group">
                <label>Type d'évènement</label>
                <select class="form-control" v-model="event.type">
                    <option value="online">En ligne</option>
                    <option value="irl">IRL</option>
                </select>
            </div>

            <div class="form-group">
                <label>Fréquence</label>
                <select class="form-control" v-model="event.type">
                    <option>A lieu une fois</option>
                    <option>Toutes les semaines</option>
                    <option>Tous les mois</option>
                </select>
            </div>

        </form>

    </div>


</template>

<script>

    export default {

        name:"event-create",

        data() {
            return {
                event: {
                    type: "online"
                }
            }
        },

        methods: {

        }

    };

</script>