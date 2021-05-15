<template>
    <div class="row w-100 m-auto justify-content-center align-items-center">
        <div class="col-sm-12 col-md-6 mb-2">
            <b-form-group label="Title" label-size="sm" class="mb-0">
                <b-form-input v-model="edit_product.title"
                              @blur="save($event.target.value,edit_product.id,'title')"
                              placeholder="Product title"
                              class="w-100 min"
                />
            </b-form-group>
        </div>
        <div class="col-sm-12 col-md-6 mb-2">
            <b-form-group label="Category" label-size="sm" class="mb-0">
                <b-form-select
                        v-model="edit_product.category"
                        value-field="title"
                        text-field="title"
                        :options="admin_categories"
                        @change="save(edit_product.category,edit_product.id,'category')"
                >
                </b-form-select>
            </b-form-group>
        </div>
        <div class="col-sm-12 col-md-4 mb-2">
            <b-form-group label="Price" label-size="sm" class="mb-0">
                <b-form-input v-model="edit_product.price"
                              type="number"
                              @blur="calculateDiscountPrice('price')"
                              placeholder="Price"
                />
            </b-form-group>
        </div>
        <div class="col-sm-12 col-md-4 mb-2">
            <b-form-group label="Discount" label-size="sm" class="mb-0">
                <b-form-input
                        v-model="edit_product.discount"
                        :type="'number'"
                        @blur="calculateDiscountPrice('discount')"
                        placeholder="Discount"
                >
                </b-form-input>
            </b-form-group>
        </div>
        <div class="col-sm-12 col-md-4 mb-2">
            <b-form-group label="Discount price" label-size="sm" class="mb-0">
                <b-form-input
                        v-model="edit_product.discount_price"
                        type="text"
                        @blur="save($event.target.value, edit_product.id,'discount_price')"
                        placeholder="Discount price"
                        disabled
                >
                </b-form-input>
            </b-form-group>
        </div>
        <div class="col-sm-12 col-md-6 mb-2">
            <b-form-group label="Quantity" label-size="sm" class="mb-0">
                <b-form-input v-model="edit_product.quantity"
                              type="number"
                              @blur="save($event.target.value,edit_product.id,'quantity')"
                              placeholder="Quantity"/>
            </b-form-group>
        </div>

        <div class="col-sm-12 col-md-6 mb-2">
            <b-form-group label="Type" label-size="sm" class="mb-0">
