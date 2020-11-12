<script>
import { Bar } from "vue-chartjs";
import * as palette from 'google-palette'
export default {
    extends: Bar,
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
                    borderWidth:1
                };
            });
            this.renderChart(
                {
                    labels: this.labels,
                    datasets: chartdata
                },
                {
                    title: {
                        display: true,
                        text: this.title, //グラフの見出し
                        padding:3
                    },
                    scales: {
                        xAxes: [{
                                stacked: true, //積み上げ棒グラフにする設定
                                categoryPercentage:0.4 //棒グラフの太さ
                        }],
                        yAxes: [{
                                stacked: true //積み上げ棒グラフにする設定
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
                    }
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