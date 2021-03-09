<template>
    <div>
        <b-row>
            <b-col lg="6" class="my-1">
                <b-form-group
                        label="Сортировка"
                        label-cols-sm="3"
                        label-align-sm="right"
                        label-size="sm"
                        label-for="sortBySelect1"
                        class="mb-0"
                >
                    <b-input-group size="sm">
                        <b-form-select v-model="sortBy" id="sortBySelect1" :options="sortOptions" class="w-75">
                            <template v-slot:first>
                                <option value="">-- none --</option>
                            </template>
                        </b-form-select>
                        <b-form-select v-model="sortDesc" size="sm" :disabled="!sortBy" class="w-25">
                            <option :value="false">Asc</option>
                            <option :value="true">Desc</option>
                        </b-form-select>
                    </b-input-group>
                </b-form-group>
            </b-col>

            <b-col lg="6" class="my-1">
                <b-form-group
                        label="Поиск"
                        label-cols-sm="3"
                        label-align-sm="right"
                        label-size="sm"
                        label-for="filterInput"
                        class="mb-0"
                >
                    <b-input-group size="sm">
                        <b-form-input
                                v-model="filter"
                                type="search"
                                id="filterInput"
                                placeholder="Введите для поиска"
                        />
                        <b-input-group-append>
                            <b-button :disabled="!filter" @click="filter = ''">Очистить</b-button>
                        </b-input-group-append>
                    </b-input-group>
                </b-form-group>
            </b-col>

            <b-col sm="5" md="6" class="my-1">
                <b-form-group
                        label="На странице"
                        label-cols-sm="6"
                        label-cols-md="4"
                        label-cols-lg="3"
                        label-align-sm="right"
                        label-size="sm"
                        label-for="perPageSelect1"
                        class="mb-0"
                >
                    <b-form-select
                            v-model="perPage"
                            id="perPageSelect1"
                            size="sm"
                            :options="pageOptions"
                    />
                </b-form-group>
            </b-col>

            <b-col sm="7" md="6" class="my-1">
                <b-pagination
                        v-model="currentPage"
                        :total-rows="totalRows"
                        :per-page="perPage"
                        align="fill"
                        size="sm"
                        class="my-0"
                />
            </b-col>
        </b-row>
        <b-table
                show-empty
                small
                stacked="md"
                :items="categories"
                :fields="fields"
                :current-page="currentPage"
                :per-page="perPage"
                :filter="filter"
                :filterIncludedFields="filterOn"
                :sort-by.sync="sortBy"
                :sort-desc.sync="sortDesc"
                :sort-direction="sortDirection"
                @filtered="onFiltered"
                :busy="loading"
                empty-text="Нет записей для отображения"
                empty-filtered-text="Нет записей, соответствующих вашему запросу"
        >
            <template v-slot:cell(title)="data">
                <b-input-group size="sm">
                    <b-form-input :value="data.item.title"
                                  @blur="save($event.target.value,data.item.id,'title')"
                                  placeholder="Введите название категории"
                                  :disabled="data.item.title=='Другое'"
                    />
                </b-input-group>
            </template>
            <template v-slot:cell(words)="data">
                <b-input-group size="sm">
                    <multiselect
                            v-model="data.item.words"
                            tag-placeholder="Добавить слово"
                            placeholder="Введите новое слово"
                            :options="data.item.words"
                            :multiple="true"
                            :taggable="true"
                            selectedLabel="Выбрано"
                            deselectLabel="Удалить"
                            :limit="2"
                            :id="data.item"
                            @tag="addTag"
                            @remove="removeTag"
                    >
                        <template slot="limit" >
                                <span class="multiselect__single">
                                    и ещё {{ data.item.words.length-2 }} {{data.item.words.length-2 | pluralizeWords}}
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
                </b-input-group>
            </template>
            <template v-slot:cell(img)="data">
                <b-input-group size="sm">
                    <b-form-input :value="data.item.img"
                                  @blur="save($event.target.value,data.item.id,'img')"
                                  placeholder="Введите ссылку на картинку категории"
                    />
                </b-input-group>
            </template>
