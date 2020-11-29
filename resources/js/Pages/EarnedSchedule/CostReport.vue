<template>
    <chart :labels="labels" :datasets="datasets" :title="'EVM CostChart'" />
</template>


<script>
import axios from "axios";
import chart from '../../Components/chart-line.vue';
export default {
    props: {
        milestone_id: {
            type : Number,
            require : true,
            'default': 0
        },
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
                this.datasets.push({name: "BAC", data: this.response_data.map(x => x.budget_at_completion)}),
                this.datasets.push({name: "PV", data: this.response_data.map(x => x.planned_value)}),
                this.datasets.push({name: "AC", data: this.response_data.map(x => x.actual_cost)}),
                this.datasets.push({name: "EV", data: this.response_data.map(x => x.earned_value)}),
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
        },
        height: function(){
            this.fetchData();
        }
    },
}
</script>