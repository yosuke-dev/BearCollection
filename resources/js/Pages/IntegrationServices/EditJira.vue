<template>
    <div>
        <button @click="openModal()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Setting</button>
        <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400" v-show="isShow">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <!-- This element is to trick the browser into centering the modal contents. -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                    <h3 class="px-4 pt-5 font-semibold text-xl text-gray-800 leading-tight">
                        JiraSetting​
                    </h3>
                    <form>
                        <div class="bg-white px-4 pt-5 pb-6 sm:p-8 sm:pb-6">
                            <div class="mb-4">
                                <label for="Url" class="block text-gray-700 text-sm font-bold mb-2">Url:</label>
                                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="Url" placeholder="Enter Url" v-model="form.url">
                                <div v-if="$page.errors.url" class="text-red-500">{{ $page.errors.url[0] }}</div>
                            </div>
                            <div class="mb-4">
                                <label for="ApiKey" class="block text-gray-700 text-sm font-bold mb-2">ApiKey:</label>
                                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="ApiKey" placeholder="Enter ApiKey" v-model="form.api_key">
                                <div v-if="$page.errors.api_key" class="text-red-500">{{ $page.errors.api_key[0] }}</div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                <button wire:click.prevent="store()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" v-show="!editMode" @click="save(form)">
                                Save
                                </button>
                            </span>
                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                <button wire:click.prevent="store()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" v-show="editMode" @click="update(form)">
                                Update
                                </button>
                            </span>
                            <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                <button @click="closeModal()" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                Close
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['integration_setting_id'],
        data() {
            return {
                isShow: false,
                editMode: false,
                disabled: true,
                response_data: null,
                form: {
                    id: null,
                    integration_setting_id: this.integration_setting_id,
                    url: null,
                    api_key: null,
                },
            }
        },
        methods: {
            openModal: function () {
                this.isShow = true;
            },
            closeModal: function () {
                this.isShow = false;
            },
            save: function (data) {
                this.$inertia.post('/integrationredminesettings', data);
                this.closeModal();
                this.editMode = true;
            },
            update: function (data) {
                data._method = 'PUT';
                this.$inertia.post('/integrationredminesettings/' + data.id, data);
                this.closeModal();
            },
            fetchData: function(){
                if(this.integration_setting_id == null){
                    this.disabled = false;
                    return;
                }
                axios
                .get('/integrationredminesettings/' + this.integration_setting_id)
                .then(response => (
                    this.response_data = response.data[0]
                ))
                .catch(error => 
                    this.$toast.error({
                        title:'error',
                        message:error
                    })
                ).finally(() => {
                    if(this.response_data != null){
                        this.form.id = this.response_data.id;
                        this.form.url = this.response_data.url;
                        this.form.api_key = this.response_data.api_key;
                        this.editMode = true;
                    }
                    this.disabled = false;
                })
            },
        },
        mounted() {
            this.fetchData();
        },
    }
</script>
