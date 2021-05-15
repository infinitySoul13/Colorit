<template>
    <div>
        <div id="box_wrapper" class="">
            <!-- template sections -->

<!--            <div class="transparent_wrapper">-->
<!--                <header class="page_header header_v2 header_white toggler_xxs_right">-->
<!--                    <div class="container-fluid">-->
<!--                        <div class="row">-->
<!--                            <div class="col-sm-12 display-flex v-center">-->
<!--                                <div class="header_left_logo">-->
<!--                                    <a href="/" rel="home" class="logo logo_image_only">-->
<!--                                        <img src="/img/2colorit.png" alt="">-->
<!--                                        <img src="/img/colorit.png" alt="">-->
<!--                                    </a>-->
<!--                                </div>-->

<!--                                <div class="header_mainmenu text-center">-->
<!--                                    <nav class="mainmenu_wrapper primary-navigation">-->
<!--                                        <ul id="menu-main-menu" class="sf-menu nav-menu nav d-block">-->
<!--                                            <li id="menu-item-18" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home menu-item-18">-->
<!--                                                <a href="/">-->
<!--                                                    <span>Home</span>-->
<!--                                                </a>-->
<!--                                            </li>-->
<!--                                            <li id="menu-item-128" class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item current_page_item menu-item-1248">-->
<!--                                                <a href="/cart">-->
<!--                                                    <span>Cart</span>-->
<!--                                                </a>-->
<!--                                            </li>-->
<!--                                            <li id="menu-item-1248" class="menu-item menu-item-type-post_type menu-item-object-page  menu-item-1248">-->
<!--                                                <a href="/shop">-->
<!--                                                    <span>Shop</span>-->
<!--                                                </a>-->
<!--                                            </li>-->
<!--                                        </ul>-->
<!--                                    </nav>-->
<!--                                    &lt;!&ndash; header toggler &ndash;&gt;-->
<!--                                    <span class="toggle_menu"><span></span></span>-->
<!--                                </div>-->

