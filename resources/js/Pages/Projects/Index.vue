<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Projects
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <t-rich-select
                    placeholder="please select"
                    :options=projects
                    v-model=selected_project
                    valueAttribute="id"
                    textAttribute="name"
                ></t-rich-select>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from './../../Layouts/AppLayout'
    export default {
        components: {
            AppLayout,
        },
        props: ['projects', 'versions'],
        data() {
            return {
                headers: ["Id", "Name", "Identifier"],
                selected_project: undefined
            }
        },
    }
</script>
