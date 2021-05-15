<template>
    <div class="row w-100 m-auto justify-content-center align-items-center">
        <div class="col-sm-12 col-md-6 mb-2">
            <b-form-group label="Title" label-size="sm" class="mb-0">
                <b-form-input v-model="new_product.title"
                              placeholder="Product title"
                              class="w-100 min"
                />
            </b-form-group>
        </div>
        <div class="col-sm-12 col-md-6 mb-2">
            <b-form-group label="Category" label-size="sm" class="mb-0">
                <b-form-select
                        v-model="new_product.category"
                        value-field="title"
                        text-field="title"
                        :options="admin_categories"
                >
                </b-form-select>
            </b-form-group>
        </div>
        <div class="col-sm-12 col-md-4 mb-2">
            <b-form-group label="Price" label-size="sm" class="mb-0">
                <b-form-input v-model="new_product.price"
                              type="number"
                              @blur="calculateDiscountPrice"
                              placeholder="Price"
                />
            </b-form-group>
        </div>
        <div class="col-sm-12 col-md-4 mb-2">
            <b-form-group label="Discount" label-size="sm" class="mb-0">
                <b-form-input
                        v-model="new_product.discount"
                        :type="'number'"
                        @blur="calculateDiscountPrice"
                        placeholder="Discount"
                >
                </b-form-input>
            </b-form-group>
        </div>
        <div class="col-sm-12 col-md-4 mb-2">
            <b-form-group label="Discount price" label-size="sm" class="mb-0">
                <b-form-input
                        v-model="new_product.discount_price"
                        type="text"
                        @blur="save($event.target.value, new_product.id,'discount_price')"
                        placeholder="Discount price"
                        disabled
                >
                </b-form-input>
            </b-form-group>
        </div>
        <div class="col-sm-12 col-md-6 mb-2">
            <b-form-group label="Quantity" label-size="sm" class="mb-0">
                <b-form-input v-model="new_product.quantity"
                              type="number"
                              placeholder="Quantity"/>
            </b-form-group>
        </div>

        <div class="col-sm-12 col-md-6 mb-2">
            <b-form-group label="Type" label-size="sm" class="mb-0">
