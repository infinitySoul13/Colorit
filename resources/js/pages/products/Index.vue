<template>
    <b-container fluid="lg">
        <notifications group="info"/>

        <div class="row mb-2">
            <div class="col-md-4 col-sm-12"><b-button v-b-toggle.collapse-1 variant="primary" class="mt-1 mb-1 w-100">Загрузить файл</b-button></div>
            <div class="col-md-4 col-sm-12">
                <b-button
                        variant="primary"
                        class="mt-1 mb-1 w-100"
                        href="https://oauth.vk.com/authorize?client_id=7727122&scope=market&redirect_uri=https://xn--80aeg0bdbz.com/products/uploadVk&response_type=code&display=page"
                >
                    Загрузить из ВК
                </b-button>
            </div>
            <div class="col-md-12 col-sm-12">
                <b-collapse id="collapse-1" class="">
                    <b-container fluid>
                        <b-row>
                            <b-col lg="12" class="my-1" v-if="!loading">
                                <div class="large-12 medium-12 small-12 filezone" >
                                    <input type="file" id="files" ref="files" v-on:change="handleFiles()" :disabled="loading"/>
                                    <p>
                                        Перетащите файл сюда <br>или нажмите для поиска
                                    </p>
                                </div>
                                <hr>
                            </b-col>
                            <b-col lg="12" class="my-1"  v-if="loading">
                                <div class="text-center text-primary my-2">
                                    <b-spinner class="align-middle"/>
                                    <strong>Загрузка...</strong>
                                </div>
                            </b-col>
                        </b-row>
                    </b-container>
                </b-collapse>
            </div>
        </div>

        <b-tabs content-class="mt-3" v-if="!initial_loading">
            <b-tab title="Все" active>
                <product-table :products="products"
                       :loading="loading"
                               :rows="products_totalRows"
                />
            </b-tab>
            <b-tab title="Удаленные">
                <product-table :products="deleted_products"
                       :loading="loading"
                               :rows="deleted_products_totalRows"
                />
            </b-tab>
        </b-tabs>
        <b-row class="m-auto w-100 h-100 align-items-center justify-content-center" v-else>
            <div class="text-center text-primary my-2">
                <b-spinner class="align-middle"/>
                <strong>Загрузка...</strong>
            </div>
        </b-row>
    </b-container>
</template>

<script>
    import ProductTable from '~/pages/products/Table'

    export default {
        components: {
            ProductTable
        },
        data() {
            return {
                loading: false,
                initial_loading: false,
            }
        },
        computed: {
            products: function () {
                return this.$store.getters.products;
            },
            deleted_products: function () {
                return this.$store.getters.deleted_products;
            },
            admin_categories: function () {
                return this.$store.getters.admin_categories;
            },
            products_totalRows: function () {
                return this.$store.getters.products_totalRows;
            },
            deleted_products_totalRows: function () {
                return this.$store.getters.deleted_products_totalRows;
            },
        },
        created() {
            this.initial_loading=true;
        },
        mounted() {
            this.loading = true;
            this.$store.dispatch('loadDataProduct').then(() => {
                this.loading = false;
            });
            this.$store.dispatch('loadDataCategory').then(() => {
                this.initial_loading=false;
            });
        },
        methods:{
            handleFiles() {
                this.loading = true;
                let uploadedFiles = this.$refs.files.files;

                for(var i = 0; i < uploadedFiles.length; i++) {
                    if (/\.(xlsx|xls|csv)$/i.test(uploadedFiles[i].name))
                    {
                        let formData = new FormData();
                        formData.append('file', uploadedFiles[i]);
                        setTimeout(this.checkStatus, 15000);
                        axios.post(`/products/upload`,
                            formData,
                            {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            })
                            .then(resp => {
                                // this.loading = false;
                                this.$store.dispatch('loadDataProduct');
                                this.$store.dispatch('loadDataCategory').then(() => {
                                    this.loading = false;
                                    this.$refs.selectableTable.refresh()
                                });
                                // this.loading = false;
                                this.sendMessage(resp.data.message);
                                // this.loadData()
                            });

                    }
                    else
                    {
                        this.loading = false;
                        this.sendMessage('Неверный формат файла', 'error');
                        break;
                    }
                }
            },
            uploadVk() {
                this.loading = true;
                axios.get(`/products/uploadVk`).then(resp=>{
                    this.sendMessage(resp.data.message);
                    this.loading = false;
                    this.$store.dispatch('loadDataProduct');
                })
            },
            sendMessage(message, type='success') {
                this.$notify({
                    group: 'info',
                    type: type,
                    title: 'Товары',
                    text: message
                });
            },
            checkStatus () {
                axios.post(`/products/status`).then(resp=>{
                    // if(resp.data.status == 1) {
                    //     this.loading = false;
                    // }
                    // else {
                    if(resp.data.status !== 1) {
                        this.sendMessage(resp.data.message);
                        setTimeout(this.checkStatus, 30000);
                    }
                })
            }
        }

    }
</script>

<style scoped>
    input[type="file"]{
        opacity: 0;
        width: 100%;
        height: 200px;
        position: absolute;
        cursor: pointer;
    }
    .filezone {
        outline: 2px dashed grey;
        outline-offset: -10px;
        background: #ccc;
        color: dimgray;
        padding: 10px 10px;
        min-height: 200px;
        position: relative;
        cursor: pointer;
    }
    .filezone:hover {
        background: #c0c0c0;
    }

    .filezone p {
        font-size: 1.2em;
        text-align: center;
        padding: 50px 50px 50px 50px;
    }
</style>
