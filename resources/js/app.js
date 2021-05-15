
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
import CartPage from  './pages/CartPage';
import Shop from  './pages/Shop';
import History from  './components/DataTable';
import UniqueOrder from "./components/UniqueOrder";
import Products from "./components/Products";
import AdminProducts from "./pages/products/Index";
import AdminCategories from "./pages/categories/Index";
import ProductPage from "./pages/ProductPage";
import PageHeader from "./components/PageHeader";
import AdminProductEdit from "./pages/products/Edit";
import AdminProductCreate from "./pages/products/Create";

import VueTheMask from 'vue-the-mask';
Vue.use(VueTheMask);

import VueCurrencyFilter from 'vue-currency-filter';
import store from '../js/store';


Vue.component('add-to-cart-btn', AddToCartBtn);
Vue.component('callback-form', CallbackForm);
Vue.component('cart-count-index', CartCountIndex);
Vue.component('cart', Cart);
Vue.component('cart-page', CartPage);
Vue.component('shop', Shop);
Vue.component('history', History);
Vue.component('u-order', UniqueOrder);
Vue.component('products', Products);
Vue.component('admin-products', AdminProducts);
Vue.component('admin-product-edit', AdminProductEdit);
Vue.component('admin-product-create', AdminProductCreate);
Vue.component('admin-categories', AdminCategories);
Vue.component('product-page', ProductPage);
Vue.component('page-header', PageHeader);

Vue.use(Notifications);
Vue.use(VueCurrencyFilter,
    {
        symbol : '£',
        thousandsSeparator: '.',
        fractionCount: 2,
        fractionSeparator: ',',
        symbolPosition: 'back',
        symbolSpacing: true
    })

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
});
import VueSlider from 'vue-slider-component'
import 'vue-slider-component/theme/material.css'
Vue.component('VueSlider', VueSlider);

import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required, email, numeric, alpha } from 'vee-validate/dist/rules';
Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);
extend('email', {
    ...email,
    // message: 'Поле E-mail должно быть действительным адресом электронной почты'
    message: 'The Email field must be a valid email address'
});
extend('required', {
    ...required,
    // message: 'Это поле обязательно к заполнению'
    message: 'This field is required'
});
extend('numeric', {
    ...numeric,
    // message: 'Это поле должно состоять только из цифр'
    message: 'This field must be numeric'
});
extend('alpha', {
    ...alpha,
    // message: 'Это поле должно состоять только из букв'
    message: 'This field must contain only letters'
});
extend('min', {
    validate(value, args) {
        return value.length >= args.length;
    },
    params: ['length'],
    // message: 'Это поле должно быть действительным номером телефона'
    message: 'This field must be a valid phone number'
});
import draggable from 'vuedraggable';
Vue.component('draggable', draggable);

const app = new Vue({
    store,
    el: '#app'
});
