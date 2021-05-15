<template>
    <div id="canvas" class="">
        <div class="modal fade" id="quickview" tabindex="-1" role="dialog" aria-labelledby="quickviewLabel">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="row w-100 m-auto align-items-center">
                            <img src="/img/2colorit.png"  class="mr-auto" alt="Image" style="height: 20px;">
                            <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="quickview_body">
                            <div class="container" v-if="selectedItem!==null">
                                <div class="row">
                                    <div class="col-12 col-lg-5">
<!--                                        <div class="quickview_pro_img slick-wrapper">-->
<!--                                            <div class="slick5">-->
<!--                                                <div class="slide-item mt-auto mb-auto" v-for="img in selectedItem.src">-->
<!--                                                    <a :href="img.path" class="fancybox7" data-fancybox="gallery">-->
<!--                                                        <img :src="img.path" alt="Image" class="mt-auto mb-auto lazyload" style="height: 400px; width: 100%; object-fit: contain;">-->
<!--                                                    </a>-->
<!--                                                </div>-->
<!--                                            </div>-->
                                            <b-carousel
                                                    id="carousel-fade"
                                                    style="text-shadow: 0px 0px 2px #000; width:100%; height:100%"
                                                    fade
                                                    indicators
                                                    img-width="600"
                                                    img-height="600"
                                            >
<!--                                                <b-carousel-slide  v-for="img in selectedItem.src" :key="img.name"-->
<!--                                                                :img-src="img.path"-->
<!--                                                                style="width:100%; height:100%"-->
<!--                                                ></b-carousel-slide>-->
                                                <b-carousel-slide v-for="img in selectedItem.src" :key="img.name" style="width:100%; height:100%">
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
<!--                                        </div>-->
                                    </div>
                                    <div class="col-12 col-lg-7">
                                        <div class="quickview_pro_des">
                                            <h4 class="title" style="color: #453074;">{{selectedItem.title}}</h4>
                                            <h5 class="price" style="color: #453074;">Price: <strong style="color: #ff4073;">{{selectedItem.price | currency}}</strong></h5>
                                            <div class="swatches">
                                                <div class="swatch clearfix mr-5" v-if="selectedItem.size != null && selectedItem.size.length >= 1" data-option-index="0">
                                                    <div class="header" style="color: #977ccc;">Size:</div>
                                                    <div v-for="size in selectedItem.size" :data-value="size.name" class="swatch-element plain">
                                                        <div class="tooltip">{{size.tooltip}}</div>
                                                        <input :id="'swatch-0-'+size.name" type="radio" name="orderSize" v-model="orderSize" :value="size.name" />
                                                        <label :for="'swatch-0-'+size.name">
                                                            {{size.name}}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="swatch clearfix" data-option-index="1" v-if="selectedItem.color != null && selectedItem.color.length >= 1">
                                                    <div class="header" style="color: #977ccc;">Color:</div>
                                                    <div class="swatch-element color" v-for ="color in selectedItem.color" :class="color.value" :data-value="color.value">
                                                        <div class="tooltip">{{color.name}}</div>
                                                        <input :id="color.value" type="radio" name="orderColor" v-model="orderColor" :value="color.name" />
                                                        <label :for="color.value">
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row justify-content-center align-items-center ml-auto mr-auto w-100" v-if="selectedItem!==null">
                            <a :href="'/products/'+selectedItem.id">
                                <button class="btn m-2 button product_type_simple add_to_cart_button" :disabled="loading">More</button>
                            </a>
                            <button class="btn button product_type_simple add_to_cart_button m-2 text-center" data-dismiss="modal" v-on:click ="addToCart(selectedItem)" type="button" id="loading" :disabled="loading">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="loading"></span>
                                <span class="sr-only">Loading...</span>Add to cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="box_wrapper" class="">
            <!-- template sections -->

            <page-header>
                <div class="header_filters mr-auto">
                    <span id="sideMenuBtn" class="toggle_filters_menu" v-b-toggle.sidebar-left>
                        <i class="fa fa-filter" style="color:white; font-size: 30px"></i>
                    </span>
                    <b-sidebar id="sidebar-left" title="" shadow>
                        <div class="ls categories-side-menu px-3">
                            <div class="widget-theme-wrapper">
                                <div class="widget widget_product_categories">
                                    <h3 class="widget-title">Product categories</h3>
                                    <ul class="product-categories">
                                        <li class="cat-item cat-item-45" :class="{selected: isActive('Accessories')}">
                                            <a href="#" @click.prevent="setActive('Accessories')">Accessories</a>
                                        </li>
                                        <li class="cat-item cat-item-44" :class="{selected: isActive('Hoodies')}">
                                            <a href="#" @click.prevent="setActive('Hoodies')">Hoodies</a>
                                        </li>
                                        <li class="cat-item cat-item-43" :class="{selected: isActive('Tshirts')}">
                                            <a href="#" @click.prevent="setActive('Tshirts')">Tshirts</a>
                                        </li>
                                        <li class="cat-item cat-item-47" :class="{selected: isActive('Decor')}">
                                            <a href="#" @click.prevent="setActive('Decor')">Decor</a>
                                        </li>
                                        <li class="cat-item cat-item-46" :class="{selected: isActive('Music')}">
                                            <a href="#" @click.prevent="setActive('Music')">Music</a>
                                        </li>
                                        <li class="cat-item cat-item-15" :class="{selected: isActive('Uncategorized')}">
                                            <a href="#" @click.prevent="setActive('Uncategorized')">Uncategorized</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="widget-theme-wrapper widget_no_background mt-4">
                                <div class="widget woocommerce widget_price_filter">
                                    <h3 class="widget-title">Filter by price</h3>
                                    <div class="price_slider_wrapper">
                                        <vue-slider v-model="price_range" :min="0" :max="max"  :min-range="100" :max-range="price_range[1]" :enable-cross="false"></vue-slider>
                                        <div class="row w-100 mx-auto mt-4 align-items-center">
                                            <div class="price-box">
                                                Min: <input v-model="price_range[0]"/> &pound;
                                            </div>
                                            <div class="ml-2 price-box">
                                                Max: <input v-model="price_range[1]"/> &pound;
                                            </div>
                                            <div class="price_label">
                                                Price:  from {{price_range[0]}} &pound; - to {{price_range[1]}} &pound;
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-theme-wrapper widget_no_background mt-4 mb-4">
                                <div  class="widget woocommerce widget_layered_nav woocommerce-widget-layered-nav">
                                    <h3 class="widget-title">Filter by</h3>
                                    <ul class="woocommerce-widget-layered-nav-list">
                                        <li v-for ="color in colors" @click.prevent = "setActive(color.name)" class="woocommerce-widget-layered-nav-list__item wc-layered-nav-term">
                                            <a rel="nofollow" href="#">{{color.name}}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="widget-theme-wrapper widget_no_background mb-4" v-if="filtersApplied.length > 0">
                                <div  class="widget woocommerce widget_product_tag_cloud">
                                    <h3 class="widget-title">Product tags</h3>
                                    <div class="tagcloud row w-100 m-auto">
                                        <div v-for ="filterApplied in filtersApplied" class="tag-cloud-link tag-link-56 tag-link-position-1" style="font-size: 8pt;">
                                            <a class="tag-cloud-link tag-link-56 tag-link-position-1" @click='removeTags(filterApplied)'> {{filterApplied}} <span style="cursor:pointer">X</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </b-sidebar>
                </div>
            </page-header>

            <section class="page_breadcrumbs cs main_color6 section_padding_25 background_cover" style="background-image: url(http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/breadcrumbs1.jpg)">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 text-center darklinks">
                            <h1><span class="taxonomy-name-title">Archives: </span>Products </h1>
                            <ol class="breadcrumb d-block">
                                <li class="first-item">
                                    <a href="/">Homepage</a>
                                </li>
                                <li class="last-item">
                                    <span>Products</span>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
            <section class="ls page_content section_padding_top_150 section_padding_bottom_150 columns_padding_30">
                <div class="container-fluid">
                    <div class="row w-100 m-auto">
                        <!-- main aside sidebar -->
                        <div class="d-none d-lg-block col-lg-4">
                            <div class="widget-theme-wrapper">
                                <div class="widget widget_product_categories">
                                    <h3 class="widget-title">Product categories</h3>
                                    <ul class="product-categories">
                                        <li class="cat-item cat-item-45" :class="{selected: isActive('Accessories')}">
                                            <a href="#" @click.prevent="setActive('Accessories')">Accessories</a>
                                        </li>
                                        <li class="cat-item cat-item-44" :class="{selected: isActive('Hoodies')}">
                                            <a href="#" @click.prevent="setActive('Hoodies')">Hoodies</a>
                                        </li>
                                        <li class="cat-item cat-item-43" :class="{selected: isActive('Tshirts')}">
                                            <a href="#" @click.prevent="setActive('Tshirts')">Tshirts</a>
                                        </li>
                                        <li class="cat-item cat-item-47" :class="{selected: isActive('Decor')}">
                                            <a href="#" @click.prevent="setActive('Decor')">Decor</a>
                                        </li>
                                        <li class="cat-item cat-item-46" :class="{selected: isActive('Music')}">
                                            <a href="#" @click.prevent="setActive('Music')">Music</a>
                                        </li>
                                        <li class="cat-item cat-item-15" :class="{selected: isActive('Uncategorized')}">
                                            <a href="#" @click.prevent="setActive('Uncategorized')">Uncategorized</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="widget-theme-wrapper widget_no_background mt-4">
                                <div class="widget woocommerce widget_price_filter">
                                    <h3 class="widget-title">Filter by price</h3>
                                    <div class="price_slider_wrapper">
                                        <vue-slider v-model="price_range" :min="0" :max="max"  :min-range="100" :max-range="price_range[1]" :enable-cross="false"></vue-slider>
                                        <div class="row w-100 mx-auto mt-4 align-items-center">
                                            <div class="price-box">
                                                Min: <input v-model="price_range[0]"/> &pound;
                                            </div>
                                            <div class="ml-2 price-box">
                                                Max: <input v-model="price_range[1]"/> &pound;
                                            </div>
                                            <div class="price_label">
                                                Price:  from {{price_range[0]}} &pound; - to {{price_range[1]}} &pound;
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-theme-wrapper widget_no_background mt-4 mb-4">
                                <div  class="widget woocommerce widget_layered_nav woocommerce-widget-layered-nav">
                                    <h3 class="widget-title">Filter by</h3>
                                    <ul class="woocommerce-widget-layered-nav-list">
                                        <li v-for ="color in colors" @click.prevent = "setActive(color.name)" class="woocommerce-widget-layered-nav-list__item wc-layered-nav-term">
                                            <a rel="nofollow" href="#">{{color.name}}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="widget-theme-wrapper widget_no_background mb-4" v-if="filtersApplied.length > 0">
                                <div  class="widget woocommerce widget_product_tag_cloud">
                                    <h3 class="widget-title">Product tags</h3>
                                    <div class="tagcloud row w-100 m-auto">
                                        <div v-for ="filterApplied in filtersApplied" class="tag-cloud-link tag-link-56 tag-link-position-1" style="font-size: 8pt;">
                                            <a class="tag-cloud-link tag-link-56 tag-link-position-1" @click='removeTags(filterApplied)'> {{filterApplied}} <span style="cursor:pointer">X</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- eof main aside sidebar -->
                        <div id="content_products" class="col-xs-12 col-md-12 col-lg-8">
                            <div class="container-fluid overflow-hidden">
                                <div class="form-inline content-justify v-center float-right mrs-1">

                                    <div class="storefront-sorting">
                                        <b-form-select
                                                class="orderby"
                                                v-model="sort"
                                                value-field="value"
                                                text-field="text"
                                                :options="sortOptions"
                                                placeholder="Sorting"
                                        >
                                        </b-form-select>

