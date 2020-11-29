<template>
    <line-chart :chartdata="chartData" :options="chartOptions" cssClasses="chart-style" ref="_chart"></line-chart>
</template>
<script>
import { Line as LineChart } from "vue-chartjs";
import * as palette from 'google-palette'
export default {
    props: {
        title: String,
        labels: Array,
        datasets: Array,
    },
    components: {
        LineChart
    },
    methods: {
        chartSetData: function(){
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
            return chartdata;
        },
        chartData: function(){
            const data = {
                labels: this.labels,
                datasets: this.chartSetData(),
            };
            return data;
        },
        chartOptions: function(){
            const options = {
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
                layout: {
                    padding: {
                        left: 20,
                        right: 20,
                        top: 0,
                        bottom: 0
                    }
                },
                tooltips:{
                    mode:'label' //マウスオーバー時に表示されるtooltip
                },
                responsive: true,
                maintainAspectRatio: false,
            };
            return options;
        },
        render: function(){
            this.$refs._chart.renderChart(this.chartData(), this.chartOptions());
        },
    },
    mounted() {
        this.render();
    },
    watch: {
        datasets(){
            this.render();
        },
    },
};
</script>
<style scoped>
.chart-style {
    width: 100%;
    height: 100%;
}
</style>