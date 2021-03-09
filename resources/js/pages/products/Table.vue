<template>
    <div>
        <b-modal :id="modal" title="Изменить категорию" v-if="admin_categories.length>0">
            <b-form-select
                    v-model="category"
                    value-field="title"
                    text-field="title"
                    :options="admin_categories"
            >
            </b-form-select>
            <template v-slot:modal-footer>
                <div class="w-100">
                    <b-button
                            variant="primary"
                            class="float-right"
                            @click="saveAll(category, 'category')"
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
            <b-col md="12" class="my-1" v-if="selected.length>1">
                <b-row class="m-auto p-3" style="border-top: 1px solid #d8dbe0;">
                    <b-col md="4">
                        <b-button @click="selectAllRows" variant="primary" class="mr-1 mb-1">
                            Выбрать все
                        </b-button>
                    </b-col>
                    <b-col md="4">
                        <b-button @click="clearSelected" variant="primary" class="mr-1 mb-1">
                            Отменить выделение
                        </b-button>
                    </b-col>
                    <b-col md="4">
                        <b-button v-if="products[0].deleted_at==null" @click="removeAll" variant="primary" class="mr-1 mb-1">
                            Удалить выделенные
                        </b-button>
                        <b-button v-else @click="restoreAll" variant="primary" class="mr-1 mb-1">
                            Восстановить выделенные
                        </b-button>
                    </b-col>
                    <b-col md="4">
                        <b-button @click="setInitialCategory" variant="primary" class="mt-1 mb-1">Изменить категорию у выделенных</b-button>
                    </b-col>
                    <b-col md="4">
                        <b-button @click="saveAll(true, 'is_active')" variant="primary" class="mt-1 mb-1">Показать выделенные на сайте</b-button>
                    </b-col>
                    <b-col md="4">
                        <b-button @click="saveAll(false, 'is_active')" variant="primary" class="mt-1 mb-1">Скрыть выделенные на сайте</b-button>
                    </b-col>
                </b-row>
            </b-col>
        </b-row>
        <b-table
                show-empty
                small
                stacked="md"
                :items="products"
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
                selectable
                :select-mode="'multi'"
                @row-selected="onRowSelected"
                ref="selectableTable"
                class="stacked"
        >
            <template #cell(selected)="{ rowSelected }">
                <template v-if="rowSelected">
                    <span aria-hidden="true">&check;</span>
                    <span class="sr-only">Selected</span>
                </template>
                <template v-else>
                    <span aria-hidden="true">&nbsp;</span>
                    <span class="sr-only">Not selected</span>
                </template>
            </template>
            <template v-slot:cell(title)="data">
                <b-input-group size="sm">
                    <b-form-input :value="data.item.title"
                                  @blur="save($event.target.value,data.item.id,'title')"
                                  placeholder="Введите название товара"
                                  class="w-100 min"
                    />
                </b-input-group>
            </template>
            <template v-slot:cell(category)="data">
                <b-input-group size="sm">
                    <b-form-select
                            v-model="data.item.category"
                            value-field="title"
                            text-field="title"
                            :options="admin_categories"
                            @change="save(data.item.category,data.item.id,'category')"
                    >
                    </b-form-select>
                </b-input-group>
            </template>
            <template v-slot:cell(brand)="data">
                <b-input-group size="sm">
                    <b-form-input
                            v-model="data.item.brand"
                            type="text"
                            @blur="save($event.target.value, data.item.id,'brand')"
                    >
                    </b-form-input>
                </b-input-group>
            </template>
            <template v-slot:cell(quantity)="data">
                <b-input-group size="sm">
                    <b-form-input :value="data.item.quantity"
                                  type="number"
                                  @blur="save($event.target.value,data.item.id,'quantity')"
                                  placeholder="Введите кол-во"/>
                </b-input-group>
            </template>
            <template v-slot:cell(price)="data">
                <b-input-group size="sm">
                    <b-form-input :value="data.item.price"
                                  type="number"
                                  @blur="save($event.target.value,data.item.id,'price')"
                                  placeholder="Введите цену"/>
                </b-input-group>
            </template>
            <template v-slot:cell(from_vk)="data">
                <span class="w-100 text-center m-auto" v-if="data.item.from_vk">Да</span>
                <span class="w-100 text-center m-auto" v-else>Нет</span>
            </template>
            <template v-slot:cell(action)="data">
                <b-input-group size="sm" class="w-100">
                    <b-button @click="data.toggleDetails" class="w-100 btn btn-travel mr-1 mb-1 btn-travel">
                        {{ data.detailsShowing ? 'Свернуть' : 'Развернуть' }}
                    </b-button>

                    <b-button v-if="data.item.is_active" @click="changeIsActive(false, data.item,'is_active')" class="btn btn-info w-100 mr-1 mb-1"
                              :disabled="data.item.id===null">
                        Скрыть
                    </b-button>
                    <b-button v-else @click="changeIsActive(true, data.item,'is_active')" class="btn btn-info w-100 mr-1 mb-1"
                              :disabled="data.item.id===null">
                        Показать
                    </b-button>
                    <b-button v-if="data.item.deleted_at==null" @click="remove(data.item.id)" class="btn btn-info w-100 mr-1 mb-1"
                              :disabled="data.item.id===null">
                        Удалить
                    </b-button>
                    <b-button v-else @click="restore(data.item.id)" class="btn btn-info w-100 mr-1 mb-1"
                              :disabled="data.item.id===null">
                        Восстановить
                    </b-button>
                    <b-button v-if="data.item.deleted_at!=null" @click="destroy(data.item.id)" class="btn btn-info w-100 mr-1 mb-1"
                              :disabled="data.item.id===null">
                        Удалить полностью
                    </b-button>
                </b-input-group>
            </template>
            <template v-slot:row-details="row">
                <b-card>
                    <div class="row align-items-center justify-content-center m-auto">
                        <div class="col-sm-12 col-md-6 mb-2">
                            <b-form-group label="Номер производителя" label-size="sm" class="mb-0">
                            <b-form-input :value="row.item.manufacturer_number"
                                          type="text"
                                          @blur="save($event.target.value,row.item.id,'manufacturer_number')"
                                          placeholder="Введите номер производителя"/>
                            </b-form-group>
                        </div>
                        <div class="col-sm-12 col-md-6 mb-2">
                            <b-form-group label="Оригинальный номер" label-size="sm" class="mb-0">
                            <b-form-input :value="row.item.original_number"
                                          type="text"
                                          @blur="save($event.target.value,row.item.id,'original_number')"
                                          placeholder="Введите оригинальный номер"/>
                            </b-form-group>
                        </div>
                        <div class="col-sm-12 col-md-6 mb-2">
                            <b-form-group label="Мин. в упаковке" label-size="sm" class="mb-0">
                            <b-form-input :value="row.item.min_in_pack"
                                          type="text"
                                          @blur="save($event.target.value,row.item.id,'min_in_pack')"
                                          placeholder="Введите минимальное кол-во в упаковке"/>
                            </b-form-group>
                        </div>
                        <div class="col-sm-12 col-md-6 mb-2">
                            <b-form-group label="Единицы измерения" label-size="sm" class="mb-0">
                            <b-form-input :value="row.item.units"
                                          type="text"
                                          @blur="save($event.target.value,row.item.id,'units')"
                                          placeholder="Введите единицы измерения"/>
                            </b-form-group>
                        </div>
                        <div class="col-sm-12 col-md-6 mb-2">
                            <b-form-group label="Ссылка" label-size="sm" class="mb-0">
                            <b-form-input :value="row.item.site_url"
                                          type="text"
                                          @blur="save($event.target.value,row.item.id,'site_url')"
                                          placeholder="Введите ссылку на товар"/>
                            </b-form-group>
                        </div>
                        <div class="col-sm-12 col-md-6 mb-2">
                            <b-form-group label="Картинка" label-size="sm" class="mb-0">
                            <b-form-input :value="row.item.img"
                                          type="text"
                                          @blur="save($event.target.value,row.item.id,'img')"
                                          placeholder="Введите ссылку на картинку"/>
                            </b-form-group>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-2">
                            <b-form-group label="Описание" label-size="sm" class="mb-0">
                                <b-form-textarea
                                        id="textarea"
                                        v-model="row.item.description"
                                        placeholder="Описание"
                                        rows="3"
                                        max-rows="6"
                                        @blur="save($event.target.value,row.item.id,'description')"
                                >
                                </b-form-textarea>
                            </b-form-group>
                        </div>
                    </div>
                </b-card>
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
            products: {
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
            rows: {
                type:Number
            }
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
                    {key: 'category', label: 'Категория', sortable: true, sortDirection: 'desc'},
                    {key: 'brand', label: 'Производитель', sortable: true, sortDirection: 'desc'},
                    {key: 'quantity', label: 'Кол-во на складе', sortable: true, sortDirection: 'desc'},
                    {key: 'price', label: 'Цена', sortable: true, sortDirection: 'desc'},
                    {key: 'from_vk', label: 'Товар из ВК', sortable: true, sortDirection: 'desc'},
                    {key: 'action', label: 'Действия'}
                ],
                in_process: [],
                // loading: false,
                options:[],
                selected:[],
                category:'Другое',
                modal:'',
            }
        },
        mounted() {
            // this.totalRows = this.products.length;
            this.totalRows = this.rows;
        },
        computed: {
            sortOptions() {
                return this.fields
                    .filter(f => f.sortable)
                    .map(f => {
                        return {text: f.label, value: f.key}
                    })
            },
            admin_categories: function () {
                return this.$store.getters.admin_categories;
            },
        },
        methods: {
            sendMessage(message) {
                this.$notify({
                    group: 'info',
                    type: 'success',
                    title: 'Товары',
                    text: message
                });
            },
            remove(id) {
                this.$store.dispatch('removeProduct', id)
                    .then(resp => {
                    this.sendMessage(resp.data.message)
                        this.totalRows = this.products.length
                });
            },
            restore(id) {
                this.$store.dispatch('restoreProduct', id)
                    .then(resp => {
                        this.sendMessage(resp.data.message)
                        this.totalRows = this.products.length
                    });
            },
            removeAll() {
                var ids=[];
                this.selected.forEach(item=>{
                    ids.push(item.id);
                })
                this.$store.dispatch('removeAllProduct', {ids: ids}).then(resp => {
                    this.sendMessage(resp.data.message)
                    this.totalRows = this.products.length
                })
            },
            restoreAll() {
                var ids=[];
                this.selected.forEach(item=>{
                    ids.push(item.id);
                })
                this.$store.dispatch('restoreAllProduct', {ids: ids})
                    .then(resp => {
                        this.sendMessage(resp.data.message)
                        this.totalRows = this.products.length
                    });
            },
            destroyAll() {
                var ids=[];
                this.selected.forEach(item=>{
                    ids.push(item.id);
                })
                this.$store.dispatch('forceDeleteAllProduct', {ids: ids}).then(resp => {
                    this.sendMessage(resp.data.message)
                    this.totalRows = this.products.length
                })
            },
            saveAll(value, key) {
                var ids=[];
                this.selected.forEach(item=>{
                    ids.push(item.id);
                })
                this.$store.dispatch('saveAllProduct', {
                    ids: ids,
                    key: key,
                    value: value
                    // category: category,
                }).then(resp => {
                    this.$store.commit('saveAllProduct', {
                        ids: ids,
                        key: key,
                        value: value
                        // category: category,
                    });
                    this.sendMessage(resp.data.message)
                    this.$bvModal.hide(this.modal)
                });
            },
            destroy(id) {
                this.$store.dispatch('forceDeleteProduct', id).then(resp => {
                    this.sendMessage(resp.data.message)
                    this.totalRows = this.products.length
                })
            },
            changeIsActive(value, item, key){
                item.is_active = value;
                this.save(value, item.id, key)
            },
            save(value, id, key) {
                this.$store.dispatch('saveProduct', {
                    id: id,
                    key: key,
                    value: value
                }).then(resp => {
                    this.$store.commit('saveProduct', resp.data.product);
                    this.sendMessage(resp.data.message)
                });
            },
            onFiltered(filteredItems) {
                this.totalRows = filteredItems.length;
                this.currentPage = 1
            },
            onRowSelected(items) {
                this.selected = items
            },
            setInitialCategory() {
                this.modal='modal-' +this.selected[0].id;
                this.$bvModal.show(this.modal);
            },
            cancel() {
                this.$bvModal.hide(this.modal)
            },
            selectAllRows() {
                this.$refs.selectableTable.selectAllRows()
            },
            clearSelected() {
                this.$refs.selectableTable.clearSelected()
            },
        }
    }
</script>

<style scoped>
@media screen and (min-width: 991.98px) {
    .min {
        min-width: 300px !important;
    }
}
@media (max-width: 900px) {
    .stacked {
        display: block;
        width: 100%;
    }
    .stacked > tbody, .stacked > tbody > tr, .stacked > tbody > tr > td, .stacked > tbody > tr > th {
        display: block;
    }
    .stacked > tbody > tr > [data-label]::before {
        content: attr(data-label);
        width: 40%;
        float: left;
        text-align: right;
        overflow-wrap: break-word;
        font-weight: bold;
        font-style: normal;
        padding: 0 calc(1rem / 2) 0 0;
        margin: 0;
    }
    .stacked > tbody > tr > [data-label]::after {
        display: block;
        clear: both;
        content: "";
    }
}
</style>