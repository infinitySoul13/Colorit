<template>
    <div id="canvas" class="">
        <div id="box_wrapper" class="">
            <page-header></page-header>
            <section class="page_breadcrumbs cs main_color6 section_padding_25 background_cover" style="background-image: url(http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/breadcrumbs3.jpg)">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 text-center darklinks">

                            <h1>Album</h1>

                            <ol class="breadcrumb d-block">
                                <li class="first-item">
                                    <a href="/">Homepage</a></li>
                                <li class="0-item">
                                    <a href="/shop">Shop</a></li>
                                <li class="last-item">
                                    <span>{{product.title}}</span>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
            <section class="ls page_content section_padding_top_150 section_padding_bottom_150 columns_padding_30">
                <div class="container">
                    <div class="row">
                        <div class="woocommerce-notices-wrapper"></div>
                        <div id="content_product" class="col-xs-12 col-md-12 col-lg-12">
                            <div id="product-1209" class="post-1209 product type-product status-publish has-post-thumbnail product_cat-music product_tag-tag-1 product_tag-tag-2 product_tag-tag-3 first instock downloadable virtual purchasable product-type-simple">

                                <div class="row columns_padding_30 columns_margin_bottom_30">
                                    <div class="col-sm-6">
<!--                                        <div class="woocommerce-product-gallery woocommerce-product-gallery&#45;&#45;with-images woocommerce-product-gallery&#45;&#45;columns-4 images" data-columns="4" style="opacity: 1; transition: opacity .25s ease-in-out;">-->
<!--                                            <figure class="woocommerce-product-gallery__wrapper">-->
<!--                                                <div data-thumb="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/album-1-100x100.jpg" class="woocommerce-product-gallery__image">-->
<!--                                                    <a href="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/album-1.jpg">-->
<!--                                                        <img width="600" height="600" src="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/album-1-600x600.jpg"-->
<!--                                                             class="wp-post-image" alt="" title="album-1.jpg" data-caption=""-->
<!--                                                             data-src="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/album-1.jpg"-->
<!--                                                             data-large_image="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/album-1.jpg"-->
<!--                                                             data-large_image_width="800" data-large_image_height="800"-->
<!--                                                             srcset="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/album-1-600x600.jpg 600w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/album-1-150x150.jpg 150w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/album-1-300x300.jpg 300w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/album-1-768x768.jpg 768w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/album-1-100x100.jpg 100w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/album-1.jpg 800w"-->
<!--                                                             sizes="(max-width: 600px) 100vw, 600px"-->
<!--                                                        />-->
<!--                                                    </a>-->
<!--                                                </div>-->
<!--                                            </figure>-->
<!--                                        </div>-->
                                        <b-carousel
                                                id="carousel-fade"
                                                style="text-shadow: 0px 0px 2px #000; width:100%; height:100%"
                                                fade
                                                indicators
                                                img-width="600"
                                                img-height="600"
                                        >
                                            <b-carousel-slide v-for="img in show_product.src" :key="img.name" style="width:100%; height:100%">
                                                <template #img>
                                                    <img class="d-block img-fluid w-100 attachment-woocommerce_thumbnail size-woocommerce_thumbnail"
                                                         alt="image slot"
                                                         :src="img.path"
                                                         sizes="(max-width: 600px) 100vw, 600px"
                                                         style="height:100%; width:100%;object-fit: fill; min-height: 400px; max-height: 400px;"
                                                    >
                                                </template>
                                            </b-carousel-slide>
                                        </b-carousel>
                                        <!--eof .images -->
                                        <div class="thumbnails-wrap">

                                        </div>
                                    </div>
                                    <!-- eof .col-sm- with single product images -->
                                    <div class="col-sm-6">
                                        <div class="summary entry-summary">
                                            <h1 class="product_title entry-title">{{show_product.title}}</h1>
