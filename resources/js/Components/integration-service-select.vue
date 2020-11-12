<template>
    <div class="max-w-7xl mx-auto">
        <t-radio-group
            name="integration_service_select"
            :options=integration_services
            v-model=selected_integration_service_id
            value-attribute="id"
            text-attribute="name"
            variant="buttons"
            :disabled=!is_completed_loading
        ></t-radio-group>
    </div>
</template>

<script>
    export default {
        props: ['value'],
        data() {
            return {
                integration_services: [],
                selected_integration_service_id: undefined,
                is_completed_loading: false,
            }
        },mounted: function() {
            axios
            .get('/integrationservices/index')
            .then(response => (
                this.integration_services = response.data,
                this.selected_integration_service_id = this.value,
                this.$emit("input", this.selected_integration_service_id)
            ))
            .finally(() => (
                this.is_completed_loading = true
            ))
        },
        watch: {
            selected_integration_service_id: function(val){
                this.$emit("input", val);
            }
        },
    }
</script>
