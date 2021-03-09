<template>
    <div class="container">
        <div class="" v-if="products.length>0 && !loading">
            <div class="row">
                <div class="col-lg-12">
                    <div class="chopcafe_filter text-center" v-if="product_categories.length>0">
                        <button class="chopcafe_btn mb-2" :class="{active_btn:category.title==chosen_category.title}" v-for="category in product_categories" @click="chosen_category=category">{{category.title}}</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12 menu_item" v-for="product in filtered_products">
                    <div class="grid_item food_grid_box">
                        <div class="grid_inner_item">
                            <div class="chopcafe_img main_menu_img">
                                <img :src="product.img" class="img-fluid" style="max-height:245px;width:100%;height:100%;object-fit:contain;" alt="">
                                <!--                            <img :src="\assets\images\{{$product->img}}" class="img-fluid" style="max-height:245px;width:100%;height:100%;object-fit:contain;" alt="">-->
                                <div class="overlay_img"></div>
                                <div class="overlay_content custom_overlay_menu">
                                    <add-to-cart-btn :product_id="product.id" :product_data="product"></add-to-cart-btn>
                                </div>
                            </div>
                            <div class="chopcafe_info">
                                <div style="height: 75px;">
                                    <h3>{{product.title}}</h3>
                                </div>
                                <hr>
                                <p><em>{{product.brand}}</em></p>
                                <hr>
                                <h3><strong>{{product.price}}  <i class="fas fa-ruble-sign"></i></strong></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="chopcafe_filter text-center" v-if="!product_loading">
                        <button class="chopcafe_btn mb-2" @click="infiniteHandler" v-if="chosen_category.last_page>=chosen_category.page">Показать больше</button>
                    </div>
                    <div class="text-center text-danger my-2" v-if="product_loading">
                        <b-spinner class="align-middle"/>
                        <strong>Загрузка...</strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="" v-if="loading">
            <div class="row">
                <div class="col-12">
                    <div class="text-center text-danger my-2">
                        <b-spinner class="align-middle"/>
                        <strong>Загрузка...</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Products",
        data() {
            return {
                loading: false,
                page:1,
                chosen_category:'',
                product_loading:false,
                sort:'По возрастанию цены',
            }
        },
        computed: {
            products: function () {
                return this.$store.getters.products;
            },
            deleted_products: function () {
                return this.$store.getters.deleted_products;
            },
            product_categories: function () {
                return this.$store.getters.product_categories;
            },
            filtered_products:function () {
                var prod = this.products.filter(item => item.category === this.chosen_category.title).sort((a, b) => {
                    if (this.sort =='По убыванию цены') {
                        return b.price-a.price;
                    }
                    else if (this.sort =='По возрастанию цены') {
                        return a.price-b.price;
                    };
                });
                return prod;
            },
        },
        created(){
            this.loading = true;
            this.$store.dispatch('loadCategoriesForShop').then(() => {
                this.chosen_category = this.product_categories[0]
                console.log(this.chosen_category)
                // this.$store.dispatch('loadProductsForShop').then(() => {
                this.loading = false;
                // })
            })
        },
        mounted() {

        },
        methods:{
            async infiniteHandler() {
                // let vm = this;
                this.product_loading = true;
                // await axios.get('/products?page='+this.page+'&category='+this.category)
                await axios.get('/products/more/'+this.chosen_category.title+'?page='+this.chosen_category.page, {
                    page: this.chosen_category.page,
                    category: this.chosen_category.title
                })
                    .then(response => {
                        this.$store.dispatch('addMoreProducts', response.data.products.data);
                        this.$store.dispatch('incPage', this.chosen_category.title);
                        this.product_loading = false;
                        // return response.json();
                    });
                    // .then(data => {
                    // $.each(data.data, function(key, value) {
                    //     vm.list.push(value);
                    // });
                    //$state.loaded();
                // });

                // this.page = this.page + 1;
            },
        }
    }
</script>

<style scoped>

</style>