<!--                                <div class="header_right_buttons text-right hidden-xxs">-->
<!--                                    <span class="social-icons">-->
<!--                                        <a href="#" class="social-icon socicon-facebook color-bg-icon rounded-icon" target="_blank"></a>-->
<!--                                        <a href="#" class="social-icon socicon-twitter color-bg-icon rounded-icon" target="_blank"></a>-->
<!--                                        <a href="#" class="social-icon socicon-googleplus color-bg-icon rounded-icon" target="_blank"></a>-->
<!--        		                    </span>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </header>-->
<!--            </div>-->
            <page-header></page-header>
            <section class="page_breadcrumbs cs main_color6 section_padding_25 background_cover" style="background-image: url(http://webdesign-finder.com/colorlab/wp-content/uploads/2018/07/breadcrumbs4.jpg)">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 text-center darklinks">

                            <h1>Cart</h1>

                            <ol class="breadcrumb d-block">
                                <li class="first-item">
                                    <a href="http://webdesign-finder.com/colorlab/">Homepage</a></li>
                                <li class="last-item"><span>Cart</span></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
            <div class="row w-100 m-auto align-items-center justify-content-center text-center" v-if="cartProducts.length == 0">
                <h3 class="font-italic">Cart is empty</h3>
            </div>
            <div class="row w-100 m-auto align-items-center justify-content-center py-5 px-4 cart-panel" v-else>
                <table  class="table no-more-tables">
                    <thead>
                    <tr>
                        <th scope="col" width="40%" class="border-0 bg-light">
                            <div class="p-2 px-3 text-uppercase">Product</div>
                        </th>
                        <th scope="col" width="10%" class="border-0 bg-light">
                            <div class="py-2 text-uppercase">Price</div>
                        </th>
                        <th scope="col" class="border-0 bg-light">
                            <div class="py-2 text-uppercase">Quantity</div>
                        </th>
                        <th scope="col" class="border-0 bg-light">
                            <div class="py-2 text-uppercase">Delete</div>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for ="(item, key) in cartProducts">
                        <td scope="row" data-title="Product">
                            <div class="p-2">
                                <img
                                        v-if="item.product.src != null && item.product.src && item.product.src[0]"
                                        :src="item.product.src[0].path"
                                        alt=""
                                        width="70" height="70"
                                        class="img-fluid rounded shadow-sm"
                                        style="max-height: 70px; min-height: 70px; object-fit: fill;"
                                >
                                <div class="d-inline-block align-middle">
                                    <h5 class="mb-0">
                                        <a href="#" class="text-dark d-inline-block">{{item.product.title}}</a>
                                    </h5>
                                    <p>
                                    <span v-if="item.size != null">
                                        Size: {{item.size}} ;
                                        <!--                                            {{product.attributes['size']}} ;-->
                                    </span>
                                        <span v-if="item.color != null">
                                        Color: {{item.color}} ;
                                            <!--                                            {{product.attributes['color']}} ;-->
                                    </span>
                                        <!--                                        <span v-if="product.attributes['style'] !=null">-->
                                        <!--                                            Фасон: {{product.attributes['style']}} ;-->
                                        <!--                                        </span>-->
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-center" data-title="Price">
                             <div class="row w-100 m-auto price" v-if="item.product.type=='sale'">
                                 <div class="col-12">
                                     <del class="mr-0">
                                        <span class="woocommerce-Price-amount amount">
                                            {{item.product.price  | currency}}
                                        </span>
                                     </del>
                                 </div>
                                 <div class="col-12">
                                     <ins>
                                    <span class="woocommerce-Price-amount amount">
                                        {{item.product.discount_price | currency}}
                                    </span>
                                     </ins>
                                 </div>
                            </div>
                            <div class="row w-100 m-auto justify-content-center align-items-center price" v-else>
                                <span class="woocommerce-Price-amount amount">
                                    {{item.product.price | currency}}
                                </span>
                            </div>
                        </td>
                        <td class="align-middle" data-title="Quantity">
                            <div class="row w-100 m-auto justify-content-center align-items-center">
                                <div class="col-12 col-sm-2 px-1">
                                    <button type="button" class="btn w-100 p-2 fa fa-minus" style="background:#453074; color:#fff;" :disabled="item.quantity===1"
                                            @click="decrement(item)">
                                    </button>
                                </div>
                                <div class="col-12 col-sm-8 px-1">
                                    <input type="number" :value="item.quantity" step="1" min="0"
                                           class="form-control"/>
                                </div>
                                <div class="col-12 col-sm-2 px-1">
                                    <button type="button" class="btn w-100  p-2 fa fa-plus" style="background:#453074; color:#fff;"
                                            @click="increment(item)">
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle" data-title="Delete">
                            <div class="row align-items-center justify-content-center">
                                <button class="btn" style="background: #453074;color:white" v-on:click="remove(item)">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="row w-100 m-auto">
                <div class="text-left ml-auto mr-auto mb-2">
                    <a href="/shop" class="btn btn-dark" style="background: #ff4073; text-transform: uppercase">Continue shopping</a>
                </div>
                <div class="update-checkout text-right ml-auto mr-auto" v-if="cartProducts.length != 0">
                    <button v-on:click="clearCart()" class="btn btn-dark" style="background: #ff4073">Clear cart</button>
                </div>
            </div>

            <ValidationObserver v-slot="{ invalid }">
                <div class="row w-100 mx-auto py-5 px-4 mb-5 cart-panel">
                    <div class="col-lg-6">
                        <div class="bg-light rounded-pill px-4 py-3 text-uppercase font-weight-bold">Billing address</div>
                        <form class="needs-validation pb-4" validate>
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <ValidationProvider name="Name" rules="required" v-slot="{ errors }">
                                    <input type="text" class="form-control" id="name" v-model="name" placeholder="" value="" required>
                                    <span class="validate-error">{{ errors[0] }}</span>
                                </ValidationProvider>
                            </div>

                            <div class="mb-3">
                                <label for="telephone">Phone</label>
                                <ValidationProvider name="Phone" rules="required|min:16" v-slot="{ errors }">
                                    <input type="telephone" class="form-control" id="telephone" v-model="phone" placeholder="" v-mask="'+#(###)###-##-##'" required>
                                    <span class="validate-error">{{ errors[0] }}</span>
                                </ValidationProvider>
                            </div>
                            <div class="mb-3">
                                <label>Preferred messengers</label>

                                <select class="form-control" name="messenger" v-model="messenger">
                                    <option selected>Telegram</option>
                                    <option>WhatsApp</option>
                                    <option>Viber</option>
                                    <option>Phone call only</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="address">Address</label>
                                <ValidationProvider name="Address" rules="required" v-slot="{ errors }">
                                    <input type="text" class="form-control" id="address" v-model="address" placeholder="" required>
                                    <span class="validate-error">{{ errors[0] }}</span>
                                </ValidationProvider>
                            </div>

                        </form>
                    </div>
