<template>
    <div class="cart">

        <div class="container" v-if="cartProducts.length>0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="chopcafe_product_table">
                        <table class="chopcafe_table chopcafe_custom_table table">
                            <thead>
                            <tr>
                                <th>Изображение</th>
                                <th>Название продукта</th>
                                <th>Цена</th>
                                <th>Количество</th>
                                <th>Всего</th>
                                <th>Удалить</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="item in cartProducts">
                                <td class="thumbnail">
                                    <img :src="'/assets/images/'+item.product.img" class="img-fluid" style="max-height: 50px; max-width: 50px;" alt="">
                                </td>
                                <td class="product_title">{{item.product.title}}
                                </td>
                                <td class="product_price"><span>{{item.product.price | currency}}</span></td>
                                <td class="product_quantity">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-2 col-lg-2">
                                            <button type="button" class="btn" style="background:#dd1427; color:#fff;" :disabled="item.quantity===1"
                                                    @click="decrement(item.product.id)">-
                                            </button>

                                        </div>

                                        <div class="col-sm-4 col-lg-4">
                                            <input type="number" :value="item.quantity" step="1" min="0"
                                                   class="form-control">

                                        </div>
                                        <div class="col-sm-2 col-lg-2">
                                            <button type="button" class="btn" style="background:#dd1427; color:#fff;"
                                                    @click="increment(item.product.id)">+
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td class="total">
                                    <span>{{item.product.price*item.quantity | currency }}</span>
                                </td>
                                <td class="product_delete">
                                    <a href="#" @click="remove(item.product.id)"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6">
                    <div class="billing_order_confirm">
                        <table class="table chopcafe_table">
                            <thead>
                            <tr>
                                <th class="cart_title" colspan="2">Итого</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="subtotal"></td>
                                <td class="subtotal_price"><span>Всего</span></td>
                            </tr>
                            <tr>
                                <td class="product"><span>Количество</span></td>
                                <td class="price"><span>{{cartTotalCount}}</span></td>
                            </tr>
                            <tr>
                                <td class="total_price"><span>Цена заказа</span></td>
                                <td class="price"><span>{{cartTotalPrice | currency}}</span></td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="row mt-2">
                            <div class="col-lg-9 col-sm-12">
                                <div class="update_cart">
                                    <a href="#" class="chopcafe_btn clear_cart_btn" @click="clearCart"><i
                                            class="fas fa-times-circle"></i>Очистить
                                        корзину</a>
                                </div>
                            </div>
                    </div>

                    </div>
                </div>
                <div class="col-md-6">
                    <form @submit="sendRequest">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form_group">
                                    <input type="text" placeholder="Ваше имя" name="name"
                                                               v-model="name"
                                                               required="required"
                                                               class="form_control">
                                </div>
                            </div>
                            <div class="col-lg-12 mt-2">
                                <div class="form_group">
                                    <input type="text" placeholder="Ваш номер телефона" name="phone"
                                                               v-model="phone"
                                                               required="required"  class="form_control"
                                                               v-mask="'+38 (###) ###-##-##'">
                                </div>
                            </div>

                            <div class="col-lg-12 mt-2">
                                <div class="form_group">
                                    <textarea style="height: 122px;" placeholder="Сообщение для нас"
                                                                  name="message"
                                                                  v-model="message"
                                                                  class="form_control"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-2">
                                <div class="continue_shopping">
                                    <button class="chopcafe_btn continue_btn" :disabled="sending"><i
                                            class="fas fa-shopping-cart"></i>Оформить
                                        покупку</button>
                                </div>
                            </div>


                        </div>
                    </form>

                </div>
            </div>
        </div>
        <div v-if="cartProducts.length===0">
            <div class="row justify-content-center">
                <img :src="'../assets/images/empty_cart.png'" alt="">
            </div>
            <h1 class="text-center">Корзина пуста:(</h1>
        </div>

    </div>
</template>
<script>
    export default {
        data() {
            return {
                phone: '',
                name: '',
                message: '',
                deliveryPrice: 50,
                sending:false
            }
        },
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
            sendRequest(e) {
                e.preventDefault();

                this.sending = true;
                let products = '';

                this.cartProducts.forEach(function (item) {
                    products += item.product.title +"_#"+item.product.id+ "_ x  "+ item.quantity + "штук => " + item.quantity * item.product.price + "₽\n"
                });

                let message = `*Заказ с сайта:*\n${products}\n_${this.message}_\nСуммарно: ${this.cartTotalPrice + this.deliveryPrice} ₽`;
                axios
                    .post('api/send-request', {
                        name: this.name,
                        phone: this.phone,
                        message: message
                    })
                    .then(response => {
                        this.sendMessage("Заказ успешно отправлен");
                        this.sending = false;
                        this.clearCart()
                    });
            },
            sendMessage(message) {
                this.$notify({
                    group: 'messages',
                    type: 'success',
                    title: 'Отправка заказа АВТОДОН',
                    text: message
                });
            },
            increment(id) {
                this.$store.dispatch("incQuantity", id)
            },
            decrement(id) {
                this.$store.dispatch("decQuantity", id)
            },
            remove(id) {
                this.$store.dispatch("removeCartProduct", id)
            },
            clearCart() {
                this.$store.dispatch("clearCart")
            }
        },

    }
</script>
<style>
    .chopcafe_custom_table {
        min-width: 1000px;
    }
</style>