<!--                                       <select name="orderby" class="orderby" v-model="sort" placeholder="Sorting">-->
<!--                                            <option :value="'low'" >Sort by price: low to high</option>-->
<!--                                            <option :value="'high'" >Sort by price: high to low</option>-->
<!--                                        </select>-->
                                    </div>
                                </div>
                                <div class="row w-100 m-auto row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-2 row-cols-xl-2 row-cols-xxl-3 g-3">
                                    <div v-for="product in filteredItems" :key="product.id" class="col product py-2 px-0 px-sm-1 px-md-2" style="max-height: 615px; min-height: 450px">
                                        <div class="h-100 w-100 vertical-item content-padding big-padding with_border text-center">
                                            <div class="item-media-wrap with_border" style="height: 50%; min-height:250px; max-height: 50%;">
                                                <div class="item-media" style="height: 100%">
                                                    <span v-if="product.type == 'sale'" class="onsale">Sale!</span>
                                                    <span v-if="product.type == 'hot'" class="onsale">Hot</span>
                                                    <img v-if="product.src == null || product.src.length<1 "
                                                         width="600" height="600"
                                                         src="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/beanie-2-600x600.jpg"
                                                         class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail"
                                                         alt=""
                                                         sizes="(max-width: 600px) 100vw, 600px"
                                                         style="height:100%; width:100%;object-fit: fill;"
                                                    />
                                                    <img v-else
                                                         width="600" height="600"
                                                         :src="product.src[0].path"
                                                         class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail"
                                                         alt=""
                                                         sizes="(max-width: 600px) 100vw, 600px"
                                                         style="height:100%; width:100%;object-fit: fill;"
                                                    />
                                                </div>
                                                <!-- eof .item-media -->
                                            </div>
                                            <!-- eof .item-media-wrap -->
                                            <div class="item-content" style="height: 50%">
                                                <a :href="'/products/'+product.id" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                                                    <h2 class="woocommerce-loop-product__title">{{product.title}}</h2>
                                                </a>
                                                <!--                                                <div class="star-rating">-->
                                                <!--                                                    <span style="width:51%">Rated <strong class="rating">{{product.rating}}</strong> out of 5</span>-->
                                                <!--                                                </div>-->
                                                <span class="price" v-if="product.type=='sale'">
                                                    <del>
                                                        <span class="woocommerce-Price-amount amount">
                                                            <span class="woocommerce-Price-currencySymbol">&pound;</span>
                                                            {{product.price}}
                                                        </span>
                                                    </del>
                                                    <ins>
                                                        <span class="woocommerce-Price-amount amount">
                                                            <span class="woocommerce-Price-currencySymbol">&pound;</span>
                                                            {{product.discount_price}}
                                                        </span>
                                                    </ins>
                                                </span>
                                                <span class="price" v-else>
                                                    <span class="woocommerce-Price-amount amount">
                                                        <span class="woocommerce-Price-currencySymbol">&pound;</span>
                                                        {{product.price}}
                                                    </span>
                                                </span>
                                                <p class="topmargin_10">
                                                    <button
                                                            class="button product_type_simple add_to_cart_button ajax_add_to_cart"
                                                            @click="sendInfo(product)"
                                                            data-toggle="modal" data-target="#quickview"
                                                    >
                                                        Add to cart
                                                    </button>
                                                </p>
                                            </div>
                                            <!-- eof .item-content -->
                                        </div>
                                    </div>
                                </div>
                            </div>