<!--                                            <div class="woocommerce-product-rating">-->
<!--                                                <div class="star-rating">-->
<!--                                                    <span style="width:51.8%">Rated <strong class="rating">2.59</strong> out of 5 based on <span class="rating">1398</span> customer ratings</span>-->
<!--                                                </div>-->
<!--                                            </div>-->

                                            <span class="price" v-if="show_product.type=='sale'">
                                                    <del>
                                                        <span class="woocommerce-Price-amount amount">
                                                            <span class="woocommerce-Price-currencySymbol">&pound;</span>
                                                            {{show_product.price}}
                                                        </span>
                                                    </del>
                                                    <ins>
                                                        <span class="woocommerce-Price-amount amount">
                                                            <span class="woocommerce-Price-currencySymbol">&pound;</span>
                                                            {{show_product.discount_price}}
                                                        </span>
                                                    </ins>
                                                </span>
                                            <span class="price" v-else>
                                                <span class="woocommerce-Price-amount amount">
                                                    <span class="woocommerce-Price-currencySymbol">&pound;</span>
                                                    {{show_product.price}}
                                                </span>
                                            </span>
                                            <div class="swatches">
                                                <div class="swatch clearfix mr-5" v-if="show_product.size != null && show_product.size.length >= 1" data-option-index="0">
                                                    <div class="header" style="color: #453074;">Choose a size:</div>
                                                    <div v-for="size in show_product.size" :data-value="size.name" class="swatch-element plain xs">
                                                        <div class="tooltip">{{size.tooltip}}</div>
                                                        <input :id="'swatch-0-'+size.name" type="radio" name="orderSize" v-model="orderSize" :value="size.name" />
                                                        <label :for="'swatch-0-'+size.name">
                                                            {{size.name}}
                                                        </label>
                                                    </div>
