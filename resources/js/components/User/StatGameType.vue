<script>

  import { Pie } from 'vue-chartjs'

  export default {

    extends: Pie,

    props: ['stats'],

    data () {
      return {
        datacollection: {}
      }
    },

    mounted () {
      this.fillData();
    },

    methods: {

        fillData () {

            this.datacollection = {
                labels: [],
                datasets: [{
                    'data': [],
                    'backgroundColor': [],
                    'label': []
                }]
            };

            var i;
            for (i = 0; i < this.stats.length; i++) {
                this.datacollection.labels.push( this.stats[i].game.title);
                this.datacollection.datasets[0].data.push( this.stats[i].total);
                this.datacollection.datasets[0].backgroundColor.push(this.getRandomColor());
            }
            
            this.renderChart(this.datacollection);

        },

        getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

    }
  };
  
</script>
