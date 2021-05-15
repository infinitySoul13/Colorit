<template>
    <b-container>
        <notifications group="info"/>

        <b-modal id="modal-new-category" title="Add category">
            <div class="row w-100 m-auto">
                <div class="col-12">
                    <b-form-group label="Title">
                        <b-form-input
                                v-model="new_category.title"
                                placeholder="Title"
                                class="w-100 min"
                        />
                    </b-form-group>

                </div>
            </div>

            <template v-slot:modal-footer>
                <div class="w-100">
                    <b-button
                            variant="primary"
                            class="float-right"
                            @click="createCategory"
                    >
                        Save
                    </b-button>
                    <b-button
                            variant="primary"
                            class="float-right mr-2"
                            @click="cancel"
                    >
                        Cancel
                    </b-button>
                </div>
            </template>
        </b-modal>

        <div class="row" v-if="!initial_loading">
            <div class="col-md-4 col-sm-12 mt-4 mb-4">
                <b-button variant="primary" class="mt-4 mb-4" v-b-modal.modal-new-category>Add category</b-button>
            </div>
        </div>

        <b-tabs content-class="mt-3" v-if="!initial_loading">
            <b-tab title="All" active>
                <category-table :categories="admin_categories"
                       :loading="loading"
                />
            </b-tab>
            <b-tab title="Deleted">
                <category-table :categories="deleted_categories"
                       :loading="loading"
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
    import CategoryTable from '~/pages/categories/Table'

    export default {
        components: {
            CategoryTable
        },
        data() {
            return {
                loading: false,
                new_category:{
                    title:'',
                },
                initial_loading: false,
                options:[]
            }
        },
        computed: {
            admin_categories: function () {
                return this.$store.getters.admin_categories;
            },
            deleted_categories: function () {
                return this.$store.getters.deleted_categories;
            },
        },
        created() {
            this.initial_loading=true;
        },
        mounted() {
            this.loading = true;
            this.$store.dispatch('loadDataCategory').then(() => {
                this.loading = false;
                this.initial_loading= false;
            })
        },

        methods:{
            createCategory() {
                this.$store.dispatch('addCategory', this.new_category).then(resp => {
                    if(resp.data.status =='success'){
                        this.$store.commit('addCategory', resp.data.category)
                        this.new_category = {
                            title:'',
                        }
                    }
                    else {
                        this.sendMessage(resp.data.message, 'error')
                    }

                    this.$bvModal.hide('modal-new-category')
                })
            },
            addTag(tag) {
                this.new_category.words.push(tag);
            },
            cancel() {
                this.new_category = '';
                this.$bvModal.hide('modal-new-category')
            },
            sendMessage(message, type='success') {
                this.$notify({
                    group: 'info',
                    type: type,
                    title: 'Categories',
                    text: message
                });
            },
        },
    }
</script>

<style scoped>

</style>