<!--                                                    <div data-value="XS" class="swatch-element plain xs">-->
<!--                                                        <div class="tooltip">42</div>-->
<!--                                                        <input id="swatch-0-xs" type="radio" name="orderSize" v-model="orderSize" value="XS" />-->
<!--                                                        <label for="swatch-0-xs">-->
<!--                                                            XS-->
<!--                                                        </label>-->
<!--                                                    </div>-->
<!--                                                    <div data-value="S" class="swatch-element plain s">-->
<!--                                                        <div class="tooltip">44-46</div>-->
<!--                                                        <input id="swatch-0-s" type="radio" name="orderSize" v-model="orderSize" value="S" />-->
<!--                                                        <label for="swatch-0-s">-->
<!--                                                            S-->
<!--                                                        </label>-->
<!--                                                    </div>-->
<!--                                                    <div data-value="M" class="swatch-element plain m">-->
<!--                                                        <div class="tooltip">48-50</div>-->
<!--                                                        <input id="swatch-0-m" type="radio" name="orderSize" v-model="orderSize" value="M" />-->
<!--                                                        <label for="swatch-0-m">-->
<!--                                                            M-->
<!--                                                        </label>-->
<!--                                                    </div>-->
<!--                                                    <div data-value="L" class="swatch-element plain l">-->
<!--                                                        <div class="tooltip">52</div>-->
<!--                                                        <input id="swatch-0-l" type="radio" name="orderSize" v-model="orderSize" value="L" />-->
<!--                                                        <label for="swatch-0-l">-->
<!--                                                            L-->
<!--                                                        </label>-->
<!--                                                    </div>-->
<!--                                                    <div data-value="XL" class="swatch-element plain xl">-->
<!--                                                        <div class="tooltip">54-56</div>-->
<!--                                                        <input id="swatch-0-xl" type="radio" name="orderSize" v-model="orderSize" value="XL" />-->
<!--                                                        <label for="swatch-0-xl">-->
<!--                                                            XL-->
<!--                                                        </label>-->
<!--                                                    </div>-->
<!--                                                    <div data-value="XXL" class="swatch-element plain xxl">-->
<!--                                                        <div class="tooltip">58-60</div>-->
<!--                                                        <input id="swatch-0-xxl" type="radio" name="orderSize" v-model="orderSize" value="XXL" />-->
<!--                                                        <label for="swatch-0-xxl">-->
<!--                                                            XXL-->
<!--                                                        </label>-->
<!--                                                    </div>-->
                                                </div>
                                                <div class="swatch clearfix" data-option-index="1" v-if="show_product.color != null && show_product.color.length >= 1">
                                                    <div class="header" style="color: #453074;">Choose a color:</div>
                                                    <div class="swatch-element color" v-for ="color in show_product.color" :class="color.value" :data-value="color.value">
                                                        <div class="tooltip">{{color.name}}</div>
                                                        <input :id="color.value" type="radio" name="orderColor" v-model="orderColor" :value="color.name" />
                                                        <label :for="color.value">
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row w-100 m-auto align-items-center">
                                                <div class="col-12 mb-0 p-0">
                                                    <label class="screen-reader-text">Quantity</label>
                                                </div>
                                                <div class="col-12 col-md-6 mb-0 pl-0">
                                                    <b-form-input
                                                            :type="'number'"
                                                            v-model="quantity"
                                                            class=""
                                                            step="1" min="1"
                                                            title="Qty" size="4"
                                                            pattern="[0-9]*"
                                                            inputmode="numeric"
                                                            aria-labelledby="quantity"
                                                    ></b-form-input>
                                                </div>
                                                <div class="col-12 col-md-6 mb-0 pr-0">
                                                    <button class="btn button product_type_simple add_to_cart_button m-2 text-center" data-dismiss="modal" v-on:click ="addToCart(show_product)" type="button" id="loading" :disabled="loading">
                                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="loading"></span>
                                                        <span class="sr-only" v-if="loading">Loading...</span>Add to cart
                                                    </button>
                                                </div>

                                            </div>



                                            <div class="highlightlinks">
                                                <div class="product_meta">
                                                    <span class="posted_in">Category: {{show_product.category}}</span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!--eof .col-sm- .summary -->
                                </div>
                                <!--eof .row -->
                                <div class="woocommerce-tabs wc-tabs-wrapper">
                                    <ul class="nav nav-tabs tabs wc-tabs" role="tablist">
                                        <li class=" nav-item description_tab active" id="tab-title-description" data-toggle="tab" href="#tab-description" role="tab" aria-controls="tab-description">
                                            <a href="#tab-description">Description</a>
                                        </li>
                                        <li class="nav-item reviews_tab" id="tab-title-reviews" data-toggle="tab" href="#tab-reviews" role="tab" aria-controls="tab-reviews">
                                            <a href="#tab-reviews">Reviews</a>
                                        </li>
                                    </ul>

                                    <div class="tab-pane active woocommerce-Tabs-panel woocommerce-Tabs-panel--description panel entry-content wc-tab" id="tab-description" role="tabpanel" aria-labelledby="tab-title-description">

                                        <h2>Description</h2>
                                        <p>{{show_product.description}}</p>

