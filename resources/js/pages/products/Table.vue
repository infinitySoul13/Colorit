<template>
    <div>
        <b-modal :id="modal" title="Change category" v-if="admin_categories.length>0">
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
        <b-row>
            <b-col lg="6" class="my-1">
                <b-form-group
                        label="Sorting"
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
                        label="Search"
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
                                placeholder="Print to search"
                        />
                        <b-input-group-append>
                            <b-button :disabled="!filter" @click="filter = ''">Clear</b-button>
                        </b-input-group-append>
                    </b-input-group>
                </b-form-group>
            </b-col>
            <b-col sm="5" md="6" class="my-1">
                <b-form-group
                        label="On page"
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
                    <b-col md="3">
                        <b-button @click="selectAllRows" variant="primary" class="w-100 mr-1 mb-1">
                            Select All
                        </b-button>
                    </b-col>
                    <b-col md="3">
                        <b-button @click="clearSelected" variant="primary" class="w-100 mr-1 mb-1">
                            Clear Selected
                        </b-button>
                    </b-col>
                    <b-col md="3">
                        <b-button v-if="products[0].deleted_at==null" @click="removeAll" variant="primary" class="w-100 mr-1 mb-1">
                            Remove Selected
                        </b-button>
                        <b-button v-else @click="restoreAll" variant="primary" class="w-100 mr-1 mb-1">
                            Restore Selected
                        </b-button>
                    </b-col>
                    <b-col md="3">
                        <b-button @click="setInitialCategory" variant="primary" class="w-100 mt-1 mb-1">Set Category</b-button>
                    </b-col>
<!--                    <b-col md="4">-->
<!--                        <b-button @click="saveAll(true, 'is_active')" variant="primary" class="mt-1 mb-1">Показать выделенные на сайте</b-button>-->
<!--                    </b-col>-->
<!--                    <b-col md="4">-->
<!--                        <b-button @click="saveAll(false, 'is_active')" variant="primary" class="mt-1 mb-1">Скрыть выделенные на сайте</b-button>-->
<!--                    </b-col>-->
                </b-row>
            </b-col>
        </b-row>
        <b-table
                show-empty
                small
                stacked="md"
                :items="productss"
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
                selectable
                :select-mode="'multi'"
                @row-selected="onRowSelected"
                ref="selectableTable"
                class="stacked"
        >
<!--                  empty-text="Нет записей для отображения"
                empty-filtered-text="Нет записей, соответствующих вашему запросу"-->
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
<!--            <template v-slot:cell(title)="data">-->
<!--                <b-input-group size="sm">-->
<!--                    <b-form-input :value="data.item.title"-->
<!--                                  @blur="save($event.target.value,data.item.id,'title')"-->
<!--                                  placeholder="Product title"-->
<!--                                  class="w-100 min"-->
<!--                    />-->
<!--                </b-input-group>-->
<!--            </template>-->
<!--            <template v-slot:cell(category)="data">-->
<!--                <b-input-group size="sm">-->
<!--                    <b-form-select-->
<!--                            v-model="data.item.category"-->
<!--                            value-field="title"-->
<!--                            text-field="title"-->
<!--                            :options="admin_categories"-->
<!--                            @change="save(data.item.category,data.item.id,'category')"-->
<!--                    >-->
<!--                    </b-form-select>-->
<!--                </b-input-group>-->
<!--            </template>-->
<!--            <template v-slot:cell(price)="data">-->
<!--                <b-input-group size="sm">-->
<!--                    <b-form-input :value="data.item.price"-->
<!--                                  type="number"-->
<!--                                  @blur="save($event.target.value,data.item.id,'price')"-->
<!--                                  placeholder="Price"/>-->
<!--                </b-input-group>-->
<!--            </template>-->
<!--            <template v-slot:cell(discount)="data">-->
<!--                <b-input-group size="sm">-->
<!--                    <b-form-input-->
<!--                            v-model="data.item.discount"-->
<!--                            type="text"-->
<!--                            @blur="save($event.target.value, data.item.id,'discount')"-->
<!--                            placeholder="Discount"-->
<!--                    >-->
<!--                    </b-form-input>-->
<!--                </b-input-group>-->
<!--            </template>-->
<!--            <template v-slot:cell(discount_price)="data">-->
<!--                <b-input-group size="sm">-->
<!--                    <b-form-input-->
<!--                            v-model="data.item.discount_price"-->
<!--                            type="text"-->
<!--                            @blur="save($event.target.value, data.item.id,'discount_price')"-->
<!--                            placeholder="Discount price"-->
<!--                    >-->
<!--                    </b-form-input>-->
<!--                </b-input-group>-->
<!--            </template>-->
<!--            <template v-slot:cell(quantity)="data">-->
<!--                <b-input-group size="sm">-->
<!--                    <b-form-input :value="data.item.quantity"-->
<!--                                  type="number"-->
<!--                                  @blur="save($event.target.value,data.item.id,'quantity')"-->
<!--                                  placeholder="Quantity"/>-->
<!--                </b-input-group>-->
<!--            </template>-->
            <template v-slot:cell(action)="data">
                <b-input-group size="sm" class="w-100">