<!--                <b-form-input v-model="edit_product.type"-->
<!--                              type="text"-->
<!--                              @blur="save($event.target.value,edit_product.id,'type')"-->
<!--                              placeholder="Type"/>-->
                <b-form-select
                        v-model="edit_product.type"
                        value-field="value"
                        text-field="title"
                        :options="[{title:'Normal', value:''},{title:'Sale', value:'sale'}, {title:'Hot', value:'hot'}]"
                        @change="save(edit_product.type,edit_product.id,'type')"
                        placeholder="Type"
                >
                </b-form-select>
            </b-form-group>
        </div>
        <div class="col-sm-12 col-md-6 mb-2">
            <b-form-group label="Size" label-size="sm" class="mb-0">
                <multiselect
                        placeholder="Choose sizes"
                        v-model="edit_product.size"
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
                        @input="save(edit_product.size,edit_product.id,'size')"
                >
                </multiselect>
            </b-form-group>
        </div>
        <div class="col-sm-12 col-md-6 mb-2">
            <b-form-group label="Color" label-size="sm" class="mb-0">
                <multiselect
                        placeholder="Choose colors"
                        v-model="edit_product.color"
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
                        @input="save(edit_product.color,edit_product.id,'color')"
                >
                </multiselect>
            </b-form-group>
        </div>
        <div class="col-sm-12 col-md-12 mb-2">
            <b-form-group label="Description" label-size="sm" class="mb-0">
                <b-form-textarea
                        id="textarea"
                        v-model="edit_product.description"
                        placeholder="Description"
                        rows="3"
                        max-rows="6"
                        @blur="save($event.target.value,edit_product.id,'description')"
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
                            :list="edit_product.src"
                            :disabled="file_loading"
                            class="file-listing"
                            ghost-class="ghost"
                            @end="save(edit_product.src,edit_product.id,'src')"
                    >
                        <div class="list-group-item"
                             v-for="(element, index) in edit_product.src"
                             :key="index"
                        >
                            <div class="row w-100 m-auto align-items-center justify-content-center">
                                <div class="col-4"><img :src="element.path" class="preview"/></div>
                                <div class="col-6 text-wrap text-break">{{ element.filename }}</div>
                                <div class="col-2">
                                    <div class="remove-container">
                                        <a class="remove" v-on:click="deleteFile( element.name, index)" v-if="file_loading == false">Remove</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </draggable>
                </div>
                <div class="col-md-12 p-0" v-if="new_files.length>0">
                    <div class="row w-100 m-auto align-items-center">
                        <h3 v-if="new_files.length>0">New files</h3>
                    </div>
                    <draggable
                            :list="new_files"
                            class="file-listing"
                            ghost-class="ghost"
                            :disabled="file_loading"
                    >
                        <div v-if="new_files.length>0" :key="key" v-for="(file, key) in new_files" class="list-group-item">
                            <!--                        <img v-if="new_src" class="preview" :src="new_src[key]"/>-->
                            <div class="row w-100 m-auto align-items-center justify-content-center">
                                <div class="col-4">
                                    <img class="preview" :ref="'preview'+key"/>
                                </div>
                                <div class="col-6 text-wrap text-break">
                                    {{ file.name }}
                                </div>
                                <div class="col-2">
                                    <div class="remove-container">
                                        <a class="remove" v-on:click="removeFile(key)" v-if="file_loading == false">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </draggable>
                </div>
                <div class="col-md-12">
                    <div class="row w-100 mx-auto my-2 align-items-center">
                        <b-button variant="primary" class="ml-auto w-25 w-xs-100" :disabled="file_loading" v-if="new_files.length>0" @click="saveImages()">
                            <b-spinner small v-if="file_loading"></b-spinner>
                            <span class="sr-only" v-if="file_loading">Loading...</span>
                            Save new pictures
                        </b-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Edit",
        props:['product'],
        data() {
            return {
                edit_product:{
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
                new_files:[],
                new_src:[],
                sizeOptions: [
                    { label: 'Выбрать все',
                        sizes: [
                            {name:'XS', tooltip:'42'},
                            {name:'S', tooltip:'44-46'},
                            {name:'M', tooltip:'48-50'},
                            {name:'L', tooltip:'52'},
                            {name:'XL', tooltip:'54-56'},
                            {name:'XXL', tooltip:'58-60'},
                            // 'XXS',
                            // 'XS',
                            // 'S',
                            // 'M',
                            // 'L',
                            // 'XL',
                            // 'XXL'
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
                file_loading: false,
            }
        },
        mounted() {
            this.edit_product={
                id:this.product.id,
                title:this.product.title,
                category:this.product.category,
                discount_price:this.product.discount_price,
                discount:this.product.discount,
                price:this.product.price,
                type:this.product.type,
                quantity:this.product.quantity,
                description:this.product.description,
                size:this.product.size,
                color:this.product.color,
                src:this.product.src
            };
            this.$store.dispatch('loadDataCategory').then(() => {

            });
        },
        computed: {
            admin_categories: function () {
                return this.$store.getters.admin_categories;
            },
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
                // item.new_files.splice( key, 1 );
                // item.new_src.splice( key, 1 );
                // this.getImagePreviews(item.new_files, item.new_src);
                this.new_files.splice( key, 1 );
                this.getImagePreviews(this.new_files);
            },
            deleteFile(name, index) {
                this.file_loading = true;
                this.edit_product.src.splice( index, 1 );
               let data = {
                   id: this.edit_product.id,
                   name: name,
                   src:  this.edit_product.src
               };
                this.$store.dispatch('deleteFile', data).then(resp => {
                    // this.product = resp.data.product;
                    this.file_loading = false;
                }).catch(function (error) {
                    console.log(error);
                    this.file_loading = false;
                });
            },
            saveImages() {
                this.file_loading = true;
                let formData = new FormData();
                for( let i = 0; i < this.new_files.length; i++ ) {
                    formData.append('files[' + i + ']', this.new_files[i]);
                }
                formData.append('id', this.edit_product.id);
                this.$store.dispatch('uploadFiles', formData).then(resp => {
                    // this.$store.commit('saveProduct', resp.data.product);
                    this.edit_product = resp.data.product;
                    this.new_files = [];
                    this.file_loading = false;
                }).catch(function (error) {
                    console.log(error);
                    this.file_loading = false;
                });
            },
            handleFiles() {
                let uploadedFiles = this.$refs['files'].files;
                for(let i = 0; i < uploadedFiles.length; i++) {
                    if (/\.(jpe?g|png|gif|svg)$/i.test(uploadedFiles[i].name))
                    {
                        this.new_files.push(uploadedFiles[i]);
                        this.getImagePreviews(this.new_files);
                    }
                    else
                    {
                        this.sendMessage('This file has wrong format')
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
            calculateDiscountPrice(key) {
                this.edit_product.discount_price = (this.edit_product.price - this.edit_product.discount);
                this.edit_product.discount_price = Math.round( this.edit_product.discount_price * 1e2 ) / 1e2;;

                if ( key == 'price') {
                    this.save(this.edit_product.price, this.edit_product.id,'price');
                }
                else {
                    this.save(this.edit_product.discount, this.edit_product.id,'discount');
                }
                this.save(this.edit_product.discount_price, this.edit_product.id, 'discount_price');
            }
        }
    }
</script>

<style scoped>
    .custom-select option {
        color: black !important;
    }
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