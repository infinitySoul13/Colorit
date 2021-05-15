<template>
    <b-container fluid="lg">
<!--        <div class="row mb-2">-->
<!--&lt;!&ndash;            <div class="col-md-4 col-sm-12"><b-button v-b-toggle.collapse-1 variant="primary" class="mt-1 mb-1 w-100">Загрузить файл</b-button></div>&ndash;&gt;-->
<!--&lt;!&ndash;            <div class="col-md-4 col-sm-12">&ndash;&gt;-->
<!--&lt;!&ndash;                <b-button&ndash;&gt;-->
<!--&lt;!&ndash;                        variant="primary"&ndash;&gt;-->
<!--&lt;!&ndash;                        class="mt-1 mb-1 w-100"&ndash;&gt;-->
<!--&lt;!&ndash;                        href="https://oauth.vk.com/authorize?client_id=7727122&scope=market&redirect_uri=https://xn&#45;&#45;80aeg0bdbz.com/products/uploadVk&response_type=code&display=page"&ndash;&gt;-->
<!--&lt;!&ndash;                >&ndash;&gt;-->
<!--&lt;!&ndash;                    Загрузить из ВК&ndash;&gt;-->
<!--&lt;!&ndash;                </b-button>&ndash;&gt;-->
<!--&lt;!&ndash;            </div>&ndash;&gt;-->
<!--&lt;!&ndash;            <div class="col-md-12 col-sm-12">&ndash;&gt;-->
<!--&lt;!&ndash;                <b-collapse id="collapse-1" class="">&ndash;&gt;-->
<!--&lt;!&ndash;                    <b-container fluid>&ndash;&gt;-->
<!--&lt;!&ndash;                        <b-row>&ndash;&gt;-->
<!--&lt;!&ndash;                            <b-col lg="12" class="my-1" v-if="!loading">&ndash;&gt;-->
<!--&lt;!&ndash;                                <div class="large-12 medium-12 small-12 filezone" >&ndash;&gt;-->
<!--&lt;!&ndash;                                    <input type="file" id="files" ref="files" v-on:change="handleFiles()" :disabled="loading"/>&ndash;&gt;-->
<!--&lt;!&ndash;                                    <p>&ndash;&gt;-->
<!--&lt;!&ndash;                                        Перетащите файл сюда <br>или нажмите для поиска&ndash;&gt;-->
<!--&lt;!&ndash;                                    </p>&ndash;&gt;-->
<!--&lt;!&ndash;                                </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                <hr>&ndash;&gt;-->
<!--&lt;!&ndash;                            </b-col>&ndash;&gt;-->
<!--&lt;!&ndash;                            <b-col lg="12" class="my-1"  v-if="loading">&ndash;&gt;-->
<!--&lt;!&ndash;                                <div class="text-center text-primary my-2">&ndash;&gt;-->
<!--&lt;!&ndash;                                    <b-spinner class="align-middle"/>&ndash;&gt;-->
<!--&lt;!&ndash;                                    <strong>Загрузка...</strong>&ndash;&gt;-->
<!--&lt;!&ndash;                                </div>&ndash;&gt;-->
<!--&lt;!&ndash;                            </b-col>&ndash;&gt;-->
<!--&lt;!&ndash;                        </b-row>&ndash;&gt;-->
<!--&lt;!&ndash;                    </b-container>&ndash;&gt;-->
<!--&lt;!&ndash;                </b-collapse>&ndash;&gt;-->
<!--&lt;!&ndash;            </div>&ndash;&gt;-->
<!--        </div>-->
        <b-row class="m-auto w-100 h-100 align-items-center" v-if="!initial_loading">
            <b-button variant="primary" class="mt-2 mb-2 float-right" :href="'admin/products/create'">
                Create product
            </b-button>
        </b-row>
        <b-tabs content-class="mt-3" v-if="!initial_loading">
            <b-tab title="All" active>
                <product-table :products="products"
                       :loading="loading"
                               :rows="products_totalRows"
                />
            </b-tab>
            <b-tab title="Deleted">
                <product-table :products="deleted_products"
                       :loading="loading"
                               :rows="deleted_products_totalRows"
                />
            </b-tab>
        </b-tabs>
        <b-row class="m-auto w-100 h-100 align-items-center justify-content-center" v-else>
            <div class="text-center text-primary my-2">
                <b-spinner class="align-middle"/>
                <strong>Loading...</strong>
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
            sendMessage(message, type='success') {
                this.$notify({
                    group: 'info',
                    type: type,
                    title: 'Products',
                    text: message
                });
            },

        }

    }
</script>

<style>
    .custom-select option {
        color: black !important;
    }
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