<!--                    <b-button @click="data.toggleDetails" class="w-100 btn btn-travel mr-1 mb-1 btn-travel">-->
<!--                        {{ data.detailsShowing ? 'Collapse' : 'Expand' }}-->
<!--                    </b-button>-->

<!--                    <b-button v-if="data.item.is_active" @click="changeIsActive(false, data.item,'is_active')" class="btn btn-info w-100 mr-1 mb-1"-->
<!--                              :disabled="data.item.id===null">-->
<!--                        Скрыть-->
<!--                    </b-button>-->
<!--                    <b-button v-else @click="changeIsActive(true, data.item,'is_active')" class="btn btn-info w-100 mr-1 mb-1"-->
<!--                              :disabled="data.item.id===null">-->
<!--                        Показать-->
<!--                    </b-button>-->
                    <b-button
                            v-if="data.item.deleted_at==null"
                            @click="remove(data.item.id)"
                            class="btn btn-info w-100 mr-1 mb-1"
                            :disabled="data.item.id===null"
                    >
                        Delete
                    </b-button>
                    <b-button
                            v-if="data.item.deleted_at==null"
                            @click="edit(data.item.id)"
                            class="btn btn-info w-100 mr-1 mb-1"
                            :disabled="data.item.id===null"
                    >
                        Edit
                    </b-button>
                    <b-button
                            v-else
                            @click="restore(data.item.id)"
                            class="btn btn-info w-100 mr-1 mb-1"
                            :disabled="data.item.id===null"
                    >
                        Restore
                    </b-button>
                    <b-button v-if="data.item.deleted_at!=null" @click="destroy(data.item.id)" class="btn btn-info w-100 mr-1 mb-1"
                              :disabled="data.item.id===null">
                        Delete Completely
                    </b-button>
                </b-input-group>
            </template>
            <template v-slot:row-details="row">
                <b-card>
                    <div class="row align-items-center justify-content-start m-auto">
                        <div class="col-sm-12 col-md-6 mb-2">
                            <b-form-group label="Type" label-size="sm" class="mb-0">
                            <b-form-input :value="row.item.type"
                                          type="text"
                                          @blur="save($event.target.value,row.item.id,'type')"
                                          placeholder="Type"/>
                            </b-form-group>
                        </div>
                        <div class="col-sm-12 col-md-6 mb-2">
                            <b-form-group label="Size" label-size="sm" class="mb-0">
                                <multiselect
                                        placeholder="Choose sizes"
                                        v-model="row.item.size"
                                        :options="sizeOptions"
                                        :multiple="true"
                                        :taggable="true"
                                        :hideSelected="true"
                                        :searchable="false"
                                        :select-label="''"
                                        group-values="sizes"
                                        group-label="label"
                                        :group-select="true"
                                        :deselectGroupLabel="'Cancel'"
                                        :selectGroupLabel="''"
                                >
                                </multiselect>
                            </b-form-group>
                        </div>
                        <div class="col-sm-12 col-md-6 mb-2">
                            <b-form-group label="Color" label-size="sm" class="mb-0">
                                <multiselect
                                        placeholder="Choose colors"
                                        v-model="row.item.color"
                                        :options="colorOptions"
                                        :multiple="true"
                                        :taggable="true"
                                        label="name"
                                        track-by="value"
                                        :hideSelected="true"
                                        :searchable="false"
                                        :select-label="''"
                                        group-values="colors"
                                        group-label="label"
                                        :group-select="true"
                                        :deselectGroupLabel="'Cancel'"
                                        :selectGroupLabel="''"
                                >
                                </multiselect>
                            </b-form-group>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-2">
                            <b-form-group label="Description" label-size="sm" class="mb-0">
                                <b-form-textarea
                                        id="textarea"
                                        v-model="row.item.description"
                                        placeholder="Description"
                                        rows="3"
                                        max-rows="6"
                                        @blur="save($event.target.value,row.item.id,'description')"
                                >
                                </b-form-textarea>
                            </b-form-group>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-2">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="large-12 medium-12 small-12 filezone">
                                        <input type="file" id="files" :ref="'files'+row.item.id" multiple v-on:change="handleFiles(row.item)"/>
                                        <p>
                                            Drag and drop pictures here <br> or click to search
                                        </p>
                                    </div>
                                    <draggable
                                            :list="row.item.src"
                                            class="file-listing"
                                            ghost-class="ghost"
                                    >
                                        <div class="list-group-item"
                                             v-for="(element, index) in row.item.src"
                                             :key="element.name"
                                        >
                                            <img :src="'/images/'+element.name" class="preview"/>
                                            {{ element.name }}
                                            <div class="remove-container">
                                                <a class="remove" v-on:click="deleteItem(element.name, index, row.item.src)" v-if="file_loading == false">Remove</a>
                                            </div>
                                        </div>
                                    </draggable>

                                    <h3 v-if="row.item.new_files && row.item.new_files.length>0">New files</h3>
                                    <div v-if="row.item.new_files && row.item.new_files.length>0" :key="key" v-for="(file, key) in row.item.new_files" class="file-listing">
