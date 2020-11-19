<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                EarnedSchedule
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4 min-h-screen">
                    <div class="flex flex-wrap">
                        <div class="mx-auto py-4 sm:w-3/5 md:w-2/5 sm:px-2 lg:px-4">
                            <label for="projects" class="block text-gray-700 text-sm font-bold mb-2">Projects</label>
                            <t-rich-select
                                id="projects"
                                placeholder="please select"
                                :options=projects
                                v-model=selected_project
                                valueAttribute="id"
                                textAttribute="name"
                                :disabled=updating
                            ></t-rich-select>
                        </div>
                        <div class="mx-auto py-4 sm:w-3/5 md:w-2/5 sm:px-2 lg:px-4">
                            <label for="milestones" class="block text-gray-700 text-sm font-bold mb-2" >Milestones</label>
                            <t-rich-select
                                id="milestones"
                                placeholder="please select"
                                :options=project_milestones
                                v-model=selected_milestone
                                valueAttribute="id"
                                textAttribute="name"
                                :disabled=updating||!is_selected_project
                            ></t-rich-select>
                        </div>
                        <div class="w-1/5 mx-auto py-4 sm:px-2 lg:px-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Sync</label>
                            <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition ease-in-out duration-150 disabled:cursor-not-allowed disabled:opacity-50" :disabled="updating||!is_selected_milestone" @click="update()">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" v-if="updating">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Report Sync
                            </button>
                        </div>
                        <div class="max-w-7xl mx-auto py-4 sm:px-2 lg:px-8">
                            <gantt-milestone :milestone_id=selected_milestone v-if="!updating&&is_selected_milestone"></gantt-milestone>
                        </div>
                        <div class="max-w-7xl mx-auto py-4 sm:px-2 lg:px-8">
                            <cost-report :milestone_id=selected_milestone v-if="!updating&&is_selected_milestone"></cost-report>
                        </div>
                        <div class="max-w-7xl mx-auto py-4 sm:px-2 lg:px-8">
                            <variance-report :milestone_id=selected_milestone v-if="!updating&&is_selected_milestone"></variance-report>
                        </div>
                        <div class="max-w-7xl mx-auto py-4 sm:px-2 lg:px-8">
                            <index-report :milestone_id=selected_milestone v-if="!updating&&is_selected_milestone"></index-report>
                        </div>
                        <div class="max-w-7xl mx-auto py-4 sm:px-2 lg:px-8">
                            <completion-report :milestone_id=selected_milestone v-if="!updating&&is_selected_milestone"></completion-report>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from './../../Layouts/AppLayout'
    import CostReport from './CostReport'
    import VarianceReport from './VarianceReport'
    import IndexReport from './IndexReport'
    import CompletionReport from './CompletionReport'
    import GanttMilestone from './../Gantt/milestone'
    export default {
        components: {
            AppLayout,
            CostReport,
            VarianceReport,
            IndexReport,
            CompletionReport,
            GanttMilestone,
        },
        props: ['projects', 'milestones'],
        data() {
            return {
                selected_project: undefined,
                selected_milestone: undefined,
                updating: false,
            }
        },methods: {
            update: function () {
                this.updating = true;
                let milestone_id = this.selected_milestone;
                axios.put('/earnedschedulecost/' + milestone_id)
                .finally(() => {
                    this.updating = false;
                },);
            },
        },computed: {
            project_milestones: function () {
                return this.milestones.filter(x => x.project_id === this.selected_project)
            },
            is_selected_project: function () {
                return this.selected_project == undefined ? false : true
            },
            is_selected_milestone: function () {
                return this.selected_milestone == undefined ? false : true
            }
        },watch: {
            selected_project: function(val){
                this.selected_milestone = undefined;
            }
        },
    }
</script>