<!--                            <div class="columns-2">-->
<!--                                <div class="form-inline content-justify v-center">-->
<!--                                    <p class="woocommerce-result-count">-->
<!--&lt;!&ndash;                                        Showing 1&ndash;8 of 17 results&ndash;&gt;-->
<!--                                    </p>-->
<!--                                    <div class="storefront-sorting">-->
<!--&lt;!&ndash;                                            <multiselect v-model="sort" :options="sortOptions" :searchable="false" :close-on-select="true" :selectLabel="''" :selectedLabel="''" :deselectLabel="'X'" placeholder="Нет"></multiselect>&ndash;&gt;-->
<!--                                        <select name="orderby" class="orderby" v-model="sort" placeholder="Sorting">-->
<!--&lt;!&ndash;                                                <option value="popularity"  selected='selected'>Sort by popularity</option>&ndash;&gt;-->
<!--&lt;!&ndash;                                                <option value="rating" >Sort by average rating</option>&ndash;&gt;-->
<!--&lt;!&ndash;                                                <option value="date" >Sort by latest</option>&ndash;&gt;-->
<!--                                            <option value="Sort by price: low to high" >Sort by price: low to high</option>-->
<!--                                            <option value="Sort by price: high to low" >Sort by price: high to low</option>-->
<!--                                        </select>-->
<!--&lt;!&ndash;                                            <input type="hidden" name="paged" value="1" />&ndash;&gt;-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <ul class="products list-unstyled">-->
<!--&lt;!&ndash;                                    <li class="post-1209 product type-product status-publish has-post-thumbnail product_cat-music product_tag-tag-1 product_tag-tag-2 product_tag-tag-3 first instock downloadable virtual purchasable product-type-simple">&ndash;&gt;-->
<!--&lt;!&ndash;                                        <div class="vertical-item content-padding big-padding with_border text-center">&ndash;&gt;-->
<!--&lt;!&ndash;                                            <div class="item-media-wrap">&ndash;&gt;-->
<!--&lt;!&ndash;                                                <div class="item-media">&ndash;&gt;-->
<!--&lt;!&ndash;                                                    <a href="http://webdesign-finder.com/colorlab/product/album/"&ndash;&gt;-->
<!--&lt;!&ndash;                                                       class="woocommerce-LoopProduct-link woocommerce-loop-product__link"&ndash;&gt;-->
<!--&lt;!&ndash;                                                    >&ndash;&gt;-->
<!--&lt;!&ndash;                                                        <img width="600" height="600"&ndash;&gt;-->
<!--&lt;!&ndash;                                                             src="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/album-1-600x600.jpg"&ndash;&gt;-->
<!--&lt;!&ndash;                                                             class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail"&ndash;&gt;-->
<!--&lt;!&ndash;                                                             alt=""&ndash;&gt;-->
<!--&lt;!&ndash;                                                             srcset="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/album-1-600x600.jpg 600w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/album-1-150x150.jpg 150w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/album-1-300x300.jpg 300w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/album-1-768x768.jpg 768w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/album-1-100x100.jpg 100w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/album-1.jpg 800w"&ndash;&gt;-->
<!--&lt;!&ndash;                                                             sizes="(max-width: 600px) 100vw, 600px"&ndash;&gt;-->
<!--&lt;!&ndash;                                                        />&ndash;&gt;-->
<!--&lt;!&ndash;                                                    </a>&ndash;&gt;-->
<!--&lt;!&ndash;                                                </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                                &lt;!&ndash; eof .item-media &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                            </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                            &lt;!&ndash; eof .item-media-wrap &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                            <div class="item-content">&ndash;&gt;-->
<!--&lt;!&ndash;                                                <a href="http://webdesign-finder.com/colorlab/product/album/" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">&ndash;&gt;-->
<!--&lt;!&ndash;                                                    <h2 class="woocommerce-loop-product__title">Album</h2>&ndash;&gt;-->
<!--&lt;!&ndash;                                                </a>&ndash;&gt;-->
<!--&lt;!&ndash;                                                <div class="star-rating">&ndash;&gt;-->
<!--&lt;!&ndash;                                                    <span style="width:50.6%">Rated <strong class="rating">2.53</strong> out of 5</span>&ndash;&gt;-->
<!--&lt;!&ndash;                                                </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                                <span class="price">&ndash;&gt;-->
<!--&lt;!&ndash;                                                    <span class="woocommerce-Price-amount amount">&ndash;&gt;-->
<!--&lt;!&ndash;                                                        <span class="woocommerce-Price-currencySymbol">&pound;</span>&ndash;&gt;-->
<!--&lt;!&ndash;                                                        15.00&ndash;&gt;-->
<!--&lt;!&ndash;                                                    </span>&ndash;&gt;-->
<!--&lt;!&ndash;                                                </span>&ndash;&gt;-->
<!--&lt;!&ndash;                                                <p class="topmargin_10">&ndash;&gt;-->
<!--&lt;!&ndash;                                                    <a href="/colorlab/shop/?add-to-cart=1209"&ndash;&gt;-->
<!--&lt;!&ndash;                                                       class="button product_type_simple add_to_cart_button ajax_add_to_cart"&ndash;&gt;-->
<!--&lt;!&ndash;                                                       aria-label="Add &ldquo;Album&rdquo; to your cart"&ndash;&gt;-->
<!--&lt;!&ndash;                                                       rel="nofollow"&ndash;&gt;-->
<!--&lt;!&ndash;                                                    >&ndash;&gt;-->
<!--&lt;!&ndash;                                                        Add to cart&ndash;&gt;-->
<!--&lt;!&ndash;                                                    </a>&ndash;&gt;-->
<!--&lt;!&ndash;                                                </p>&ndash;&gt;-->
<!--&lt;!&ndash;                                            </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                            &lt;!&ndash; eof .item-content &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                        </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                        &lt;!&ndash; eof .vertical-item &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                    </li>&ndash;&gt;-->

