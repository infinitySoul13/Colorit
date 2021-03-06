<!-- Start chopcafe_menu section -->
<section class="chopcafe_menu chopcafe_menu_1 section_padding_2" id="menu_grid">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="chopcafe_title chopcafe_title_1 text-center">
                            <h2>НАШИ <span>ТОВАРЫ</span></h2>
                            <div class="title_divider">
                                <i class="flaticon-support title_fork"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <products></products>
{{--                <div class="row">--}}
{{--                    <div class="col-lg-12">--}}
{{--                        <div class="chopcafe_filter text-center">--}}
{{--                            @foreach($categories as $key=>$category)--}}
{{--                                <button class="chopcafe_btn mb-2 {{$key==$defaultCat?"active_btn":""}}" data-filter=".{{$category->trans}}">{{$category->title}}</button>--}}
{{--                            @endforeach--}}
{{--                            <button class="chopcafe_btn active_btn" data-filter=".breakfast">Масло моторное</button>--}}
{{--                            <button class="chopcafe_btn" data-filter=".lunch">Масло трансмиссионное</button>--}}
{{--                            <button class="chopcafe_btn" data-filter=".dinner">Жидкость гидроусилителя</button>--}}
{{--                            <button class="chopcafe_btn" data-filter=".snacks">АКБ</button>--}}
{{--                            <button class="chopcafe_btn" data-filter=".fastfood">Двориники</button>--}}
{{--                            <button class="chopcafe_btn" data-filter=".Salad">Выхлоп</button>--}}
{{--                            <button class="chopcafe_btn" data-filter=".kuzov">Кузов</button>--}}
{{--                            <button class="chopcafe_btn" data-filter=".oil">Масло</button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="row grid">--}}
{{--                    @foreach($products as $product)--}}
{{--                        <div class="col-lg-4 col-md-6 col-sm-12 menu_item {{$product->translit_category}}">--}}
{{--                            <div class="grid_item food_grid_box">--}}
{{--                                <div class="grid_inner_item">--}}
{{--                                    <div class="chopcafe_img main_menu_img">--}}
{{--                                        <img src="\assets\images\{{$product->img}}" class="img-fluid" style="max-height:245px;width:100%;height:100%;object-fit:contain;" alt="">--}}
{{--                                        <div class="overlay_img"></div>--}}
{{--                                        <div class="overlay_content custom_overlay_menu">--}}
{{--                                            <add-to-cart-btn :product_id="{{$product->id}}" :product_data="{{json_encode($product)}}"></add-to-cart-btn>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="chopcafe_info ">--}}
{{--                                        <div style="height: 75px;">--}}
{{--                                            <h3>{{$product->title}}</h3>--}}
{{--                                        </div>--}}
{{--                                        <hr>--}}
{{--                                        <p><em>{{$product->brand}}</em></p>--}}
{{--                                        <hr>--}}
{{--                                        <h3><strong>{{$product->price}}<i class="fas fa-ruble-sign"></i></strong>--}}{{-- <small>({{$product->mass}} гр.)</small>--}}{{--</h3>--}}
{{--                                    </div>--}}

