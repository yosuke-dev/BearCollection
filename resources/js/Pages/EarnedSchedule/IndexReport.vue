<template>
    <div>
        <div>
            <chart :labels="labels" :datasets="datasets" :title="'EVM IndexChart'" :height="540" :width="1080" />
        </div>
    </div>
</template>


<script>
import axios from "axios";
import chart from '../../Components/chart-line.vue';
export default {
    props: {
        milestone_id: Number,
    },
    data() {
        return {
            datasets: [],
            response_data: [],
            labels: [],
            disabled: false,
        }
    },
    components: {
        chart,
    },
    methods: {
        fetchData: function(){
            this.disabled = true;
            if(this.milestone_id == null){
                this.disabled = false;
                return;
            }
            axios
            .get('/earnedschedulecost/' + this.milestone_id)
            .then(response => (
                this.response_data = response.data.earnedschedules
            ))
            .catch(error => 
                this.$toast.error({
                    title:'error',
                    message:error
                })
            ).finally(() => (
                this.datasets = [],
                this.datasets.push({name: "Criteria", data: new Array(this.response_data.length).fill(1)}),
                this.datasets.push({name: "SPI", data: this.response_data.map(x => x.schedule_performance_index)}),
                this.datasets.push({name: "CPI", data: this.response_data.map(x => x.cost_performance_index)}),
                this.labels = this.response_data.map(x => x.earned_schedule_date),
                this.disabled = false
            ))
        },
    },
    mounted() {
        this.fetchData();
    },
    watch:{
        milestone_id: function(val){
            this.fetchData();
        }
    },
}
</script>