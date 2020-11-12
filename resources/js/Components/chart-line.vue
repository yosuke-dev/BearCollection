<script>
import { Line } from "vue-chartjs";
import * as palette from 'google-palette'
export default {
    extends: Line,
    props: {
        title: String,
        labels: Array,
        datasets: Array,
    },
    methods: {
        render(){
            const colors = palette('mpn65', this.datasets.length).map((hex) => {
                return '#' + hex;
            });
            const chartdata = this.datasets.map((data, i, a) => {
                return {
                    label: data.name,
                    data: data.data,
                    backgroundColor: colors[i] + '11',  // 塗りは少し透明に
                    borderColor: colors[i],
                    borderWidth: 2,
                    fill: false
                };
            });
            this.renderChart(
                {
                    labels: this.labels,
                    datasets: chartdata,
                },
                {
                    title: {
                        display: true,
                        text: this.title, //グラフの見出し
                        padding:3
                    },
                    scales: {
                        xAxes: [{
                                stacked: false,
                                categoryPercentage:0.8 //棒グラフの太さ
                        }],
                        yAxes: [{
                                stacked: false
                        }]
                    },
                    legend: {
                        labels: {
                                boxWidth:30,
                                padding:20 //凡例の各要素間の距離
                        },
                        display: true
                    },
                    tooltips:{
                        mode:'label' //マウスオーバー時に表示されるtooltip
                    },
                }
            );
        }
    },watch: {
        datasets(){
            this.render();
        }
    },
};
</script>