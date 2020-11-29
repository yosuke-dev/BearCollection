<template>
    <chart :labels="labels" :datasets="datasets" :title="'EVM CompletionChart'" />
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
                this.datasets.push({name: "Criteria(BAC)", data: this.response_data.map(x => x.budget_at_completion)}),
                this.datasets.push({name: "ETC", data: this.response_data.map(x => x.estimate_to_completion)}),
                this.datasets.push({name: "EAC", data: this.response_data.map(x => x.estimate_at_completion)}),
                this.datasets.push({name: "VAC", data: this.response_data.map(x => x.variance_at_completion)}),
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