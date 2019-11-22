<script>

  import { Line } from 'vue-chartjs'

  export default {

    extends: Line,

    props: ['datas'],

    data () {
      return {
        datacollection: {},
        labels: [],
        visitors: [],
        pageViews: [],
        options: {
            responsive: true,
            maintainAspectRatio: false,
            hoverMode: 'index',
            stacked: false,
            title: {
                display: false,
                text: 'Visiteurs et pages vues'
            },
            scales: {
                yAxes: [{
                    type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                    display: true,
                    position: 'left',
                    id: 'y-axis-1',
                }, {
                    type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                    display: true,
                    position: 'right',
                    id: 'y-axis-2',

                    // grid line settings
                    gridLines: {
                        drawOnChartArea: false, // only want the grid lines for one axis to show up
                    },
                }],
            }
        }
      }
    },

    mounted () {

        console.log(this.datas);

      this.fillData();
    },

    methods: {

        fillData () {

            this.datacollection = {
                labels: this.labels,
                datasets: [{
                    label: 'Visiteurs',
                    borderColor: '#1abc9c',
                    backgroundColor: '#1abc9c',
                    fill: false,
                    data: this.visitors,
                    yAxisID: 'y-axis-1',
                }, {
                    label: 'Pages vues',
                    borderColor: '#2c3e50',
                    backgroundColor: '#2c3e50',
                    fill: false,
                    data: this.pageViews,
                    yAxisID: 'y-axis-2'
                }]
            };



            var i;
            for (i = 0; i < this.datas.length; i++) {
                this.labels.push( moment(this.datas[i].date).format("D/M/YYYY"));
                this.visitors.push( this.datas[i].visitors);
                this.pageViews.push( this.datas[i].pageViews);
            }
            
            console.log(this.pageViews);
            this.renderChart(this.datacollection, this.options);

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
  }
</script>

<style scoped>


</style>