<!--            <template v-slot:cell(quantity)="data">-->
<!--                <b-input-group size="sm">-->
<!--                    <b-form-input :value="data.item.quantity"-->
<!--                                  type="number"-->
<!--                                  @blur="save($event.target.value,data.item.id,'quantity')"-->
<!--                                  placeholder="Введите кол-во"/>-->
<!--                </b-input-group>-->
<!--            </template>-->

            <template v-slot:cell(action)="data">
                <b-input-group size="sm">
                    <b-button v-if="data.item.deleted_at==null" @click="remove(data.item.id)" class="btn btn-info mr-1 mb-1"
                              :disabled="data.item.id===null||data.item.title=='Другое'">
                        Удалить
                    </b-button>
                    <b-button v-else @click="restore(data.item.id)" class="btn btn-info mr-1 mb-1"
                              :disabled="data.item.id===null||data.item.title=='Другое'">
                        Восстановить
                    </b-button>
                    <b-button v-if="data.item.deleted_at!=null" @click="destroy(data.item.id)" class="btn btn-info mr-1 mb-1"
                              :disabled="data.item.id===null">
                        Удалить полностью
                    </b-button>
                </b-input-group>
            </template>
            <template v-slot:table-busy>
                <div class="text-center text-primary my-2">
                    <b-spinner class="align-middle"/>
                    <strong>Загрузка...</strong>
                </div>
            </template>
        </b-table>
    </div>
</template>

<script>
    export default {
        name: "Table",
        props: {
            categories: {
                type: Array,
                required: true,
                default: function(){
                    return [];
                }
            },
            loading: {
                type: Boolean,
                default: true
            },
        },
        data() {
            return {

                sortBy: 'title',
                sortDesc: false,

                totalRows: 1,
                currentPage: 1,
                perPage: 5,
                sortDirection: 'asc',
                filter: null,
                filterOn: [],

                pageOptions: [5, 10, 15, 25, 50, 100],

                fields: [
                    {key: 'id', label: 'ID', sortable: true, sortDirection: 'desc'},
                    {key: 'title', label: 'Название', sortable: true, sortDirection: 'desc'},
                    {key: 'words', label: 'Слова для поиска', sortable: true, sortDirection: 'desc'},
                    {key: 'img', label: 'Картинка', sortable: true, sortDirection: 'desc'},
                    {key: 'products_count', label: 'Товаров в категории', sortable: true, sortDirection: 'desc'},
                    {key: 'action', label: 'Действия'}
                ],

                in_process: [],
                options:[],
            }
        },
        mounted() {
            this.totalRows = this.categories.length;
        },
        computed: {
            sortOptions() {
                return this.fields
                    .filter(f => f.sortable)
                    .map(f => {
                        return {text: f.label, value: f.key}
                    })
            },
        },
        methods: {
            sendMessage(message) {
                this.$notify({
                    group: 'message',
                    type: 'success',
                    title: 'Категории',
                    text: message
                });
            },
            remove(id) {
                this.$store.dispatch('removeCategory', id).then(resp => {
                    this.sendMessage(resp.data.message)
                    this.totalRows = this.categories.length
                })
            },
            restore(id) {
                this.$store.dispatch('restoreCategory', id)
                    .then(resp => {
                        this.sendMessage(resp.data.message)
                        this.totalRows = this.categories.length
                    });
            },
            destroy(id) {
                this.$store.dispatch('forceDeleteCategory', id).then(resp => {
                    this.sendMessage(resp.data.message)
                    this.totalRows = this.categories.length
                })
            },
            async save(value, id, key) {
                await this.$store.dispatch('saveCategory', {
                    id: id,
                    key: key,
                    value: value
                }).then(resp => {
                    this.$store.commit('saveCategory', resp.data.category);
                    this.sendMessage(resp.data.message)
                });
            },
            onFiltered(filteredItems) {
                this.totalRows = filteredItems.length;
                this.currentPage = 1
            },
            addTag(tag, item){
                item.words.push(tag);
                this.save(item.words, item.id, 'words')
            },
            removeTag(tag, item){
                var index = item.words.findIndex(x => x === tag)
                item.words.splice(index, 1);
                this.save(item.words, item.id, 'words')
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