<!--                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum sagittis orci ac odio dictum tincidunt. Donec ut metus leo. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.-->
<!--                                            Sed luctus, dui eu sagittis sodales, nulla nibh sagittis augue, vel porttitor diam enim non metus. Vestibulum aliquam augue neque. Phasellus tincidunt odio eget ullamcorper efficitur. Cras placerat ut turpis-->
<!--                                            pellentesque vulputate. Nam sed consequat tortor. Curabitur finibus sapien dolor. Ut eleifend tellus nec erat pulvinar dignissim. Nam non arcu purus. Vivamus et massa massa.</p>-->
                                    </div>
                                    <div class="tab-pane woocommerce-Tabs-panel woocommerce-Tabs-panel--reviews panel entry-content wc-tab" id="tab-reviews" role="tabpanel" aria-labelledby="tab-title-reviews">
                                        <div id="reviews" class="woocommerce-Reviews">
                                            <div id="comments">
                                                <h2 class="woocommerce-Reviews-title">Reviews for <span>{{show_product.title}}</span></h2>


                                                <ol class="commentlist">
                                                    <li v-for="rev in show_product.reviews"class="review even thread-even depth-1" id="li-comment-34">

                                                        <div id="comment-34" class="comment_container">

                                                            <img alt='' src="https://0.gravatar.com/avatar/?s=60&#038;d=mm&#038;r=g" srcset='http://2.gravatar.com/avatar/?s=120&#038;d=mm&#038;r=g 2x' class='avatar avatar-60 photo avatar-default' height='60' width='60' />
                                                            <div class="comment-text">
                                                                <p class="meta">
                                                                    <strong class="woocommerce-review__author">{{rev.name}} </strong>
                                                                    <span class="woocommerce-review__dash">&ndash;</span>
                                                                   {{ rev.created_at | moment('DD.MM.YYYY')}}
                                                                </p>

                                                                <div class="description">
                                                                    <p>{{rev.message}}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ol>
                                            </div>
                                            <div id="review_form_wrapper">
                                                <div id="review_form">
                                                    <div id="respond" class="comment-respond">
                                                        <span id="reply-title" class="comment-reply-title">Add a review <small><a rel="nofollow" id="cancel-comment-reply-link" href="/colorlab/product/album/#respond" style="display:none;">Cancel reply</a></small></span>
                                                        <div class="comment-form">
                                                            <p class="comment-notes">
                                                                <span id="email-notes" style="color: white">Your email address will not be published.</span>
                                                            </p>
                                                            <p class="comment-form-comment">
                                                                <label for="comment">Your review&nbsp;<span class="required">*</span></label>
                                                                <textarea id="comment" v-model="review.message" name="comment" cols="45" rows="8" required></textarea>
                                                            </p>
                                                            <p class="comment-form-author">
                                                                <label for="author">Name&nbsp;<span class="required">*</span></label>
                                                                <input id="author" v-model="review.name" name="author" type="text" value="" size="30" required />
                                                            </p>
                                                            <p class="comment-form-email">
                                                                <label for="email">Email&nbsp;<span class="required">*</span></label>
                                                                <input id="email" v-model="review.email" name="email" type="email" value="" size="30" required />
                                                            </p>
                                                            <p class="form-submit">
                                                                <button
                                                                        class="button product_type_simple add_to_cart_button ajax_add_to_cart"
                                                                        @click="sendReview"
                                                                >
                                                                   Submit
                                                                </button>
