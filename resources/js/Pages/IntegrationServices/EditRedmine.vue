<template>
    <div>
        <button @click="openModal()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50">Setting</button>
        <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400" v-show="isShow">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <!-- This element is to trick the browser into centering the modal contents. -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                    <div v-if="post_put_response.isResponse">
                        <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" role="alert" v-if="post_put_response.isSuccess">
                            <div class="flex">
                            <div>
                                <p class="text-sm">{{ post_put_response.message }}</p>
                            </div>
                            </div>
                        </div>
                        <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md my-3" role="alert" v-if="!post_put_response.isSuccess">
                            <div class="flex">
                            <div>
                                <p class="text-sm">{{ post_put_response.message }}</p>
                            </div>
                            </div>
                        </div>
                    </div>
                    <h3 class="px-4 pt-5 font-semibold text-xl text-gray-800 leading-tight">
                        RedmineSetting​
                    </h3>
                    <form>
                        <div class="bg-white px-4 pt-5 pb-6 sm:p-8 sm:pb-6">
                            <div class="mb-4">
                                <label for="Url" class="block text-gray-700 text-sm font-bold mb-2">Url:</label>
                                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="Url" placeholder="Enter Url" v-model="form.url" :disabled="disabled">
                                <div v-if="$page.errors.url" class="text-red-500">{{ $page.errors.url[0] }}</div>
                            </div>
                            <div class="mb-4">
                                <label for="ApiKey" class="block text-gray-700 text-sm font-bold mb-2">ApiKey:</label>
                                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="ApiKey" placeholder="Enter ApiKey" v-model="form.api_key" :disabled="disabled">
                                <div v-if="$page.errors.api_key" class="text-red-500">{{ $page.errors.api_key[0] }}</div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                <button wire:click.prevent="store()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5 disabled:cursor-not-allowed disabled:opacity-50" v-show="!editMode" :disabled="disabled" @click="save(form)">
                                Save
                                </button>
                            </span>
                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                <button wire:click.prevent="store()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5 disabled:cursor-not-allowed disabled:opacity-50" v-show="editMode" :disabled="disabled" @click="update(form)">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" v-if="disabled">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Update
                                </button>
                            </span>
                            <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                <button @click="closeModal()" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5 disabled:opacity-50" :disabled="disabled">
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
        name: 'editredmine',
        props: ['integration_setting_id'],
        data() {
            return {
                isShow: false,
                editMode: false,
                disabled: false,
                post_put_response: {
                    message: null,
                    isSuccess: false,
                    isResponse: false,
                },
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
                this.disabled = true;
                this.post_put_response.isResponse = false;
                let result = {
                    isSuccess: false,
                    message: ""
                };
                axios.post('/integrationredminesettings/', data)
                .then(function (response) {
                    result.isSuccess = true;
                    result.message = response.data.message;
                },)
                .catch(function (error) {
                    result.isSuccess = false;
                    result.message = error;
                })
                .finally(() => {
                    this.post_put_response.isResponse = true;
                    this.post_put_response.isSuccess = result.isSuccess;
                    this.editMode = result.isSuccess;
                    this.post_put_response.message = result.message;
                    this.disabled = false;
                },);
            },
            update: function (data) {
                this.disabled = true;
                this.post_put_response.isResponse = false;
                let result = {
                    isSuccess: false,
                    message: ""
                };
                axios.put('/integrationredminesettings/' + data.id, data)
                .then(function (response) {
                    result.isSuccess = true;
                    result.message = response.data.message;
                },)
                .catch(function (error) {
                    result.isSuccess = false;
                    result.message = error;
                })
                .finally(() => {
                    this.post_put_response.isResponse = true;
                    this.post_put_response.isSuccess = result.isSuccess;
                    this.post_put_response.message = result.message;
                    this.disabled = false;
                },);
            },
            fetchData: function(){
                this.disabled = true;
                if(this.integration_setting_id == null){
                    this.disabled = false;
                    return;
                }
                axios
                .get('/integrationredminesettings/' + this.integration_setting_id)
                .then(response => {
                    if(response.data != null){
                        this.form.id = response.data.id;
                        this.form.url = response.data.url;
                        this.form.api_key = response.data.api_key;
                        this.editMode = true;
                    }
                })
                .finally(() => {
                    this.disabled = false;
                })
            },
        },
        mounted() {
            this.fetchData();
        },
    }
</script>
