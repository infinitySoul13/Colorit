<template>
    <b-container>
        <notifications group="info"/>

        <b-modal id="modal-new-category" title="Добавить категорию">
            <div class="row w-100 m-auto">
                <div class="col-12">
                    <b-form-group label="Название категории">
                        <b-form-input
                                v-model="new_category.title"
                                placeholder="Введите название категории"
                                class="w-100 min"
                        />
                    </b-form-group>

                </div>
                <div class="col-12">
                    <b-form-group label="Слова для поиска">
                        <multiselect
                                v-model="new_category.words"
                                tag-placeholder="Добавить слово"
                                placeholder="Введите новое слово"
                                :options="new_category.words"
                                selectedLabel="Выбрано"
                                deselectLabel="Удалить"
                                :limit="2"
                                :multiple="true"
                                :taggable="true"
                                @tag="addTag"
                        >
                            <template slot="limit" >
                                <span class="multiselect__single">
                                    и ещё {{ new_category.words.length-2 }} {{new_category.words.length-2 | pluralizeWords}}
                                </span>
                            </template>
                            <template slot="noOptions" >
                                <span class="multiselect__single">
                                   Введите новое слово
                                </span>
                            </template>
                            <template slot="selection" slot-scope="{ values, search, isOpen }">
                                <span class="multiselect__single" v-if="values.length>3 && !isOpen">
                                    {{ values.length }} {{values.length | pluralizeWords}} выбрано
                                </span>
                            </template>
                        </multiselect>
                    </b-form-group>
                </div>
                <div class="col-12">
                    <b-form-group label="Ссылку на картинку категории">
                        <b-form-input
                            v-model="new_category.img"
                            placeholder="Введите ссылку на картинку категории"
                            class="w-100"
                        >
                        </b-form-input>
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
                        Сохранить
                    </b-button>
                    <b-button
                            variant="primary"
                            class="float-right mr-2"
                            @click="cancel"
                    >
                        Отмена
                    </b-button>
                </div>
            </template>
        </b-modal>

        <div class="row">
            <div class="col-md-4 col-sm-12 mt-4 mb-4">
                <b-button variant="primary" class="mt-4 mb-4" v-b-modal.modal-new-category>Добавить категорию</b-button>
            </div>
        </div>

        <b-tabs content-class="mt-3" v-if="!initial_loading">
            <b-tab title="Все" active>
                <category-table :categories="admin_categories"
                       :loading="loading"
                />
            </b-tab>
            <b-tab title="Удаленные">
                <category-table :categories="deleted_categories"
                       :loading="loading"
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
                    words: [],
                    img:'/assets/images/cat.png'
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
                            words: [],
                            img:'/assets/images/cat.png'
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
            sendMessage(message, type) {
                this.$notify({
                    group: 'info',
                    type: type,
                    title: 'Оповещение АВТОДОН',
                    text: message
                });
            },
        },
        filters: {
            pluralizeWords: function (n) {
                if (n === 1) {
                    return 'слово'
                }
                if (n > 1 && n < 5) {
                    return 'слова'
                }
                if (n > 4 && n < 20) {
                    return 'слов'
                }
                if (n > 19) {
                    var last = n % 10;
                    if (last === 1) {
                        return 'слово'
                    }
                    if (last > 1 && last < 5) {
                        return 'слова'
                    }
                    if (last > 4 && last < 10 && last === 0) {
                        return 'слов'
                    }
                    return 'слов'
                }
                return 'слов'
            },

        }

    }
</script>

<style scoped>

</style>
