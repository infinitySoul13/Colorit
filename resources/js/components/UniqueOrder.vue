<template>
    <div>
        <div class="modal fade" id="orderModal">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><span>Мы вам перезвоним</span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="reservation_form">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form_group">
                                                <input type="text" placeholder="Ваше имя" name="name" v-model="name"
                                                       required="required"
                                                       class="form_control">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form_group">
                                                <input type="text" placeholder="Ваш номер телефона" name="phone"
                                                   v-model="phone"
                                                   required="required" class="form_control phone" v-mask="'+38 (###) ###-##-##'">
                                                <i class="fas fa-phone"></i>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form_button text-center">
                                                <button class="chopcafe_btn form_btn" data-dismiss="modal" v-on:click="sendUniqueOrder()">Оформить заказ</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="orderapp">
            <div class="row justify-content-center align-items-center header ml-auto mr-auto" style="width: 100%">
                <div class="col-md-3">
                    <input
                            class="new-order hover-border no-hover-border"
                            autocomplete="off"
                            placeholder="Введите наименование"
                            v-model="title"
                            @keyup.enter="addOrder"
                    />
                </div>
                <div class="col-md-3">
                <input
                        class="new-order hover-border no-hover-border"
                        autocomplete="off"
                        placeholder="Введите артикул"
                        v-model="code"
                        @keyup.enter="addOrder"
                />
                </div>
                <div class="col-md-2">
        <!--                <input-->
        <!--                        class="new-order"-->
        <!--                        autofocus-->
        <!--                        autocomplete="off"-->
        <!--                        placeholder="Кол-во"-->
        <!--                        v-model="quantity"-->
        <!--                        @keyup.enter="addOrder"-->
        <!--                />-->
                    <div class="row align-items-center ml-auto mr-auto" style="height: 100%; width: 100%">

                            <div class="col-3 p-0 text-center ml-auto mr-auto">
                                <button type="button" class="btn" style="background:#dd1427; color:#fff;" :disabled="quantity===1" @click="decrement()">-
                                </button>
                            </div>
                            <div class="col-6 col-sm-5 col-lg-6 p-0 text-center">
                                <input type="number" step="1" min="0" class="p-2 new-order form-control" v-model="quantity">
                            </div>
                            <div class="col-3 p-0 text-center ml-auto mr-auto">
                                <button type="button" class="btn" style="background:#dd1427; color:#fff;" @click="increment()">+
                                </button>
                            </div>


                    </div>
                </div>
                <div class="col-md-3">

                    <input
                            class="new-order hover-border no-hover-border"
                            autocomplete="off"
                            placeholder="Примечание/Бренд"
                            v-model="note"
                            @keyup.enter="addOrder"
                    />
                </div>
                <div class="col-md-1 text-center">
                        <button class="btn" style="background:#dd1427; color:#fff;" @click="addOrder()"><i class="fas fa-arrow-alt-circle-right"></i></button>
                </div>
<!--                <div class="col-md-12">-->
<!--                    <div class="row">-->
<!--                        <button class="btn" style="background:#dd1427; color:#fff;" @click="addOrder()">Добавить</button>-->
<!--                    </div>-->