<!--                    <div class="col-lg-6">-->
<!--                        <div class="bg-light rounded-pill px-4 py-3 text-uppercase font-weight-bold">Message to the seller</div>-->
<!--                        <div class="p-sm-4">-->
<!--                            <p class="font-italic mb-4">If you have information for the seller, you can leave it in the field below</p>-->
<!--                            <textarea name="message" cols="30" rows="10" class="form-control" v-model="message"></textarea>-->
<!--                        </div>-->
<!--                    </div>-->
                    <div class="col-lg-6">
                        <div class="bg-light rounded-pill px-4 py-3 text-uppercase font-weight-bold">Order summary</div>
                        <div class="p-4">
                            <p class="font-italic mb-4">Delivery and additional costs are calculated based on the values that you entered, and are discussed further with the manager.</p>
                            <ul class="list-unstyled mb-4">
                                <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Quantity</strong><strong>{{cartTotalCount}}</strong></li>
                                <!--                            <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Общая сумма заказа</strong><strong>{{details.subTotal}} руб</strong></li>-->
                                <!--                            <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Скидка {{details.value}}</strong><strong>{{details.conditionCalculatedValue}} руб</strong></li>-->
                                <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Total</strong>
                                    <h5 class="font-weight-bold">{{cartTotalPrice | currency}}</h5>
                                </li>
                            </ul>
                            <button class="btn btn-block" style="background: #453074;color:white" v-on:click="sendRequest" :disabled="invalid||cartProducts.length == 0">Send order</button>
                        </div>
                    </div>
                </div>

<!--                <div class="row w-100 mx-auto py-5 px-4 mb-5 cart-panel">-->
    <!--                <div class="col-lg-6">-->
    <!--                    <div class="bg-light rounded-pill px-4 py-3 text-uppercase font-weight-bold">Код скидки</div>-->
    <!--                    <div class="p-4">-->
    <!--                        <p class="font-italic mb-4">Если у Вас есть код скидки, введите его в поле ниже</p>-->
    <!--                        <p class="font-italic mb-4">{{error}}</p>-->
    <!--                        <div class="input-group mb-4 border rounded-pill">-->
    <!--                            <input type="text" placeholder="Применить скидку" aria-describedby="button-addon3" v-model="code" class="form-control border-0">-->
    <!--                            <div class="input-group-append border-0">-->
    <!--                                <button id="button-addon3" type="button" class="btn btn-dark px-4 rounded-pill" v-on:click="addCondition()">-->
    <!--                                    Применить</button>-->
    <!--                            </div>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                    <div class="bg-light rounded-pill px-4 py-3 text-uppercase font-weight-bold">Заполнить анкету</div>-->
    <!--                    <div class="p-4">-->
    <!--                        <p class="font-italic mb-4">Заполните анкету и получите код скидки</p>-->
    <!--                        <a data-toggle="modal" data-target="#questionnaireModalCenter" class="btn btn-dark rounded-pill px-4 btn-block" style="color: #fff">Получить скидку</a>-->
    <!--                    </div>-->
    <!--                </div>-->
<!--                    <div class="col-lg-6">-->
<!--                        <div class="bg-light rounded-pill px-4 py-3 text-uppercase font-weight-bold">Итог заказа</div>-->
<!--                        <div class="p-4">-->
<!--                            <p class="font-italic mb-4">Delivery and additional costs are calculated based on the values that you entered, and are discussed further with the manager.</p>-->
<!--                            <ul class="list-unstyled mb-4">-->
<!--                                <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Quantity</strong><strong>{{cartTotalCount}}</strong></li>-->
<!--    &lt;!&ndash;                            <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Общая сумма заказа</strong><strong>{{details.subTotal}} руб</strong></li>&ndash;&gt;-->
<!--    &lt;!&ndash;                            <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Скидка {{details.value}}</strong><strong>{{details.conditionCalculatedValue}} руб</strong></li>&ndash;&gt;-->
<!--                                <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Total</strong>-->
<!--                                    <h5 class="font-weight-bold">{{cartTotalPrice | currency}}</h5>-->
<!--                                </li>-->
<!--                            </ul>-->
<!--                            <button class="btn btn-dark rounded-pill py-2 btn-block" v-on:click="sendRequest" :disabled="invalid||cartProducts.length == 0">Send order</button>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
            </ValidationObserver>
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
    </div>
</template>