<!--                                        <input ref="test"/>-->
                                        {{key}}
                                        <img v-if="row.item.new_src" class="preview" :src="row.item.new_src[key]"/>
<!--                                        <img class="preview" :ref="'preview'+key"/>-->
<!--                                        <img class="preview" ref="preview0"/>-->
                                        {{ file.name }}
                                        <div class="remove-container">
                                            <a class="remove" v-on:click="removeFile(row.item, key)" v-if="file_loading == false">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row w-100 m-auto">
                                <b-button v-if="row.item.new_files && row.item.new_files.length>0" @click="saveImages(row.item.id)">
                                    Save new pictures
                                </b-button>
                            </div>
                        </div>
                    </div>
                </b-card>
            </template>
            <template v-slot:table-busy>
                <div class="text-center text-primary my-2">
                    <b-spinner class="align-middle"/>
                    <strong>Loading...</strong>
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
                    {key: 'title', label: 'Title', sortable: true, sortDirection: 'desc'},
                    {key: 'category', label: 'Category', sortable: true, sortDirection: 'desc'},
                    {key: 'price', label: 'Price', sortable: true, sortDirection: 'desc'},
                    {key: 'discount', label: 'Discount', sortable: true, sortDirection: 'desc'},
                    {key: 'discount_price', label: 'Discount price', sortable: true, sortDirection: 'desc'},
                    {key: 'quantity', label: 'Quantity', sortable: true, sortDirection: 'desc'},
                    {key: 'action', label: 'Action'}
                ],
                in_process: [],
                // loading: false,
                options:[],
                selected:[],
                category:'Another',
                modal:'',
                sizeOptions: [
                    { label: 'Выбрать все',
                        sizes: [ 'XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL']}
                ],
                colorOptions: [
                    { label: 'Выбрать все',
                        colors: [
                            {name:'Белый', value:'white'},
                            {name:'Синий', value:'blue'},
                            {name:'Красный', value:'red'},
                            {name:'Желтый', value:'yellow'},
                            {name:'Зелёный', value:'green'},
                            {name:'Фиолетовый', value:'purple'},
                            {name:'Оранжевый', value:'orange'},
                            {name:'Чёрный', value:'black'}
                        ]
                    }
                ],
                file_loading: false,
                productss:[],
            }
        },
        mounted() {
            // this.totalRows = this.products.length;
            this.totalRows = this.rows;
            console.log(this.$refs);
            this.productss = this.products;
            this.productss.forEach(item => {
                item.new_files=[];
                item.new_src=[];
            })
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
        watch: {
            productss: {
                handler: function() {
                    console.log('changed')
                },
                deep: true,
                immediate:true
            }
        },
        methods: {
            sendMessage(message) {
                this.$notify({
                    group: 'info',
                    type: 'success',
                    title: 'Products',
                    text: message
                });
            },
            edit(id) {
                window.location.href = `/admin/products/edit/${id}`;
            },
            remove(id) {
                this.$store.dispatch('removeProduct', id)
                    .then(resp => {
                        this.sendMessage(resp.data.message);
                        this.totalRows = this.productss.length
                });
            },
            restore(id) {
                this.$store.dispatch('restoreProduct', id)
                    .then(resp => {
                        this.sendMessage(resp.data.message);
                        this.totalRows = this.productss.length
                    });
            },
            removeAll() {
                let ids=[];
                this.selected.forEach(item=>{
                    ids.push(item.id);
                });
                this.$store.dispatch('removeAllProduct', {ids: ids}).then(resp => {
                    this.sendMessage(resp.data.message);
                    this.totalRows = this.productss.length
                })
            },
            restoreAll() {
                let ids=[];
                this.selected.forEach(item=>{
                    ids.push(item.id);
                });
                this.$store.dispatch('restoreAllProduct', {ids: ids})
                    .then(resp => {
                        this.sendMessage(resp.data.message);
                        this.totalRows = this.productss.length;
                    });
            },
            destroyAll() {
                let ids=[];
                this.selected.forEach(item=>{
                    ids.push(item.id);
                });
                this.$store.dispatch('forceDeleteAllProduct', {ids: ids}).then(resp => {
                    this.sendMessage(resp.data.message);
                    this.totalRows = this.productss.length;
                })
            },
            saveAll(value, key) {
                let ids=[];
                this.selected.forEach(item=>{
                    ids.push(item.id);
                });
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
                    this.sendMessage(resp.data.message);
                    this.totalRows = this.productss.length
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
                    this.sendMessage(resp.data.message);
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
            removeFile( item, key ){
                item.new_files.splice( key, 1 );
                item.new_src.splice( key, 1 );
                this.getImagePreviews(item.new_files, item.new_src);
            },
            deleteItem() {

            },
            saveImages(id)
            {

            },
            handleFiles(item) {
                if(!item.new_files) {
                    item.new_files=[];
                }
                item.new_src=[];
                let uploadedFiles = this.$refs['files'+item.id].files;

                for(let i = 0; i < uploadedFiles.length; i++) {
                    if (/\.(jpe?g|png|gif|svg)$/i.test(uploadedFiles[i].name))
                    {
                        item.new_files.push(uploadedFiles[i]);
                        this.getImagePreviews(item.new_files, item.new_src);
                    }
                    else
                    {
                        this.sendMessage('This file has wrong format')
                        // break;
                    }

                }

            },
            getImagePreviews(arr, arr_src){

                for( let i = 0; i < arr.length; i++ ) {
                    if ( /\.(jpe?g|png|gif|svg)$/i.test( arr[i].name ) ) {
                        let reader = new FileReader();
                        reader.addEventListener("load", function(){
                            arr_src.push(reader.result);
                            // this.$refs['preview'+i][0].src = reader.result;
                        }.bind(this), false);
                        reader.readAsDataURL( arr[i] );
                    }else{
                        this.$nextTick(function(){
                            arr_src.push('/images/product.jpg');
                            // this.$refs['preview'][0].src = '/images/product.jpg';
                        });
                    }
                }
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
div.file-listing img{
    max-width: 90%;
}

div.file-listing{
    margin: auto;
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

div.file-listing img{
    height: 100px;
}
div.success-container{
    text-align: center;
    color: green;
}

div.remove-container{
    text-align: center;
}

div.remove-container a{
    color: red;
    cursor: pointer;
}

a.submit-button {
    display: block;
    margin: auto;
    text-align: center;
    width: 200px;
    padding: 10px;
    text-transform: uppercase;
    background-color: green;
    color: white;
    font-weight: bold;
    margin-top: 20px;
}
</style>