<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <grid-layout
            :layout.sync="layout"
            :col-num="4"
            :row-height="60"
            :is-draggable="true"
            :is-resizable="true"
            :is-mirrored="false"
            :vertical-compact="true"
            :margin="[10, 10]"
            :use-css-transforms="true"
            >

            <grid-item v-for="item in layout"
                :x="item.x"
                :y="item.y"
                :w="item.w"
                :h="item.h"
                :i="item.i"
                :key="item.i"
                @resized="resizedEvent"
            >
                <t-card variant="full">
                    <component
                        :is="item.component"
                        :milestone_id="item.id"
                        ref="my_component"
                    >
                    </component>
                </t-card>
            </grid-item>
        </grid-layout>
    </app-layout>
</template>

<script>
    import AppLayout from './../../Layouts/AppLayout'
    import CostReport from './../EarnedSchedule/CostReport'
    import VarianceReport from './../EarnedSchedule/VarianceReport'
    import IndexReport from './../EarnedSchedule/IndexReport'
    import CompletionReport from './../EarnedSchedule/CompletionReport'
    import GanttMilestone from './../Gantt/milestone'
    import VueGridLayout from 'vue-grid-layout'

    export default {
        components: {
            AppLayout,
            GridLayout: VueGridLayout.GridLayout,
            GridItem: VueGridLayout.GridItem,
            CostReport,
            VarianceReport,
            IndexReport,
            CompletionReport,
            GanttMilestone,
        },
        data() {
            return {
                option: {
                    
                },
                layout: [
                    {"x":0,"y":0,"w":4,"h":8,"i":"0","component":"GanttMilestone","id":2954},
                    {"x":0,"y":8,"w":1,"h":4,"i":"1","component":"CostReport","id":2954},
                    {"x":1,"y":8,"w":1,"h":4,"i":"2","component":"VarianceReport","id":2954},
                    {"x":2,"y":8,"w":1,"h":4,"i":"3","component":"IndexReport","id":2954},
                    {"x":3,"y":8,"w":1,"h":4,"i":"4","component":"CompletionReport","id":2954}
                ],
            }
        },
        methods: {
            resizedEvent: function(i, newH, newW, newHPx, newWPx){
                const isGanttMilestone = this.layout.filter(x => x.i == i && x.component == 'GanttMilestone').length >= 1;
                if(isGanttMilestone){
                    this.$refs.my_component[i].handleResize(newHPx);
                }
            },
        },
    }
</script>