<!--                </div>-->
            </div>
            <section class="main" v-show="orders.length" v-cloak>
                <!--            <input-->
                <!--                    id="toggle-all"-->
                <!--                    class="toggle-all"-->
                <!--                    type="checkbox"-->
                <!--                    v-model="allDone"-->
                <!--            />-->
                <!--            <label for="toggle-all"></label>-->
                <ul class="order-list">
                    <li
                        v-for="order in orders"
                        class="order"
                        :key="order.id"
                        :class="{ editing: order == editedOrder }"
                    >
                        <div class="view row justify-content-center align-items-center ml-auto mr-auto" style="width: 100%">
    <!--                        <input class="toggle" type="checkbox" v-model="order.completed" />-->
                            <div class="col-md-3">
                            <label @click="editOrder(order, 'title')">{{ order.title }}</label>
                            </div>
                            <div class="col-md-3">
                            <label @click="editOrder(order, 'code')">{{ order.code }}</label>
                            </div>
                            <div class="col-md-2">
                                <label @click="editOrder(order, 'quantity')">{{ order.quantity }}</label>
        <!--                            <label>{{ order.quantity }}</label>-->
                            </div>
                            <div class="col-md-3">
                                <label @click="editOrder(order, 'note')">{{ order.note }}</label>
                            </div>
                            <div class="col-md-1">
                                <button class="btn destroy" @click="removeOrder(order)"></button>
                            </div>
                        </div>
                        <div class="row align-items-center ml-auto mr-auto" style="width: 100%">
                            <div class="col-md-3">
                                <input
                                        v-if="editedField == 'title'"
                                        class="edit"
                                        :class="{ editing: editedField == 'title', 'new-order': editedField != 'title' && order == editedOrder  }"
                                        type="text"
                                        v-model="order.title"
                                        @blur="doneEdit(order)"
                                        @keyup.enter="doneEdit(order)"
                                />
                                <label v-if="editedField != 'title' && order == editedOrder" @dblclick="editOrder(order, 'title')">{{ order.title }}</label>

                            </div>
                            <div class="col-md-3">
                                <input
                                        v-if="editedField == 'code'"
                                        class="edit"
                                        :class="{ editing: editedField == 'code', 'new-order': editedField != 'code' && order == editedOrder }"
                                        type="text"
                                        v-model="order.code"
                                        @blur="doneEdit(order)"
                                        @keyup.enter="doneEdit(order)"
                                />
                                <label v-if="editedField != 'code' && order == editedOrder" @dblclick="editOrder(order, 'code')">{{ order.code }}</label>

                            </div>
                            <div class="col-md-2">
    <!--                    <input-->
    <!--                            class="new-order" :class="{ edit: editedField == 'quantity' }"-->
    <!--                            type="text"-->
    <!--                            v-model="order.quantity"-->

    <!--                            @blur="doneEdit(order)"-->
    <!--                            @keyup.enter="doneEdit(order)"-->
    <!--                            @keyup.esc="cancelEdit(order)"-->
    <!--                    />-->
                                <div class="row justify-content-center align-items-center ml-auto mr-auto" style="height: 100%; width: 100%">
                                    <div class="col-3 p-0 text-center  ml-auto mr-auto">
                                        <button type="button" class="btn"
                                                :class="{ 'is-invisible': editedField != 'quantity' || order != editedOrder }"
                                                style="background:#dd1427; color:#fff;" :disabled="editedOrder.quantity===1"
                                                @click="decrementQuantity(order)">-
                                        </button>
                                    </div>
                                    <div class="col-6 col-sm-5 col-lg-6 p-0 text-center">
                                        <input v-if="editedField == 'quantity'" type="number" step="1" min="0"
                                               class="form-control p-2"
                                               :class="{ 'is-invisible': editedField != 'quantity' || order != editedOrder, 'new-order': editedField != 'quantity' && order == editedOrder }"
                                               v-model="order.quantity"
                                               @blur="doneEdit(order)"
                                               @keyup.enter="doneEdit(order)"
                                        />
                                        <label v-if="editedField != 'quantity' && order == editedOrder" @dblclick="editOrder(order, 'quantity')">{{ order.quantity }}</label>
<!--                                        :type="{text: editedField != 'quantity' && order == editedOrder, number:editedField == 'quantity'}"-->
                                    </div>
                                    <div class="col-3 p-0 text-center ml-auto mr-auto">
                                        <button type="button" class="btn"
                                                :class="{ 'is-invisible': editedField != 'quantity' || order != editedOrder }"
                                                style="background:#dd1427; color:#fff;"
                                                @click="incrementQuantity(order)">+
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <input
                                        v-if="editedField == 'note'"
                                        class="edit"
                                        :class="{ editing: editedField == 'note', 'new-order': editedField != 'note' && order == editedOrder}"
                                        type="text"
                                        v-model="order.note"
                                        @blur="doneEdit(order)"
                                        @keyup.enter="doneEdit(order)"
                                />
                                <label v-if="editedField != 'note' && order == editedOrder" @dblclick="editOrder(order, 'note')">{{ order.note }}</label>

                            </div>
                            <div class="col-md-1">
                                <i class="fas fa-save" :class="{ 'is-invisible': order != editedOrder}"  style="color: #dd1427;" @click="doneEdit(order)"></i>
                            </div>
                        </div>
                    </li>
                </ul>
            </section>
        </section>
        <div class="row ml-auto mr-auto" style="width: 100%">
            <button class="chopcafe_btn form_btn ml-auto mr-auto" style="color:white;" data-toggle="modal" data-target="#orderModal">Заказать</button>
        </div>
    </div>