<!--                                                                <input name="submit" style="color:white" type="submit" id="submit" class="submit" value="Submit" />-->
<!--                                                                <input type='hidden' name='comment_post_ID' value='1209' id='comment_post_ID' />-->
<!--                                                                <input type='hidden' name='comment_parent' id='comment_parent' value='0' />-->
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <footer class="page_footer cs main_color7 section_padding_top_110 section_padding_bottom_115 columns_margin_bottom_40">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-4">
                            <div class="widget-theme-wrapper widget_no_background ">
                                <div id="mwt_teaser-2" class="widget widget_teaser">
                                    <div class="teaser text-center  ">
                                        <div class="teaser_icon size_huge highlight3 image_icon">
                                            <img width="120" height="121" src="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/08/clr-phone-call-3.png" class="attachment-thumbnail size-thumbnail" alt="" srcset="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/08/clr-phone-call-3.png 120w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/08/clr-phone-call-3-100x100.png 100w"
                                                 sizes="(max-width: 120px) 100vw, 120px" /> </div>

                                        <p><span class="small-text highlight3">call us 24/7</span><br /><span class="big black">0-000-000-0000</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <div class="widget-theme-wrapper widget_no_background ">
                                <div id="mwt_teaser-3" class="widget widget_teaser">
                                    <div class="teaser text-center  ">
                                        <div class="teaser_icon size_huge highlight8 image_icon">
                                            <img width="120" height="85" src="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/08/clr-mail-5.png" class="attachment-thumbnail size-thumbnail" alt="" /> </div>

                                        <p>
                                            <span class="small-text highlight8">write us</span>
                                            <br />
                                            <span class="big black darklinks">
                                                <a href="mailto:clab@support.com">colorit@support.com</a>
                                            </span>
                                        </p>


                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            <div class="widget-theme-wrapper widget_no_background ">
                                <div id="mwt_teaser-4" class="widget widget_teaser">
                                    <div class="teaser text-center  ">
                                        <div class="teaser_icon size_huge highlight9 image_icon">
                                            <img width="120" height="120" src="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/08/clr-clock-5.png" class="attachment-thumbnail size-thumbnail" alt="" srcset="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/08/clr-clock-5.png 120w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/08/clr-clock-5-100x100.png 100w"
                                                 sizes="(max-width: 120px) 100vw, 120px" /> </div>

                                        <p><span class="small-text highlight9">7 days a week</span><br /><span class="big black">9:00am - 8:00PM</span></p>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- .page_footer -->
            <section class="page_copyright cs section_padding_top_110 section_padding_bottom_100">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 text-center">
                            <p class="page_social bottommargin_20">
                                <span class="social-icons ">
                                    <a href=""
                                       class="social-icon socicon-facebook light-bg-icon color-icon rounded-icon" target="_blank">
                                    </a>
                                    <a href=""
                                       class="social-icon socicon-twitter light-bg-icon color-icon rounded-icon" target="_blank">
                                    </a>
                                    <a href=""
                                       class="social-icon socicon-googleplus light-bg-icon color-icon rounded-icon" target="_blank">
                                    </a>
                                </span>
                            </p>
                            <p><span class="small-text black">© Copyright 2021. All Rights Reserved</span></p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ProductPage",
        props:['product'],
        data: () => ({
            loading: false,
            size: [],
            orderColor: null,
            orderSize: null,
            colors: [ {name:'White', value:'white'},
                {name:'Blue', value:'blue'},
                {name:'Red', value:'red'},
                {name:'Yellow', value:'yellow'},
                {name:'Green', value:'green'},
                {name:'Purple', value:'purple'},
                {name:'Orange', value:'orange'},
                {name:'Black', value:'black'},
                {name:'Gray', value:'gray'}
            ],
            sizes:[
                {name:'XS', tooltip:'42'},
                {name:'S', tooltip:'44-46'},
                {name:'M', tooltip:'48-50'},
                {name:'L', tooltip:'52'},
                {name:'XL', tooltip:'54-56'},
                {name:'XXL', tooltip:'58-60'},
            ],
            review: {
                email:'',
                name:'',
                phone:'',
                message:''
            },
            show_product: {
                id:'',
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
                src:[],
                reviews:[]
            },
            quantity: 1,
        }),
        mounted() {
            this.show_product={
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
                src:this.product.src,
                reviews:this.product.reviews
            };
            if( this.show_product.color != null &&  this.show_product.color.length >= 1) {
                this.orderColor =  this.show_product.color[0]['name'];
            }
            if( this.show_product.size != null &&  this.show_product.size.length >= 1) {
                this.orderSize = this.show_product.size[0]['name'];
            }
        },
        methods: {
            async addToCart(item) {
                this.loading = true;
                let order = {
                    product: item,
                    color: this.orderColor,
                    size: this.orderSize,
                    quantity: this.quantity,
                };
                if(order.color != null && order.color.length == 1 && order.color[0]['name']) {
                    order.color = order.color[0]['name'];
                }
                if(order.size != null && order.size.length == 1 && order.size[0]['name']) {
                    order.size = order.size[0]['name'];
                }
                this.$store.dispatch('addProductToCart', order);
                this.loading = false;
                this.sendMessage('Product added to cart successfully');
            },
            sendReview() {
                this.review.id = this.product.id;
                this.review.created_at = Date.now();
                this.show_product.reviews.push(this.review);
                axios.post('/sendReview', this.review).then( resp => {
                    this.sendMessage(resp.data.message);
                })
            },
            changeQuantity(e) {
                this.quantity = e.target.value;
                console.log(e)
            },
            sendMessage(message, type='success') {
                this.$notify({
                    group: 'info',
                    type: type,
                    title: 'Product',
                    text: message
                });
            },
        }
    }
</script>

<style scoped>

</style>