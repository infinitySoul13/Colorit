import Vue from 'vue';
import Vuex from 'vuex';
import cart from './modules/cart';
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
        cart, products, users, categories
    },
});
