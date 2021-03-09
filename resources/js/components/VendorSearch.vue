<template>
    <div class="row align-items-center justify-content-center ml-auto mr-auto" style="width: 100%">
        <div class="row align-items-center justify-content-center ml-auto mr-auto" style="width: 100%">
<!--            v-if=""-->
            <div class="spinner-border text-danger mr-2 mb-2" v-if="status=='loading'" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <div class="col-md-3 col-lg-3 p-0 mb-2">

                    <input type="text" placeholder="Введите артикул"
                           v-model="vendor_code"
                           required="required" class="form_control"
                           @keyup.enter="searchVendorCode()">

            </div>
            <div class="col-md-2 col-lg-2 mb-2">
                <div class="row">
                    <button class="chopcafe_btn form_btn m-auto" v-on:click="searchVendorCode()">Поиск</button>
                </div>

            </div>
        </div>
        <transition name="fade" mode="out-in" >
            <div v-if ="search==true">
                <history title="Результаты поиска"
                         :columns="columns"
                         :rows="results"
                         :perPage="perPage"
                         v-on:row-click="onRowClick">
                    <th slot="thead-tr">
                        Действия
                    </th>
                    <template slot="tbody-tr" slot-scope="props">
                        <td>
                            <button type="button" class="btn" style="background-color: #dd1427; color:#fff;"
                                    @click="addToOrders(props.row)">
                                Заказать
                            </button>
                        </td>
                    </template>
                </history>
            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        name: "VendorSearch",
        data: () => ({
            vendor_code:'',
            search: false,
            status: 'success',
            results: [],
            perPage: ['20', '50', '100'],
            columns: [
                {
                    label: 'Номер артикула',
                    field: 'code',
                },
                {
                    label: 'Бренд',
                    field: 'brand',
                },
                {
                    label: 'Наименование',
                    field: 'title',
                },
                {
                    label: 'Цена',
                    field: 'price',
                },
            ]
        }),
        computed:
        {
            orders() {
                return this.$store.getters.orders;
            }
        },
        methods: {
            searchVendorCode(){
                if (this.vendor_code != '') {
                    try{
                        this.status = 'loading';
                        this.results = [];
                        axios.post('vendorSearch', {vendor_code: this.vendor_code} )
                            .then((response) => {
                                response.data.result.forEach( item => {
                                    var price = Number(item.price.slice(5, -4));
                                    item.price ='Цена: '+(price + (price*0.3)).toFixed(2) + 'руб.';
                                    this.results.push(item)
                                })
                                // this.results = response.data.result;
                                if(this.results == [] ||this.results.length == 0) {
                                    this.status = 'error';
                                    this.search = false;
                                    var message = 'К сожалению, ничего не найдено.';
                                    var type = 'error';
                                    this.sendMessage(message, type)
                                }
                                else {
                                    this.status = 'success';
                                    this.search = true;
                                    var message = 'Поиск завершен.';
                                    var type = 'success';
                                    this.sendMessage(message, type)
                                }
                            });
                    }
                    catch (e) {
                        this.results =[];
                        var message = 'Произошла ошибка. Попробуйте снова.';
                        var type = 'error';
                        this.sendMessage(message, type);
                    }
                }
                else {
                    this.results =[];
                    var message = 'Введите артикул';
                    var type = 'error';
                    this.sendMessage(message, type);
                }

            },
            addToOrders(row){
                var order = {
                    id: row.id,
                    title: row.title,
                    code: row.code,
                    quantity: 1,
                    api: row.api,
                    note: row.brand+'; ' + row.price,
                }
                console.log(order);
                const orderItem = this.orders.find(item => {
                    if(item.id == order.id)
                    {
                      if (item.note == order.note){
                          return item;
                      }
                    }
                });
                if (!orderItem)
                    this.orders.push({
                        id: row.id,
                        title: row.title,
                        code: row.code,
                        quantity: 1,
                        api: row.api,
                        note: row.brand+'; ' + row.price,
                    });
                else
                    orderItem.quantity++;

                var message = 'Заказ добавлен в секцию "ЗАКАЗ УНИКАЛЬНОГО ТОВАРА, откуда Вы сможете его отправить';
                var type = 'success';
                this.sendMessage(message, type)
            },
            sendMessage(message, type) {
                this.$notify({
                    group: 'info',
                    type: type,
                    title: 'Оповещение АВТОДОН',
                    text: message
                });
            },
            onRowClick: function (row) {
                this.vendor_code = row.code;
            }
        }
    }
</script>

<style scoped>

</style>