<!--                                    <li v-for="product in filteredItems" class="post-1201 product type-product status-publish has-post-thumbnail product_cat-accessories last instock sale shipping-taxable purchasable product-type-simple">-->
<!--                                        <div class="vertical-item content-padding big-padding with_border text-center">-->
<!--                                            <div class="item-media-wrap">-->
<!--                                                <div class="item-media">-->
<!--                                                    <span v-if="product.type == 'sale'" class="onsale">Sale!</span>-->
<!--                                                    <span v-if="product.type == 'hot'" class="onsale">Hot</span>-->
<!--                                                    <img v-if="product.src == null || product.src.length<1 "-->
<!--                                                            width="600" height="600"-->
<!--                                                         src="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/beanie-2-600x600.jpg"-->
<!--                                                         class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail"-->
<!--                                                         alt=""-->
<!--                                                        sizes="(max-width: 600px) 100vw, 600px"-->
<!--                                                    />-->
<!--                                                    <img v-else-->
<!--                                                         width="600" height="600"-->
<!--                                                         :src="product.src[0].path"-->
<!--                                                         class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail"-->
<!--                                                         alt=""-->
<!--                                                         sizes="(max-width: 600px) 100vw, 600px"-->
<!--                                                         style="min-height: 365px;"-->
<!--                                                    />-->
<!--                                                </div>-->
<!--                                                &lt;!&ndash; eof .item-media &ndash;&gt;-->
<!--                                            </div>-->
<!--                                            &lt;!&ndash; eof .item-media-wrap &ndash;&gt;-->
<!--                                            <div class="item-content">-->
<!--                                                <a :href="'/products/'+product.id" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">-->
<!--                                                    <h2 class="woocommerce-loop-product__title">{{product.title}}</h2>-->
<!--                                                </a>-->
<!--&lt;!&ndash;                                                <div class="star-rating">&ndash;&gt;-->
<!--&lt;!&ndash;                                                    <span style="width:51%">Rated <strong class="rating">{{product.rating}}</strong> out of 5</span>&ndash;&gt;-->
<!--&lt;!&ndash;                                                </div>&ndash;&gt;-->
<!--                                                <span class="price" v-if="product.type=='sale'">-->
<!--                                                    <del>-->
<!--                                                        <span class="woocommerce-Price-amount amount">-->
<!--                                                            <span class="woocommerce-Price-currencySymbol">&pound;</span>-->
<!--                                                            {{product.price}}-->
<!--                                                        </span>-->
<!--                                                    </del>-->
<!--                                                    <ins>-->
<!--                                                        <span class="woocommerce-Price-amount amount">-->
<!--                                                            <span class="woocommerce-Price-currencySymbol">&pound;</span>-->
<!--                                                            {{product.discount_price}}-->
<!--                                                        </span>-->
<!--                                                    </ins>-->
<!--                                                </span>-->
<!--                                                <span class="price" v-else>-->
<!--                                                    <span class="woocommerce-Price-amount amount">-->
<!--                                                        <span class="woocommerce-Price-currencySymbol">&pound;</span>-->
<!--                                                        {{product.price}}-->
<!--                                                    </span>-->
<!--                                                </span>-->
<!--                                                <p class="topmargin_10">-->
<!--                                                    <button-->
<!--                                                       class="button product_type_simple add_to_cart_button ajax_add_to_cart"-->
<!--                                                       @click="sendInfo(product)"-->
<!--                                                       data-toggle="modal" data-target="#quickview"-->
<!--                                                    >-->
<!--                                                        Add to cart-->
<!--                                                    </button>-->
<!--                                                </p>-->
<!--                                            </div>-->
<!--                                            &lt;!&ndash; eof .item-content &ndash;&gt;-->
<!--                                        </div>-->
<!--                                        &lt;!&ndash; eof .vertical-item &ndash;&gt;-->
<!--                                    </li>-->
<!--&lt;!&ndash;                                    <li class="post-1218 product type-product status-publish has-post-thumbnail product_cat-accessories first instock sale shipping-taxable purchasable product-type-simple">&ndash;&gt;-->
<!--&lt;!&ndash;                                        <div class="vertical-item content-padding big-padding with_border text-center">&ndash;&gt;-->
<!--&lt;!&ndash;                                            <div class="item-media-wrap">&ndash;&gt;-->
<!--&lt;!&ndash;                                                <div class="item-media"><a href="http://webdesign-finder.com/colorlab/product/beanie-with-logo/" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">&ndash;&gt;-->
<!--&lt;!&ndash;                                                    <span class="onsale">Sale!</span>&ndash;&gt;-->
<!--&lt;!&ndash;                                                    <img width="600" height="600" src="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/beanie-with-logo-1-600x600.jpg" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="" srcset="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/beanie-with-logo-1-600x600.jpg 600w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/beanie-with-logo-1-150x150.jpg 150w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/beanie-with-logo-1-300x300.jpg 300w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/beanie-with-logo-1-768x768.jpg 768w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/beanie-with-logo-1-100x100.jpg 100w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/beanie-with-logo-1.jpg 800w" sizes="(max-width: 600px) 100vw, 600px" /></a></div>&ndash;&gt;-->
<!--&lt;!&ndash;                                                &lt;!&ndash; eof .item-media &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                            </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                            &lt;!&ndash; eof .item-media-wrap &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                            <div class="item-content">&ndash;&gt;-->
<!--&lt;!&ndash;                                                <a href="http://webdesign-finder.com/colorlab/product/beanie-with-logo/" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">&ndash;&gt;-->
<!--&lt;!&ndash;                                                    <h2 class="woocommerce-loop-product__title">Beanie with Logo</h2>&ndash;&gt;-->
<!--&lt;!&ndash;                                                </a>&ndash;&gt;-->
<!--&lt;!&ndash;                                                <div class="star-rating"><span style="width:53.6%">Rated <strong class="rating">2.68</strong> out of 5</span></div>&ndash;&gt;-->
<!--&lt;!&ndash;                                                <span class="price"><del><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&pound;</span>20.00</span>&ndash;&gt;-->
<!--&lt;!&ndash;                                                </del> <ins><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&pound;</span>18.00</span></ins></span>&ndash;&gt;-->
<!--&lt;!&ndash;                                                <p class="topmargin_10"><a href="/colorlab/shop/?add-to-cart=1218" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="1218" data-product_sku="Woo-beanie-logo" aria-label="Add &ldquo;Beanie with Logo&rdquo; to your cart"&ndash;&gt;-->
<!--&lt;!&ndash;                                                                           rel="nofollow">Add to cart</a></p>&ndash;&gt;-->
<!--&lt;!&ndash;                                            </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                            &lt;!&ndash; eof .item-content &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                        </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                        &lt;!&ndash; eof .vertical-item &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                    </li>&ndash;&gt;-->
<!--&lt;!&ndash;                                    <li class="post-1202 product type-product status-publish has-post-thumbnail product_cat-accessories last instock sale shipping-taxable purchasable product-type-simple">&ndash;&gt;-->
<!--&lt;!&ndash;                                        <div class="vertical-item content-padding big-padding with_border text-center">&ndash;&gt;-->
<!--&lt;!&ndash;                                            <div class="item-media-wrap">&ndash;&gt;-->
<!--&lt;!&ndash;                                                <div class="item-media"><a href="http://webdesign-finder.com/colorlab/product/belt/" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">&ndash;&gt;-->
<!--&lt;!&ndash;                                                    <span class="onsale">Sale!</span>&ndash;&gt;-->
<!--&lt;!&ndash;                                                    <img width="600" height="600" src="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/belt-2-600x600.jpg" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="" srcset="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/belt-2-600x600.jpg 600w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/belt-2-150x150.jpg 150w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/belt-2-300x300.jpg 300w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/belt-2-768x768.jpg 768w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/belt-2-100x100.jpg 100w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/belt-2.jpg 801w" sizes="(max-width: 600px) 100vw, 600px" /></a></div>&ndash;&gt;-->
<!--&lt;!&ndash;                                                &lt;!&ndash; eof .item-media &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                            </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                            &lt;!&ndash; eof .item-media-wrap &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                            <div class="item-content">&ndash;&gt;-->
<!--&lt;!&ndash;                                                <a href="http://webdesign-finder.com/colorlab/product/belt/" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">&ndash;&gt;-->
<!--&lt;!&ndash;                                                    <h2 class="woocommerce-loop-product__title">Belt</h2>&ndash;&gt;-->
<!--&lt;!&ndash;                                                </a>&ndash;&gt;-->
<!--&lt;!&ndash;                                                <div class="star-rating"><span style="width:50.2%">Rated <strong class="rating">2.51</strong> out of 5</span></div>&ndash;&gt;-->
<!--&lt;!&ndash;                                                <span class="price"><del><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&pound;</span>65.00</span>&ndash;&gt;-->
<!--&lt;!&ndash;                                                </del> <ins><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&pound;</span>55.00</span></ins></span>&ndash;&gt;-->
<!--&lt;!&ndash;                                                <p class="topmargin_10"><a href="/colorlab/shop/?add-to-cart=1202" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="1202" data-product_sku="woo-belt" aria-label="Add &ldquo;Belt&rdquo; to your cart"&ndash;&gt;-->
<!--&lt;!&ndash;                                                                           rel="nofollow">Add to cart</a></p>&ndash;&gt;-->
<!--&lt;!&ndash;                                            </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                            &lt;!&ndash; eof .item-content &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                        </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                        &lt;!&ndash; eof .vertical-item &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                    </li>&ndash;&gt;-->
<!--&lt;!&ndash;                                    <li class="post-1203 product type-product status-publish has-post-thumbnail product_cat-accessories first instock sale featured shipping-taxable purchasable product-type-simple">&ndash;&gt;-->
<!--&lt;!&ndash;                                        <div class="vertical-item content-padding big-padding with_border text-center">&ndash;&gt;-->
<!--&lt;!&ndash;                                            <div class="item-media-wrap">&ndash;&gt;-->
<!--&lt;!&ndash;                                                <div class="item-media"><a href="http://webdesign-finder.com/colorlab/product/cap/" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">&ndash;&gt;-->
<!--&lt;!&ndash;                                                    <span class="onsale">Sale!</span>&ndash;&gt;-->
<!--&lt;!&ndash;                                                    <img width="600" height="600" src="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/cap-2-600x600.jpg" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="" srcset="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/cap-2-600x600.jpg 600w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/cap-2-150x150.jpg 150w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/cap-2-300x300.jpg 300w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/cap-2-768x768.jpg 768w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/cap-2-100x100.jpg 100w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/cap-2.jpg 801w" sizes="(max-width: 600px) 100vw, 600px" /></a></div>&ndash;&gt;-->
<!--&lt;!&ndash;                                                &lt;!&ndash; eof .item-media &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                            </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                            &lt;!&ndash; eof .item-media-wrap &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                            <div class="item-content">&ndash;&gt;-->
<!--&lt;!&ndash;                                                <a href="http://webdesign-finder.com/colorlab/product/cap/" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">&ndash;&gt;-->
<!--&lt;!&ndash;                                                    <h2 class="woocommerce-loop-product__title">Cap</h2>&ndash;&gt;-->
<!--&lt;!&ndash;                                                </a>&ndash;&gt;-->
<!--&lt;!&ndash;                                                <div class="star-rating"><span style="width:50.4%">Rated <strong class="rating">2.52</strong> out of 5</span></div>&ndash;&gt;-->
<!--&lt;!&ndash;                                                <span class="price"><del><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&pound;</span>18.00</span>&ndash;&gt;-->
<!--&lt;!&ndash;                                                </del> <ins><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&pound;</span>16.00</span></ins></span>&ndash;&gt;-->
<!--&lt;!&ndash;                                                <p class="topmargin_10"><a href="/colorlab/shop/?add-to-cart=1203" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="1203" data-product_sku="woo-cap" aria-label="Add &ldquo;Cap&rdquo; to your cart"&ndash;&gt;-->
<!--&lt;!&ndash;                                                                           rel="nofollow">Add to cart</a></p>&ndash;&gt;-->
<!--&lt;!&ndash;                                            </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                            &lt;!&ndash; eof .item-content &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                        </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                        &lt;!&ndash; eof .vertical-item &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                    </li>&ndash;&gt;-->
<!--&lt;!&ndash;                                    <li class="post-1198 product type-product status-publish has-post-thumbnail product_cat-hoodies last instock sale shipping-taxable purchasable product-type-variable has-default-attributes">&ndash;&gt;-->
<!--&lt;!&ndash;                                        <div class="vertical-item content-padding big-padding with_border text-center">&ndash;&gt;-->
<!--&lt;!&ndash;                                            <div class="item-media-wrap">&ndash;&gt;-->
<!--&lt;!&ndash;                                                <div class="item-media"><a href="http://webdesign-finder.com/colorlab/product/hoodie/" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">&ndash;&gt;-->
<!--&lt;!&ndash;                                                    <span class="onsale">Sale!</span>&ndash;&gt;-->
<!--&lt;!&ndash;                                                    <img width="600" height="600" src="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/hoodie-2-600x600.jpg" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="" srcset="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/hoodie-2-600x600.jpg 600w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/hoodie-2-150x150.jpg 150w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/hoodie-2-300x300.jpg 300w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/hoodie-2-768x768.jpg 768w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/hoodie-2-100x100.jpg 100w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/hoodie-2.jpg 801w" sizes="(max-width: 600px) 100vw, 600px" /></a></div>&ndash;&gt;-->
<!--&lt;!&ndash;                                                &lt;!&ndash; eof .item-media &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                            </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                            &lt;!&ndash; eof .item-media-wrap &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                            <div class="item-content">&ndash;&gt;-->
<!--&lt;!&ndash;                                                <a href="http://webdesign-finder.com/colorlab/product/hoodie/" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">&ndash;&gt;-->
<!--&lt;!&ndash;                                                    <h2 class="woocommerce-loop-product__title">Hoodie</h2>&ndash;&gt;-->
<!--&lt;!&ndash;                                                </a>&ndash;&gt;-->
<!--&lt;!&ndash;                                                <div class="star-rating"><span style="width:51.4%">Rated <strong class="rating">2.57</strong> out of 5</span></div>&ndash;&gt;-->
<!--&lt;!&ndash;                                                <span class="price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&pound;</span>42.00</span> &ndash; <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&pound;</span>45.00</span>&ndash;&gt;-->
<!--&lt;!&ndash;                                                </span>&ndash;&gt;-->
<!--&lt;!&ndash;                                                <p class="topmargin_10"><a href="http://webdesign-finder.com/colorlab/product/hoodie/" data-quantity="1" class="button product_type_variable add_to_cart_button" data-product_id="1198" data-product_sku="woo-hoodie" aria-label="Select options for &ldquo;Hoodie&rdquo;"&ndash;&gt;-->
<!--&lt;!&ndash;                                                                           rel="nofollow">Select options</a></p>&ndash;&gt;-->
<!--&lt;!&ndash;                                            </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                            &lt;!&ndash; eof .item-content &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                        </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                        &lt;!&ndash; eof .vertical-item &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                    </li>&ndash;&gt;-->
<!--&lt;!&ndash;                                    <li class="post-1199 product type-product status-publish has-post-thumbnail product_cat-hoodies first instock shipping-taxable purchasable product-type-simple">&ndash;&gt;-->
<!--&lt;!&ndash;                                        <div class="vertical-item content-padding big-padding with_border text-center">&ndash;&gt;-->
<!--&lt;!&ndash;                                            <div class="item-media-wrap">&ndash;&gt;-->
<!--&lt;!&ndash;                                                <div class="item-media"><a href="http://webdesign-finder.com/colorlab/product/hoodie-with-logo/" class="woocommerce-LoopProduct-link woocommerce-loop-product__link"><img width="600" height="600" src="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/hoodie-with-logo-2-600x600.jpg" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="" srcset="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/hoodie-with-logo-2-600x600.jpg 600w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/hoodie-with-logo-2-150x150.jpg 150w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/hoodie-with-logo-2-300x300.jpg 300w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/hoodie-with-logo-2-768x768.jpg 768w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/hoodie-with-logo-2-100x100.jpg 100w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/hoodie-with-logo-2.jpg 801w" sizes="(max-width: 600px) 100vw, 600px" /></a></div>&ndash;&gt;-->
<!--&lt;!&ndash;                                                &lt;!&ndash; eof .item-media &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                            </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                            &lt;!&ndash; eof .item-media-wrap &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                            <div class="item-content">&ndash;&gt;-->
<!--&lt;!&ndash;                                                <a href="http://webdesign-finder.com/colorlab/product/hoodie-with-logo/" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">&ndash;&gt;-->
<!--&lt;!&ndash;                                                    <h2 class="woocommerce-loop-product__title">Hoodie with Logo</h2>&ndash;&gt;-->
<!--&lt;!&ndash;                                                </a>&ndash;&gt;-->
<!--&lt;!&ndash;                                                <div class="star-rating"><span style="width:51.2%">Rated <strong class="rating">2.56</strong> out of 5</span></div>&ndash;&gt;-->
<!--&lt;!&ndash;                                                <span class="price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&pound;</span>45.00</span>&ndash;&gt;-->
<!--&lt;!&ndash;                                                </span>&ndash;&gt;-->
<!--&lt;!&ndash;                                                <p class="topmargin_10"><a href="/colorlab/shop/?add-to-cart=1199" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="1199" data-product_sku="woo-hoodie-with-logo" aria-label="Add &ldquo;Hoodie with Logo&rdquo; to your cart"&ndash;&gt;-->
<!--&lt;!&ndash;                                                                           rel="nofollow">Add to cart</a></p>&ndash;&gt;-->
<!--&lt;!&ndash;                                            </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                            &lt;!&ndash; eof .item-content &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                        </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                        &lt;!&ndash; eof .vertical-item &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                    </li>&ndash;&gt;-->
<!--&lt;!&ndash;                                    <li class="post-1206 product type-product status-publish has-post-thumbnail product_cat-hoodies last instock featured shipping-taxable purchasable product-type-simple">&ndash;&gt;-->
<!--&lt;!&ndash;                                        <div class="vertical-item content-padding big-padding with_border text-center">&ndash;&gt;-->
<!--&lt;!&ndash;                                            <div class="item-media-wrap">&ndash;&gt;-->
<!--&lt;!&ndash;                                                <div class="item-media"><a href="http://webdesign-finder.com/colorlab/product/hoodie-with-zipper/" class="woocommerce-LoopProduct-link woocommerce-loop-product__link"><img width="600" height="600" src="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/hoodie-with-zipper-2-600x600.jpg" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="" srcset="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/hoodie-with-zipper-2-600x600.jpg 600w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/hoodie-with-zipper-2-150x150.jpg 150w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/hoodie-with-zipper-2-300x300.jpg 300w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/hoodie-with-zipper-2-768x768.jpg 768w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/hoodie-with-zipper-2-100x100.jpg 100w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/hoodie-with-zipper-2.jpg 800w" sizes="(max-width: 600px) 100vw, 600px" /></a></div>&ndash;&gt;-->
<!--&lt;!&ndash;                                                &lt;!&ndash; eof .item-media &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                            </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                            &lt;!&ndash; eof .item-media-wrap &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                            <div class="item-content">&ndash;&gt;-->
<!--&lt;!&ndash;                                                <a href="http://webdesign-finder.com/colorlab/product/hoodie-with-zipper/" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">&ndash;&gt;-->
<!--&lt;!&ndash;                                                    <h2 class="woocommerce-loop-product__title">Hoodie with Zipper</h2>&ndash;&gt;-->
<!--&lt;!&ndash;                                                </a>&ndash;&gt;-->
<!--&lt;!&ndash;                                                <div class="star-rating"><span style="width:50.8%">Rated <strong class="rating">2.54</strong> out of 5</span></div>&ndash;&gt;-->
<!--&lt;!&ndash;                                                <span class="price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&pound;</span>45.00</span>&ndash;&gt;-->
<!--&lt;!&ndash;                                                </span>&ndash;&gt;-->
<!--&lt;!&ndash;                                                <p class="topmargin_10"><a href="/colorlab/shop/?add-to-cart=1206" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="1206" data-product_sku="woo-hoodie-with-zipper" aria-label="Add &ldquo;Hoodie with Zipper&rdquo; to your cart"&ndash;&gt;-->
<!--&lt;!&ndash;                                                                           rel="nofollow">Add to cart</a></p>&ndash;&gt;-->
<!--&lt;!&ndash;                                            </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                            &lt;!&ndash; eof .item-content &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                        </div>&ndash;&gt;-->
<!--&lt;!&ndash;                                        &lt;!&ndash; eof .vertical-item &ndash;&gt;&ndash;&gt;-->
<!--&lt;!&ndash;                                    </li>&ndash;&gt;-->
<!--                                </ul>-->
<!--                            </div>-->
                            <!-- eof .columns-2 -->
