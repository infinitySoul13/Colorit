
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import DataTable from "./components/DataTable";

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import Vue from 'vue';
import {TinkerComponent} from 'botman-tinker';
Vue.component('botman-tinker', TinkerComponent);
import AddToCartBtn from './components/AddCartBtn';
import Notifications from 'vue-notification';
import CallbackForm from  './components/CallbackForm';
import CartCountIndex from  './components/CartCountIndex';
import Cart from  './components/Cart';
// import Vin from  './components/Vin';
import History from  './components/DataTable';
// import Tabs from  './components/Tabs';
import UniqueOrder from "./components/UniqueOrder";
// import VendorSearch from "./components/VendorSearch";
import Products from "./components/Products";
import AdminProducts from "./pages/products/Index";
import AdminCategories from "./pages/categories/Index";

import VueTheMask from 'vue-the-mask';
Vue.use(VueTheMask);

import VueCurrencyFilter from 'vue-currency-filter';
import store from '../js/store';


Vue.component('add-to-cart-btn', AddToCartBtn);
Vue.component('callback-form', CallbackForm);
Vue.component('cart-count-index', CartCountIndex);
Vue.component('cart', Cart);
// Vue.component('vin', Vin);
Vue.component('history', History);
Vue.component('u-order', UniqueOrder);
// Vue.component('vendor-search', VendorSearch);
Vue.component('products', Products);
Vue.component('admin-products', AdminProducts)
Vue.component('admin-categories', AdminCategories)

Vue.use(Notifications);
Vue.use(VueCurrencyFilter,
    {
        symbol : '₽',
        thousandsSeparator: '.',
        fractionCount: 2,
        fractionSeparator: ',',
        symbolPosition: 'back',
        symbolSpacing: true
    })

// Vue.component('tabs', Tabs);
// Vue.component('tab', {
//     template: `
//         <div v-show="isActive"><slot></slot></div>
//     `,
//     props: {
//         name: { required: true },
//         selected: { default: false}
//     },
//     data() {
//         return {
//             isActive: false
//         };
//     },
//     computed: {
//         href() {
//             return '#' + this.name.toLowerCase().replace(/ /g, '-');
//         }
//     },
//     mounted() {
//         this.isActive = this.selected;
//     }
// });
// import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)

const moment = require('moment');
require('moment/locale/ru');
Vue.use(require('vue-moment'), {
    moment
});

import skeleton from 'tb-skeleton'
import 'tb-skeleton/dist/skeleton.css'
Vue.use(skeleton);

import "vue-multiselect/dist/vue-multiselect.min.css"
import Multiselect from 'vue-multiselect';
Vue.component('multiselect', Multiselect);
const VueScrollTo = require('vue-scrollto');
Vue.use(VueScrollTo, {
    container: "body",
    duration: 500,
    easing: "ease",
    offset: 0,
    force: true,
    cancelable: true,
    onStart: false,
    onDone: false,
    onCancel: false,
    x: false,
    y: true
})
const app = new Vue({
    store,
    el: '#app'
});