</template>

<script>
    import {mask} from 'vue-the-mask'
export default {
    name: "UniqueOrder",
    data: () => ({
        phone: '',
        name: '',
        // orders: [],
        newOrder: "",
        title:'',
        code: '',
        quantity: 1,
        note: '',
        editedOrder: {},
        editedField: null,
        // beforeEditCache: ''
    }),
    computed:
        {
            orders() {
                return this.$store.getters.orders;
            }
        },
    methods: {
        addOrder: function() {
            var value = this.title && this.title.trim();
            if (!value||!this.code) {
                return;
            }
            this.orders.push({
                title: value,
                code: this.code,
                quantity: this.quantity,
                note: this.note,
                api:'',
            });
            this.title = "";
            this.code = "";
            this.quantity = 1;
            this.note = "";

        },

        removeOrder: function(order) {
            this.orders.splice(this.orders.indexOf(order), 1);
        },

        editOrder: function(order, field) {
            this.editedField = field;
            // this.beforeEditCache = order;
            this.editedOrder = order;
        },

        doneEdit: function(order) {
            if (!this.editedOrder) {
                return;
            }
            this.editedOrder = {};
            this.editedField = null;
            order.title = order.title.trim();
            order.code = order.code.trim();
            // order.quantity = order.quantity.trim();
            order.note = order.note.trim();
            // order. = order..trim();
            if (!order.code) {
                this.removeOrder(order);
            }
        },

        // cancelEdit: function(order) {
        //     this.editedOrder = null;
        //     order.title = this.beforeEditCache.title;
        // },
        async sendUniqueOrder() {
            var data = {
                name: this.name,
                phone: this.phone,
                orders: this.orders
            }
                // var data = {
                //     vinrequest_id: this.vinrequest_id,
                //     tyre_width: this.tyre_width,
                //     tyre_height: this.tyre_height,
                //     diameter: this.tyre_diameter,
                //     tyre_type: this.tyre_type,
                //     manufacturer: this.tyre_manufacturer,
                //     seasonality: this.seasonality,
                // };
                // this.$store.dispatch('addTyresRequest', data);
            try {
                const resp = await axios.post('api/sendUniqueOrder', data)
                    .then((response) => {
                        if (response.data.status === 'success') {
                            var message = 'Заказ принят. Ожидайте нашего звонка.';
                            var type = 'success';
                            this.sendMessage(message, type)
                            // this.orders=[];
                            this.$store.dispatch('clearOrders');
                        }
                        else {
                            var message = 'Ошибка. Попробуйте снова.';
                            var type = 'error';
                            this.sendMessage(message, type)
                        }
                    })
            } catch (e) {
                var message = 'Ошибка. Попробуйте снова.'
                var type = 'error'
                this.sendMessage(message, type)
            }
        },
        increment() {
            this.quantity++;
        },
        decrement() {
            this.quantity--;
        },
        incrementQuantity(order) {
            order.quantity++;
        },
        decrementQuantity(order) {
            order.quantity--;
        },
        sendMessage(message, type) {
            this.$notify({
                group: 'info',
                type: type,
                title: 'Оповещение АВТОДОН',
                text: message
            });
        },
        // removeCompleted: function() {
        //     this.orders = filters.active(this.orders);
        // }
    },
}
</script>

<style scoped>
[v-cloak] {
    display: none;
}
order-btn {
    margin: 0;
    padding: 0;
    border: 0;
    background: none;
    font-size: 100%;
    vertical-align: baseline;
    font-family: inherit;
    font-weight: inherit;
    color: inherit;
    -webkit-appearance: none;
    appearance: none;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    outline: 0;
}