<!--                            <div class="text-center topmargin_30">-->
<!--                                <ul class="pagination">-->
<!--                                    <li class="prev active disabled"><span class="prev page-numbers"><span class="sr-only">Prev</span><i class="fa fa-angle-left"></i></span>-->
<!--                                    </li>-->
<!--                                    <li class='active'><span class='page-numbers current'>1</span></li>-->
<!--                                    <li><a class='page-numbers' href='http://webdesign-finder.com/colorlab/shop/page/2/'>2</a></li>-->
<!--                                    <li><a class='page-numbers' href='http://webdesign-finder.com/colorlab/shop/page/3/'>3</a></li>-->
<!--                                    <li class="next "> <a class="next page-numbers" href="http://webdesign-finder.com/colorlab/shop/page/2/"><span class="sr-only">Next</span><i class="fa fa-angle-right"></i></a> </li>-->
<!--                                </ul>-->
<!--                            </div>-->
                        </div>
                        <!-- eof #content_products -->
                    </div>
                    <!-- eof .row-->
                </div>
                <!-- eof .container -->
            </section>
            <!-- eof .page_content -->

            <footer class="page_footer cs main_color7 section_padding_top_110 section_padding_bottom_115 columns_margin_bottom_40">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-4">
                            <div class="widget-theme-wrapper widget_no_background ">
                                <div id="mwt_teaser-2" class="widget widget_teaser">
                                    <div class="teaser text-center  ">
                                        <div class="teaser_icon size_huge highlight3 image_icon">
                                            <img width="120" height="121" src="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/08/clr-phone-call-3.png" class="attachment-thumbnail size-thumbnail" alt="" srcset="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/08/clr-phone-call-3.png 120w, http://webdesign-finder.com/colorlab/wp-content/uploads/2018/08/clr-phone-call-3-100x100.png 100w"
                                                 sizes="(max-width: 120px) 100vw, 120px" />
                                        </div>
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
                                            <img width="120" height="85" src="http://webdesign-finder.com/colorlab/wp-content/uploads/2018/08/clr-mail-5.png" class="attachment-thumbnail size-thumbnail" alt="" />
                                        </div>
                                        <p>
                                            <span class="small-text highlight8">write us</span>
                                            <br />
                                            <span class="big black darklinks">
                                                <a href="mailto:colorit@support.com">colorit@support.com</a>
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
                                                 sizes="(max-width: 120px) 100vw, 120px" />
                                        </div>
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
                                    <a href="" class="social-icon socicon-facebook light-bg-icon color-icon rounded-icon" target="_blank"></a>
        		                    <a href="" class="social-icon socicon-twitter light-bg-icon color-icon rounded-icon" target="_blank"></a>
        		                    <a href="" class="social-icon socicon-googleplus light-bg-icon color-icon rounded-icon" target="_blank"></a>
                                </span>
                            </p>
                            <p><span class="small-text black">© Copyright 2021. All Rights Reserved</span></p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- eof #box_wrapper -->
    </div>