<script>
    export default {
        name: "CartPage",
        props: ["products"],
        data: () => ({
            list: [],
            details: [],
            code: '',
            error: '',
            message: '',
            phone: '',
            address: '',
            name: '',
            messenger: 'Telegram'

        }),
        mounted() {
            this.$store.dispatch("getProductList")
        },
        activated() {
            this.$store.dispatch("getProductList")
        },
        computed: {
            cartProducts: function () {
                return this.$store.getters.cartProducts;
            },
            cartTotalCount: function () {
                return this.$store.getters.cartTotalCount;
            },
            cartTotalPrice: function () {
                return this.$store.getters.cartTotalPrice;
            }
        },

        methods: {
            initialize () {
                this.list=Object.values(this.products);
            },
            sendRequest(e) {
                e.preventDefault();

                this.sending = true;
                let products = '';

                this.cartProducts.forEach(function (item) {
                    let attr = '';
                    if(item.size != null && item.size !== '')
                    {
                        attr += item.size+'; ';
                    }
                    if(item.color != null && item.color !== '')
                    {
                        attr += item.color+'; ';
                    }
                    if(attr === '')
                    {
                        attr = 'None'
                    }
                    products += "<b>ID"+item.product.id+"</b>-"+item.product.title +"( "+attr+" )" +" x "+ item.quantity + " items => " + item.quantity * item.product.price + "₽\n"
                });

                let message = `<b>Messenger:</b> ${this.messenger}\n<b>Order:</b>\n${products}\n${this.message}\n<b>Total:${this.cartTotalPrice} ₽</b> `;
                axios
                    .post('api/send-request', {
                        name: this.name,
                        phone: this.phone,
                        message: message
                    })
                    .then(response => {
                        this.sendMessage("Order sent successfully");
                        this.sending = false;
                        this.clearCart()
                    });
            },
            sendMessage(message) {
                this.$notify({
                    group: 'info',
                    type: 'success',
                    title: 'Sending an order',
                    text: message
                });
            },
            increment(item) {
                this.$store.dispatch("incQuantity", item)
            },
            decrement(item) {
                this.$store.dispatch("decQuantity", item)
            },
            remove(item) {
                this.$store.dispatch("removeCartProduct", item)
            },
            clearCart() {
                this.$store.dispatch("clearCart")
            }
        },
    }
</script>

<style scoped>
    .validate-error{
        font-size: 15px;
        color: red;
        font-style: italic;
    }
    .cart_quantity {
        display: inline-block;
        float: left;
        position: relative;
        z-index: 1;
    }
    .coupon-code-area form {
        position: relative;
        z-index: 1;
    }
    .coupon-code-area form > input {
        width: 100%;
        height: 52px;
        border: none;
        background-color: #f4f2f8;
        padding: 0 30px;
        font-size: 12px;
    }

    .coupon-code-area form > button {

        height: 52px;
        border: none;
        text-transform: uppercase;
        padding: 0 30px;
        font-size: 14px;
        position: absolute;
        top: 0;
        color: #fff;
        font-weight: 700;
        right: 0;
    }


    .update-checkout a,
    .back-to-shop a {
        background-color: transparent;
        border-radius: 0;
        display: inline-block;
        height: 55px;
        line-height: 51px;
        min-width: 120px;
        padding: 0 30px;
        text-align: center;
        font-size: 14px;
        font-weight: 700;
        border: 2px solid #3a3a3a;
        text-transform: uppercase;
    }

    .update-checkout a:first-child {
        color: #7a7a7a;
        border-color: #f4f2f8;
    }

    .update-checkout a:last-child {
        background-color: #f4f2f8;
        color: #7a7a7a;
        border-color: #f4f2f8;
    }
    .deleteButton{
        background: rgb(151, 124, 204) none repeat scroll 0% 0%;
        height: 50px;
        width: 50px;
        display: block;
        color: #fff;
        float: left;
        border-radius: 50%;
        position: relative;
        padding: 0px;
    }
    .deleteButton span{
        position: absolute;
        font-size: 30px;
        top: 50%;
        left: 50%;
        -webkit-transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }
    @media (max-width: 768px) {
        .cart-panel {
            padding: 1rem !important;
        }

    }
    @media (max-width: 800px) {
        .quantity {
            margin-left: 20px;
            width: 90%;
        }
    }
    @media only screen and (max-width: 1000px) {

        /* Force table to not be like tables anymore */
        .no-more-tables table,
        .no-more-tables thead,
        .no-more-tables tbody,
        .no-more-tables th,
        .no-more-tables td,
        .no-more-tables tr {
            display: block;
        }

        /* Hide table headers (but not display: none;, for accessibility) */
        .no-more-tables thead tr {
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        .no-more-tables tr { border: 1px solid #ccc; }

        .no-more-tables td {
            /* Behave  like a "row" */
            border: none;
            border-bottom: 1px solid #eee;
            position: relative;
            padding-left: 35% !important;
            white-space: normal;
            text-align:left;
        }

        .no-more-tables td:before {
            /* Now like a table header */
            position: absolute;
            /* Top/left values mimic padding */
            /*top: 6px;*/
            left: 6px;
            width: 45%;
            padding-right: 10px;
            white-space: nowrap;
            text-align:left;
            font-weight: bold;
        }

        /*
        Label the data
        */
        .no-more-tables td:before { content: attr(data-title); }
    }
</style>