<!--                <b-form-input v-model="new_product.type"-->
<!--                              type="text"-->
<!--                              placeholder="Type"/>-->
                <b-form-select
                        v-model="new_product.type"
                        value-field="value"
                        text-field="title"
                        :options="[{title:'Normal', value:''}, {title:'Sale', value:'sale'}, {title:'Hot', value:'hot'}]"
                        placeholder="Type"
                >
                </b-form-select>
            </b-form-group>
        </div>
        <div class="col-sm-12 col-md-6 mb-2">
            <b-form-group label="Size" label-size="sm" class="mb-0">
                <multiselect
                        placeholder="Choose sizes"
                        v-model="new_product.size"
                        :options="sizeOptions"
                        :multiple="true"
                        :taggable="true"
                        label="name"
                        track-by="tooltip"
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
                        v-model="new_product.color"
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
                        v-model="new_product.description"
                        placeholder="Description"
                        rows="3"
                        max-rows="6"
                >
                </b-form-textarea>
            </b-form-group>
        </div>
        <div class="col-sm-12 col-md-12 mb-2">
            <div class="form-group row w-100 m-auto align-items-center">
                <div class="col-md-12 p-0">
                    <div class="large-12 medium-12 small-12 filezone">
                        <input type="file" id="files" ref="files" multiple v-on:change="handleFiles()"/>
                        <p>
                            Drag and drop pictures here <br> or click to search
                        </p>
                    </div>
                </div>
                <div class="col-md-12 p-0">
                    <draggable
                            :list="new_product.src"
                            class="file-listing"
                            ghost-class="ghost"
                            :disabled="loading"
                    >
                        <div v-if="new_product.src.length>0" :key="key" v-for="(file, key) in new_product.src" class="list-group-item">
                            <div class="row w-100 m-auto align-items-center justify-content-center">
                                <div class="col-4">
                                    <img class="preview" :ref="'preview'+key"/>
                                </div>
                                <div class="col-6 text-wrap text-break">
                                    {{ file.filename }}
                                </div>
                                <div class="col-2">
                                    <div class="remove-container">
                                        <a class="remove" v-on:click="removeFile(key)" v-if="loading == false">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </draggable>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-12 mb-2">
            <div class="row w-100 m-auto align-items-center">
                <b-button variant="primary" class="ml-auto float-right w-25" :disabled="loading" @click="addProduct">
                    <b-spinner small v-if="loading"></b-spinner>
                    <span class="sr-only" v-if="loading">Loading...</span>
                    Create
                </b-button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Edit",
        data() {
            return {
                new_product:{
                    title:'',
                    category:'',
                    discount_price:'',
                    discount:'',
                    price:'',
                    type:'',
                    quantity:'',
                    description:'',
                    size:[],
                    color:[],
                    src:[]
                },

                sizeOptions: [
                    { label: 'Выбрать все',
                        sizes: [
                            {name:'XS', tooltip:'42'},
                            {name:'S', tooltip:'44-46'},
                            {name:'M', tooltip:'48-50'},
                            {name:'L', tooltip:'52'},
                            {name:'XL', tooltip:'54-56'},
                            {name:'XXL', tooltip:'58-60'},
                        ]
                    }
                ],
                colorOptions: [
                    { label: 'Выбрать все',
                        colors: [
                            {name:'White', value:'white'},
                            {name:'Blue', value:'blue'},
                            {name:'Red', value:'red'},
                            {name:'Yellow', value:'yellow'},
                            {name:'Green', value:'green'},
                            {name:'Purple', value:'purple'},
                            {name:'Orange', value:'orange'},
                            {name:'Black', value:'black'},
                            {name:'Gray', value:'gray'}
                        ],
                    }
                ],
                loading: false,
            }
        },
        mounted() {
            this.$store.dispatch('loadDataCategory').then(() => {

            });
        },
        computed: {
            admin_categories: function () {
                return this.$store.getters.admin_categories;
            },
        },
        methods: {
            sendMessage(message, type='success') {
                this.$notify({
                    group: 'info',
                    type: type,
                    title: 'Products',
                    text: message
                });
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

            removeFile( key ){
                this.new_product.src.splice( key, 1 );
                this.getImagePreviews(this.new_product.src);
            },

            handleFiles() {
                let uploadedFiles = this.$refs['files'].files;
                for(let i = 0; i < uploadedFiles.length; i++) {
                    if (/\.(jpe?g|png|gif|svg)$/i.test(uploadedFiles[i].name))
                    {
                        this.new_product.src.push(uploadedFiles[i]);
                        this.getImagePreviews(this.new_product.src);
                    }
                    else
                    {
                        this.sendMessage('This file has wrong format', 'error')
                        // break;
                    }
                }
            },
            getImagePreviews(arr){
                for( let i = 0; i < arr.length; i++ ) {
                    if ( /\.(jpe?g|png|gif|svg)$/i.test( arr[i].name ) ) {
                        let reader = new FileReader();
                        reader.addEventListener("load", function(){
                            // arr_src.push(reader.result);
                            this.$refs['preview'+i][0].src = reader.result;
                        }.bind(this), false);
                        reader.readAsDataURL( arr[i] );
                    }else{
                        this.$nextTick(function(){
                            // arr_src.push('/images/product.jpg');
                            this.$refs['preview'+i][0].src = '/images/product.jpg';
                        });
                    }
                }
            },
            addProduct() {
                this.loading = true;
                let formData = new FormData();
                Object.keys(this.new_product).forEach(key => {
                    if (key == 'color' || key == 'size') {
                        formData.append(key, JSON.stringify(this.new_product[key]));
                    }
                    else {
                        formData.append(key, this.new_product[key]);
                    }
                });
                for( let i = 0; i < this.new_product.src.length; i++ ) {
                    formData.append('files[' + i + ']', this.new_product.src[i]);
                }
                this.$store.dispatch('addProduct', formData).then(resp => {
                    this.$store.commit('addProduct', resp.data.product);
                    this.sendMessage(resp.data.message,  resp.data.status);
                    this.new_product={
                        title:'',
                        category:'',
                        discount_price:'',
                        discount:'',
                        price:'',
                        type:'',
                        quantity:'',
                        description:'',
                        size:[],
                        color:[],
                        src:[]
                    };
                    this.loading = false;
                });
            },
            calculateDiscountPrice() {
                this.new_product.discount_price = (this.new_product.price - this.new_product.discount);
                this.new_product.discount_price = Math.round( this.new_product.discount_price * 1e2 ) / 1e2;;
            }
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
    .preview {
        width:100%;
        height:100px;
        object-fit: contain;
    }
</style>