</template>

<script>
    export default {
        name: "Shop",
        props: ["products"],
        data: () => ({
            list:[],
            selectedItem: null,
            loading: false,
            qcolor: null,
            size: [],
            filtersApplied: [],
            orderColor: null,
            orderSize: null,
            price_range: [0, 1000],
            test: [],
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
            //materials:['leather','recycled cork','microfiber','mesh','wool','canvas','knit','Rubber'['black'], ['red'], ['gold']],
            sizes:[
                {name:'XS', tooltip:'42'},
                {name:'S', tooltip:'44-46'},
                {name:'M', tooltip:'48-50'},
                {name:'L', tooltip:'52'},
                {name:'XL', tooltip:'54-56'},
                {name:'XXL', tooltip:'58-60'},
            ],
            sort: '',
            sortOptions: [
                { value: '', text: 'Select sorting' },
                {text:'Sort by price: low to high', value:'low'},
                {text:'Sort by price: high to low', value:'high'},
            ],
            filters:false,
        }),
        created (){
            this.initialize();
            this.$store.dispatch('getProductList');
        },
        computed: {
            filteredItems: function() {
                return this.products.filter( product => {
                    if (product.price <= this.price_range[1] && product.price >= this.price_range[0] ) {
                        return this.filtersApplied.every( filterApplied => {
                            if(product.color != null) {
                                if (product.color.some( color => color['name'] === filterApplied )) {
                                    return product;
                                }
                            }
                            if(product.size != null) {
                                if (product.size.includes(filterApplied)) {
                                    return product.size.includes(filterApplied);
                                }
                            }
                            if (product.category.includes(filterApplied)) {
                                return product.category.includes(filterApplied);
                            }
                        })
                    }
                }).sort((a, b) => {
                    console.log('here');
                    if (this.sort =='high') {
                        console.log(Number(b.price)-Number(a.price));
                        return Number(b.price)-Number(a.price);
                    }
                    else if (this.sort =='low') {
                        return Number(a.price)-Number(b.price);
                    }
                })
            },
            max: function() {
                return Math.max.apply(Math, this.products.map(function(o) {
                    return o.price;
                }));
            },
            sortByLowPrice: function() {
                return this.products.sort(function(a,b) {
                    return a.price - b.price ;
                })
            },
            sortByHigherPrice: function() {
                return this.products.sort(function(a,b) {
                    return a.price - b.price ;
                })
            }
        },
        methods: {
            initialize () {
                this.list=this.products;
                //this.maxT = this.findMax();
                this.price_range[1] = this.max;
            },

            async addToCart (item) {
                this.loading = true;
                let order = {
                    product: item,
                    color: this.orderColor,
                    size: this.orderSize,
                    quantity: 1
                };
                // if(order.color != null && order.color.length == 1) {
                //     order.color = order.color[0]['name'];
                // }
                // if(order.size != null && order.size.length == 1) {
                //     order.size = order.size[0]['name'];
                // }
                this.$store.dispatch('addProductToCart', order);
                this.loading = false;
                this.sendMessage('Product added to cart successfully');
            },
            removeTags : function (item) {
                this.filtersApplied = this.filtersApplied.filter(tag => tag != item);
            },

            setActive: function(element){
                if(this.filtersApplied.indexOf(element) > -1) {
                    this.filtersApplied = this.filtersApplied.filter(tag => tag != element);
                } else {
                    this.filtersApplied.push(element)
                }
            },
            isActive: function (menuItem) {
                return this.filtersApplied.indexOf(menuItem) > -1
            },
            sendInfo(item) {
                this.orderColor = null;
                this.orderSize = null;
                if(item.color != null && item.color.length >= 1) {
                    this.orderColor = item.color[0]['name'];
                }
                if(item.size != null && item.size.length >= 1) {
                    this.orderSize = item.size[0]['name'];
                }
                this.selectedItem = item;

            },
            sendMessage(message, type='success') {
                this.$notify({
                    group: 'info',
                    type: type,
                    title: 'Products',
                    text: message
                });
            },
        },
    }
