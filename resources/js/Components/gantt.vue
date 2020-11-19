<template>
  <gantt-elastic :tasks="tasks" :options="options" :dynamic-style="dynamicStyle">
    <!-- <gantt-elastic-header slot="header"></gantt-elastic-header> -->
    <!-- <gantt-elastic-footer slot="footer"></gantt-elastic-footer> -->
  </gantt-elastic>
</template>
<script>
import GanttElastic from "gantt-elastic";
export default {
  props: {
    tasks: Array,
    options: Object,
    dynamicStyle: Object,
  },
  components: {
    ganttElastic: GanttElastic
  }
};
</script>