{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    @endforeach--}}
{{--                    <div class="col-lg-4 col-md-6 col-sm-12 menu_item breakfast lunch snacks">--}}
{{--                        <div class="grid_item food_grid_box wow slideInUp">--}}
{{--                            <div class="grid_inner_item">--}}
{{--                                <div class="chopcafe_img">--}}
{{--                                    <img src="assets/images/menu_1.jpg" class="img-fluid" alt="">--}}
{{--                                    <div class="overlay_img"></div>--}}
{{--                                    <div class="overlay_content">--}}
{{--                                        <a href="assets/images/menu_1.jpg" class="btn_a btn_zoom"><i class="fas fa-search"></i></a>--}}
{{--                                        <a href="shop_details.html" class="btn_a btn_link"><i class="fas fa-link"></i></a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="chopcafe_info">--}}
{{--                                    <h3><a href="shop_details.html">Chicken Breast</a><span>$ 11.00</span></h3>--}}
{{--                                    <p>Seasoned chicken, parmesan, maple cured rasherai chicken red onion, mushroom.</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-lg-4 col-md-6 col-sm-12 menu_item breakfast fastfood dinner">--}}
{{--                        <div class="grid_item food_grid_box wow slideInUp">--}}
{{--                            <div class="grid_inner_item">--}}
{{--                                <div class="chopcafe_img">--}}
{{--                                    <img src="assets/images/menu_2.jpg" class="img-fluid" alt="">--}}
{{--                                    <div class="overlay_img"></div>--}}
{{--                                    <div class="overlay_content">--}}
{{--                                        <a href="assets/images/menu_1.jpg" class="btn_a btn_zoom"><i class="fas fa-search"></i></a>--}}
{{--                                        <a href="shop_details.html" class="btn_a btn_link"><i class="fas fa-link"></i></a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="chopcafe_info">--}}
{{--                                    <h3><a href="shop_details.html">Fresh Grilled Asparagus</a><span>$ 25.00</span></h3>--}}
{{--                                    <p>Seasoned chicken, parmesan, maple cured rasherai chicken red onion, mushroom.</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-lg-4 col-md-6 col-sm-12 menu_item breakfast Salad lunch">--}}
{{--                        <div class="grid_item food_grid_box wow slideInUp">--}}
{{--                            <div class="grid_inner_item">--}}
{{--                                <div class="chopcafe_img">--}}
{{--                                    <img src="assets/images/menu_3.jpg" class="img-fluid" alt="">--}}
{{--                                    <div class="overlay_img"></div>--}}
{{--                                    <div class="overlay_content">--}}
{{--                                        <a href="assets/images/menu_1.jpg" class="btn_a btn_zoom"><i class="fas fa-search"></i></a>--}}
{{--                                        <a href="shop_details.html" class="btn_a btn_link"><i class="fas fa-link"></i></a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="chopcafe_info">--}}
{{--                                    <h3><a href="shop_details.html">Sashimi Steak</a><span>$35.00</span></h3>--}}
{{--                                    <p>Seasoned chicken, parmesan, maple cured rasherai chicken red onion, mushroom.</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-lg-4 col-md-6 col-sm-12 menu_item breakfast snacks dinner fastfood">--}}
{{--                        <div class="grid_item food_grid_box wow slideInUp">--}}
{{--                            <div class="grid_inner_item">--}}
{{--                                <div class="chopcafe_img">--}}
{{--                                    <img src="assets/images/menu_4.jpg" class="img-fluid" alt="">--}}
{{--                                    <div class="overlay_img"></div>--}}
{{--                                    <div class="overlay_content">--}}
{{--                                        <a href="assets/images/menu_1.jpg" class="btn_a btn_zoom"><i class="fas fa-search"></i></a>--}}
{{--                                        <a href="shop_details.html" class="btn_a btn_link"><i class="fas fa-link"></i></a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="chopcafe_info">--}}
{{--                                    <h3><a href="shop_details.html">Fresh Fruits Salads</a><span>$ 11.00</span></h3>--}}
{{--                                    <p>Seasoned chicken, parmesan, maple cured rasherai chicken red onion, mushroom.</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-lg-4 col-md-6 col-sm-12 menu_item breakfast Salad fastfood lunch">--}}
{{--                        <div class="grid_item food_grid_box wow slideInUp">--}}
{{--                            <div class="grid_inner_item">--}}
{{--                                <div class="chopcafe_img">--}}
{{--                                    <img src="assets/images/menu_5.jpg" class="img-fluid" alt="">--}}
{{--                                    <div class="overlay_img"></div>--}}
{{--                                    <div class="overlay_content">--}}
{{--                                        <a href="assets/images/menu_1.jpg" class="btn_a btn_zoom"><i class="fas fa-search"></i></a>--}}
{{--                                        <a href="shop_details.html" class="btn_a btn_link"><i class="fas fa-link"></i></a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="chopcafe_info">--}}
{{--                                    <h3><a href="shop_details.html">Sweet Desserts</a><span>$ 26.00</span></h3>--}}
{{--                                    <p>Seasoned chicken, parmesan, maple cured rasherai chicken red onion, mushroom.</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-lg-4 col-md-6 col-sm-12 menu_item breakfast snacks Salad  dinner">--}}
{{--                        <div class="grid_item food_grid_box wow slideInUp">--}}
{{--                            <div class="grid_inner_item">--}}
{{--                                <div class="chopcafe_img">--}}
{{--                                    <img src="assets/images/menu_6.jpg" class="img-fluid" alt="">--}}
{{--                                    <div class="overlay_img"></div>--}}
{{--                                    <div class="overlay_content">--}}
{{--                                        <a href="assets/images/menu_1.jpg" class="btn_a btn_zoom"><i class="fas fa-search"></i></a>--}}
{{--                                        <a href="shop_details.html" class="btn_a btn_link"><i class="fas fa-link"></i></a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="chopcafe_info">--}}
{{--                                    <h3><a href="shop_details.html">Salad & Egg</a><span>$ 18.00</span></h3>--}}
{{--                                    <p>Seasoned chicken, parmesan, maple cured rasherai chicken red onion, mushroom.</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
        </section>
<!-- End chopcafe_menu section -->