</script>

<style>
    .custom-select:focus-visible {
        color: transparent;
        text-shadow: none;
    }
    .custom-select:focus {
        border-color: #453074;
        outline: 0;
        box-shadow: none;
    }
    .orderby.custom-select {
        display: inline-block;
        width: 100%;
        height: calc(1.6em + 0.75rem + 2px);
        padding: 0.375rem 1.75rem 0.375rem 0.75rem;
        font-size: 0.9rem;
        font-weight: 400;
        line-height: 1.6;
        color: #495057;
        vertical-align: middle;
        background: #fff url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='4' height='5' viewBox='0 0 4 5'%3e%3cpath fill='%23ff4073' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e") no-repeat right 0.75rem center/8px 10px;
        border: 1px solid #453074;
        border-radius: 0.25rem;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }
    .orderby.selectize-dropdown {
        height:120px !important;
    }
    @media (min-width: 1792px) {
        .row-cols-xxl-3 > * {
            flex: 0 0 33.3333333333%;
            max-width: 33.3333333333%;
        }
    }
    .toggle_filters_menu {
        /*position: absolute;*/
        /*top: 50%;*/
        /*right: 50%;*/
        margin-right: -30px;
        margin-top: -30px;
        width: 60px;
        height: 60px;
        cursor: pointer;
        z-index: 1001;
        visibility: visible;
    }
    .toggle_mobile_menu {
        /*position: absolute;*/
        /*top: 50%;*/
        /*right: 50%;*/
        margin-left: -30px;
        margin-top: -30px;
        width: 60px;
        height: 60px;
        cursor: pointer;
        z-index: 1001;
        visibility: visible;
    }

    .price-box input{
        border-radius: 5px;
        border: 1px solid #453074;
        width: 70px;
        text-align: center;
    }
    .sort-by {
        width: 120px;
        border-radius: 5px;
        border: 1px solid #ff4073 ;
        /*position: absolute;
        top: -50px;right: 0px;*/
    }
    .vue-slider-dot-handle {
        background-color: #453074 !important;
    }
    .vue-slider-rail {
        background-color: #ff4073 !important;
    }
    .vue-slider-process {
        background-color: #453074 !important;
    }
    .vue-slider-dot-tooltip-inner {
        background-color: #453074 !important;
    }
    .vue-slider-dot-handle::after {
        background-color: rgba(151, 124, 204, 0.67);
    }
    .product_type_simple {
        position: relative;
        font-size: 14px;
        text-transform: uppercase;
        font-weight: 500;
        padding: 8px 0;
        letter-spacing: 0.2em;
        line-height: 1em;
        display: inline-block;
        vertical-align: top;
        text-align: center;
        color: #222222;
        background-color: transparent;
        background-image: -webkit-linear-gradient(#ff4073, #ff4073);
        background-image: linear-gradient(#ff4073, #ff4073);
        background-position: 0 20px;
        background-repeat: no-repeat;
        border: none;
        border-radius: 0;
        -webkit-transition: all 0.4s linear 0s;
        transition: all 0.4s linear 0s;
    }
    .product_type_simple:hover {
        color: #ffffff;
        -webkit-transition: all 0.05s linear 0s;
        transition: all 0.05s linear 0s;
    }
    .swatches,
    .swatch {
        display: block;
    }

    .swatches:after,
    .swatch:after {
        clear: both;
        content: "";
        display: block;
        visibility: hidden;
        height: 0;
    }
    .swatches .guide {
        float: left;
        margin: 36px 0 0 20px;
    }
    .swatches {
        margin: 17px 0 30px;
    }

    .selector-wrapper,
    #productSelect {
        display: none;
    }

    .swatch {
        float: left;
        margin-right: 40px;
    }

    .swatch:nth-last-child(2) {
        margin-right: 0;
    }

    .swatch .header {
        font-family: "montserratbold", sans-serif;
        text-transform: uppercase;
    }

    .swatch input {
        display: none;
    }

    .swatch .swatch-element {
        float: left;
        margin: 5px 8px 0 0;
        position: relative;
    }

    .swatch .color label {
        font-family: "montserratlight", sans-serif;
        font-size: 81.25%;
        line-height: 184.61538%;
        -moz-transition: all 0.3s ease-in-out;
        -o-transition: all 0.3s ease-in-out;
        -webkit-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
        -moz-border-radius: 50%;
        -webkit-border-radius: 50%;
        border-radius: 50%;
        border: 1px solid #453074;
        cursor: pointer;
        display: block;
        height: 42px;
        padding: 7px 0 0 7px;
        width: 42px;
    }

    .swatch .color label span {
        -moz-border-radius: 50%;
        -webkit-border-radius: 50%;
        border-radius: 50%;
        display: block;
        height: 26px;
        position: relative;
        width: 26px;
        border: 1px solid #cb0380;
    }

    .swatch .color label span:after {
        -moz-transition: all 0.3s ease-in-out;
        -o-transition: all 0.3s ease-in-out;
        -webkit-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
        background: url("http://iva-med.ru/images/check_color3.png") no-repeat center center;
        bottom: 0;
        content: "";
        display: block;
        height: 100%;
        left: 0;
        opacity: 0;
        position: absolute;
        top: 0;
        width: 100%;
    }

    .swatch .plain label {
        font-family: "montserratlight", sans-serif;
        font-size: 81.25%;
        line-height: 184.61538%;
        -moz-transition: all 0.3s ease-in-out;
        -o-transition: all 0.3s ease-in-out;
        -webkit-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
        -moz-border-radius: 50%;
        -webkit-border-radius: 50%;
        border-radius: 50%;
        font-family: "montserratbold", sans-serif;
        border: 1px solid #cb0380;
        color: #cb0380;
        cursor: pointer;
        display: block;
        height: 42px;
        padding-top: 8px;
        text-align: center;
        width: 42px;
    }

    .swatch .color input:checked + label span:after {
        opacity: 1;
    }

    .swatch input:not(:checked) + label {
        border-color: #453074 !important;
    }

    .swatch input:not(:checked) + label:hover {
        border-color: #cb0380 !important;
    }

    .swatch input:not(:checked) + label:hover span{
        border-color: #cb0380 !important;
    }

    .swatch .plain input:not(:checked) + label:hover{
        color: #cb0380 !important;
    }
    .swatch .plain input:not(:checked) + label {
        color: #453074 !important;
    }

    .swatch .blue input:checked + label {
        border-color: #cb0380 !important;
    }

    .swatch .white input:checked + label {
        border-color: #cb0380 !important;
    }

    .swatch .red input:checked + label {
        border-color: #d9332e !important;
    }

    .swatch .blue label span {
        border: 1px solid #453074;
        background-color: #30079d !important;
    }

    .swatch .blue input:checked + label span {
        border: 1px solid #cb0380;
        border-color: #cb0380 !important;
    }

    .swatch .white label span {
        border: 1px solid #453074;
        background-color: white !important;
    }
    .swatch .white input:checked + label span {
        border: 1px solid #cb0380;
        border-color: #cb0380 !important;
    }

    .swatch .red label span {
        background-color: #ff0700 !important;
    }

    .swatch .green input:checked + label {
        border-color: #cb0380 !important;
    }

    .swatch .green label span {
        border: 1px solid #453074;
        background-color: #06833d !important;
    }

    .swatch .green input:checked + label span {
        border: 1px solid #cb0380;
        border-color: #cb0380 !important;
    }

    .swatch .yellow input:checked + label {
        border-color: #cb0380 !important;
    }

    .swatch .yellow label span {
        border: 1px solid #453074;
        background-color: #f7ff00 !important;
    }

    .swatch .yellow input:checked + label span {
        border: 1px solid #cb0380;
        border-color: #cb0380 !important;
    }
    .swatch .orange input:checked + label {
        border-color: #cb0380 !important;
    }

    .swatch .orange label span {
        border: 1px solid #453074;
        background-color: #ff6c00 !important;
    }

    .swatch .orange input:checked + label span {
        border: 1px solid #cb0380;
        border-color: #cb0380 !important;
    }
    .swatch .black input:checked + label {
        border-color: #cb0380 !important;
    }

    .swatch .black label span {
        border: 1px solid #453074;
        background-color: black !important;
    }

    .swatch .black input:checked + label span {
        border: 1px solid #cb0380;
        border-color: #cb0380 !important;
    }

    .swatch .purple input:checked + label {
        border-color: #cb0380 !important;
    }

    .swatch .purple label span {
        border: 1px solid #453074;
        background-color: #8627bb !important;
    }

    .swatch .purple input:checked + label span {
        border: 1px solid #cb0380;
        border-color: #cb0380 !important;
    }

    .swatch .tooltip {
        -moz-border-radius: 2px;
        -webkit-border-radius: 2px;
        border-radius: 2px;
        text-align: center;
        background-color: rgba(22, 22, 26, 0.93);
        color: #fff;
        bottom: 100%;
        padding: 10px;
        display: block;
        position: absolute;
        width: 100px;
        left: -23px;
        margin-bottom: 15px;
        filter: alpha(opacity=0);
        -khtml-opacity: 0;
        -moz-opacity: 0;
        opacity: 0;
        visibility: hidden;
        -webkit-transform: translateY(10px);
        -moz-transform: translateY(10px);
        -ms-transform: translateY(10px);
        -o-transform: translateY(10px);
        transform: translateY(10px);
        -webkit-transition: all .25s ease-out;
        -moz-transition: all .25s ease-out;
        -ms-transition: all .25s ease-out;
        -o-transition: all .25s ease-out;
        transition: all .25s ease-out;
        -webkit-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.28);
        -moz-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.28);
        -ms-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.28);
        -o-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.28);
        box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.28);
        z-index: 1;
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }

    .swatch .tooltip:before {
        bottom: -20px;
        content: " ";
        display: block;
        height: 20px;
        left: 0;
        position: absolute;
        width: 100%;
    }

    .swatch .tooltip:after {
        border-left: solid transparent 10px;
        border-right: solid transparent 10px;
        border-top: solid rgba(22, 22, 26, 0.93) 10px;
        bottom: -10px;
        content: " ";
        height: 0;
        left: 50%;
        margin-left: -13px;
        position: absolute;
        width: 0;
    }

    .swatch .swatch-element:hover .tooltip {
        filter: alpha(opacity=100);
        -khtml-opacity: 1;
        -moz-opacity: 1;
        opacity: 1;
        visibility: visible;
        -webkit-transform: translateY(0px);
        -moz-transform: translateY(0px);
        -ms-transform: translateY(0px);
        -o-transform: translateY(0px);
        transform: translateY(0px);
    }

    .swatch.error {
        background-color: #E8D2D2 !important;
        color: #333 !important;
        padding: 1em;
        border-radius: 5px;
    }

    .swatch.error p {
        margin: 0.7em 0;
    }

    .swatch.error p:first-child {
        margin-top: 0;
    }

    .swatch.error p:last-child {
        margin-bottom: 0;
    }

    .swatch.error code {
        font-family: monospace;
    }

    .btn-radio {
        background: #453074;
        color: #fff;
    }

    .btn-radio:hover {
        background: #cb0380;
        color: #fff;
    }

    .swatch input:checked + .btn-radio {
        background: #cb0380;
    }
    .sold_out {
        display: none;
    }
    /* Shop */

    .swatch .plain .shop-size label:hover{
        color: #cb0380 !important;
        border: 1px solid #cb0380;
    }
    .swatch .plain .shop-size label {
        color: #453074 !important;
        border: 1px solid #453074;
    }
    .swatch .plain .selected label {
        color: #cb0380 !important;
        border: 1px solid #cb0380;
    }

    .swatch .color .shop-color label span {
        border: 1px solid  #453074;
        border-color: #453074 !important;
    }

    .swatch .color .shop-color label:hover{
        border: 1px solid #cb0380;
    }

    .swatch .color .shop-color label:hover span{
        border: 1px solid #cb0380;
        border-color: #cb0380 !important;
    }
    .swatch .color .selected label {
        border: 1px solid #cb0380;
    }
    .swatch .color .selected label span{
        border: 1px solid #cb0380;
        border-color: #cb0380 !important;
    }
    .swatch .color .selected label span:after {
        opacity: 1 !important;
    }
</style>