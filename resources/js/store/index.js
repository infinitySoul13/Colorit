import Vue from 'vue';
import Vuex from 'vuex';
import cart from './modules/cart';
import rio from './modules/rio';
import vin from './modules/vin';
import users from './modules/users';
import products from './modules/products';
import categories from "./modules/categories";


Vue.use(Vuex);

export default new Vuex.Store({
    state: {},
    getters: {},
    mutations: {},
    actions: {},
    modules: {
        cart, rio, vin, products, users, categories
    },
});
