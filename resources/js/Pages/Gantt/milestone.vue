<template>
    <div class="gantt-style" ref="gantt_wrapper">
        <gantt :tasks="tasks" :options="options" :dynamic-style="dynamicStyle" v-show="!disabled"/>
    </div>
</template>

<script>
import axios from "axios";
import gantt from '../../Components/gantt.vue';

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
            tasks: [{
              id: 1,
              label: '',
              user:'',
              start: this.formatDate(new Date(), 'yyyy/MM/dd'),
              duration: 0,
              progress: 0,
              type: 'project',
            }],
            dynamicStyle: {
              'task-list-header-label': {
                'font-weight': 'bold'
              }
            },
            options: {
              maxHeight: 0,
              row: {
                height: 24,
              },
              calendar: {
                hour: {
                  display: false,
                },
              },
              chart: {
                progress: {
                  bar: false,
                },
                expander: {
                  display: false,
                },
              },
              taskList: {
                columns: [
                  {
                    id: 1,
                    label: 'ID',
                    value: 'id',
                    width: 52,
                  },
                  {
                    id: 2,
                    label: 'Description',
                    value: 'label',
                    width: 180,
                  },
                  {
                    id: 3,
                    label: 'Assigned to',
                    value: 'user',
                    width: 78,
                    html: true,
                  },
                  {
                    id: 3,
                    label: 'Start',
                    value: (task) => this.formatDate(new Date(task.start), 'yyyy/MM/dd'),
                    width: 78,
                  },
                  {
                    id: 4,
                    label: 'Tracker',
                    value: 'tracker',
                    width: 68,
                  },
                  {
                    id: 5,
                    label: '%',
                    value: 'progress',
                    width: 35,
                    style: {
                      'task-list-header-label': {
                        'text-align': 'center',
                        width: '100%',
                      },
                      'task-list-item-value-container': {
                        'text-align': 'center',
                        width: '100%',
                      },
                    },
                  },
                ],
              },
              locale:{
                name: 'ja', // name String
              },
              times: {
                timeZoom: 21,
              }
            },
            disabled: true,
        }
    },
    components: {
        gantt,
    },
    methods: {
        fetchData: function(){
            this.disabled = true;
            if(this.milestone_id == null){
                this.disabled = false;
                return;
            }
            axios
            .get('/ganttmilestone/' + this.milestone_id)
            .then(response => (
                this.response_data = response.data
            ))
            .catch(error => 
                this.$toast.error({
                    title:'error',
                    message:error
                })
            ).finally(() => (
                this.tasks = this.response_data,
                this.disabled = false,
                this.handleResize()
            ))
        },
        formatDate: function(date, format) {
            format = format.replace(/yyyy/g, date.getFullYear());
            format = format.replace(/MM/g, ('0' + (date.getMonth() + 1)).slice(-2));
            format = format.replace(/dd/g, ('0' + date.getDate()).slice(-2));
            format = format.replace(/HH/g, ('0' + date.getHours()).slice(-2));
            format = format.replace(/mm/g, ('0' + date.getMinutes()).slice(-2));
            format = format.replace(/ss/g, ('0' + date.getSeconds()).slice(-2));
            format = format.replace(/SSS/g, ('00' + date.getMilliseconds()).slice(-3));
            return format;
        },
        handleResize(height) {
            let setHeight = 0;
            if(height == null){
                setHeight = this.$refs.gantt_wrapper.clientHeight - 60;
            }else{
                setHeight = height - 100;
            }
            this.options.maxHeight = setHeight ;
        }
    },
    mounted() {
        this.fetchData();
    },
    watch:{
        milestone_id: function(val){
            this.fetchData();
        },
    },
}
</script>
<style scoped>
.gantt-style {
    width: 100%;
    height: 100%;
}
</style>