body {
    font: 14px 'Helvetica Neue', Helvetica, Arial, sans-serif;
    line-height: 1.4em;
    background: #f5f5f5;
    color: #dd1427;
    min-width: 230px;
    max-width: 550px;
    margin: 0 auto;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    font-weight: 300;
}

:focus {
    outline: 0;
}

.hidden {
    display: none;
}
.order-list {

    margin: 0;
    padding: 0;
    list-style: none;

}
.orderapp {
    background: #fff;
    margin: 0px 0 40px 0;
    position: relative;
    box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2),
    0 25px 50px 0 rgba(0, 0, 0, 0.1);
}

.orderapp input::-webkit-input-placeholder {
    font-style: italic;
    font-weight: 300;
    color: #dd1427;
}
.hover-border:hover {
    border-bottom: 3px solid #dd1427 !important;
}
.hover-border:focus {
    border-bottom: 3px solid #dd1427 !important;
}
.no-hover-border {
    border-bottom: 3px solid #fff !important;
}
.orderapp input::-moz-placeholder {
    font-style: italic;
    font-weight: 300;
    color: #dd1427;
}

.orderapp input::input-placeholder {
    font-style: italic;
    font-weight: 300;
    color: #dd1427;
}

.orderapp h1 {
    position: absolute;
    top: -155px;
    width: 100%;
    font-size: 100px;
    font-weight: 100;
    text-align: center;
    color: rgba(175, 47, 47, 0.15);
    -webkit-text-rendering: optimizeLegibility;
    -moz-text-rendering: optimizeLegibility;
    text-rendering: optimizeLegibility;
}

.new-order,
.edit {
    position: relative;
    margin: 0;
    width: 100%;
    font-size: 20px;
    font-family: inherit;
    font-weight: inherit;
    line-height: 1.4em;
    border: 0;
    color: inherit;
    padding: 6px;
    border: 1px solid #999;
    box-shadow: inset 0 -1px 5px 0 rgba(0, 0, 0, 0.2);
    box-sizing: border-box;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

.new-order {
    display:block !important;
    padding: 16px 16px 16px 30px;
    border: none;
    background: rgba(0, 0, 0, 0.003);
    box-shadow: inset 0 -2px 1px rgba(0,0,0,0.03);
    border-bottom: #dd1427;
}

.main {
    position: relative;
    z-index: 2;
    border-top: 1px solid #e6e6e6;
}
.order-list {
    margin: 0;
    padding: 0;
    list-style: none;
}

.order-list li {
    position: relative;
    font-size: 20px;
    border-bottom: 1px solid #ededed;
}

.order-list li:last-child {
    border-bottom: none;
}

.order-list li.editing {
    border-bottom: none;
    padding: 0;
}

.order-list li.editing .editing {
    display: block;
    width: calc(100% - 30px);
    padding: 12px 16px;
    margin: 0 0 0 30px;
}

.order-list li.editing .view {
    display: none;
}
.order-list li label {
    word-break: break-all;
    padding: 15px 15px 15px 30px;
    display: block;
    line-height: 1.2;
    transition: color 0.4s;
    cursor: pointer;
}
.order-list li .destroy {
    display: block;
    position: absolute;
    top: 0;
    right: 20px;
    bottom: 0;
    width: 40px;
    height: 40px;
    margin: auto 0;
    font-size: 30px;
    color:#dd1427;
    margin-bottom: 11px;
}

/*.order-list li .destroy:hover {*/
/*    color: #af5b5e;*/
/*}*/

.order-list li .destroy:after {
    content: '×';
}

/*.order-list li:hover .destroy {*/
/*    display: block;*/
/*}*/

.order-list li .edit {
    display: none;
}

.order-list li.editing:last-child {
    margin-bottom: -1px;
}
/*.q-btn {*/
/*    display:none;*/
/*}*/
.is-invisible {
    display:none;
}
@media (max-width: 768px) {
    .order-list li .destroy {
        width: auto;
        height: auto;
        top: auto;
    }
}
</style>