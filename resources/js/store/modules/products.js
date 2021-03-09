const state = {
    products: [],
    deleted_products: [],
    products_totalRows: 0,
    deleted_products_totalRows:0,
    product_categories:[],
}

// getters
const getters = {
    products: (state, getters, rootState) => {
        return state.products;
    },
    deleted_products: (state, getters, rootState) => {
        return state.deleted_products;
    },
    product_categories: (state, getters, rootState) => {
        return state.product_categories;
    },
    products_totalRows: (state, getters, rootState) => {
        return state.products_totalRows;
    },
    deleted_products_totalRows: (state, getters, rootState) => {
        return state.deleted_products_totalRows;
    },
}
// actions
const actions = {
    loadDataProduct({state, commit}) {
        return axios
            .get('/products/get')
            .then(resp => {
                state.products = resp.data.products;
                state.products_totalRows = state.products.length;
                state.deleted_products = resp.data.deleted_products;
                state.deleted_products_totalRows = state.deleted_products.length;
                state.product_categories = resp.data.categories;
            });
    },
    async loadCategoriesForShop({state, commit}) {
        return await axios
            .get('/products/categories')
            .then(resp => {
                state.product_categories = resp.data.categories;
                state.products = resp.data.products;
            });
    },
    loadProductsForShop({state, commit}) {
        state.product_categories.forEach( item => {
            console.log('loadProductsForShopItem', item);
            axios
                .get('/products/more/'+item.title, {category: item.title})
                .then(response => {
                    console.log('loadProductsForShop', response.data);
                    commit('addMoreProducts', response.data.products.data)
            });
        })
    },
    async removeProduct({state, commit}, id) {
        commit('removeProduct', id)
        return await axios
            .delete(`/products/${id}`)
    },
    async forceDeleteProduct({state, commit}, id) {
        commit('forceDeleteProduct', id)
        return await axios
            .post(`/products/forceDelete/${id}`)
    },
    async restoreProduct({state, commit}, id) {
        commit('restoreProduct', id)
        return await axios
            .post(`/products/restore/${id}`)
    },
    async removeAllProduct({state, commit}, ids) {
        commit('removeAllProduct', ids)
        return await axios
            .post(`/products/deleteAll`, ids)
    },
    async forceDeleteAllProduct({state, commit}, ids) {
        commit('forceDeleteAllProduct', ids)
        return await axios
            .post(`/products/forceDeleteAll/`, ids)
    },
    async restoreAllProduct({state, commit}, ids) {
        commit('restoreAllProduct', ids)
        return await axios
            .post(`/products/restoreAll`, ids)
    },
    async saveAllProduct({state, commit}, param) {
        return await axios
            .post(`/products/updateAll`, param);
    },
    async saveProduct({state, commit}, param) {
        return await axios
            .put(`/products/${param.id}`, {
                param: param.key,
                value: param.value
            });
    },
    async addProduct({state, commit}, product) {
        await axios
            .post('/products').then(resp => {
                commit('addProduct', resp.data.product)
            })
    },
    async addMoreProducts({state, commit}, products) {
        commit('addMoreProducts', products);
    },
    incPage({state, commit}, category) {
        commit('incPage', category);
    },
}

// mutations
const mutations = {
    addProduct(state, payload) {
        state.products.push(payload)
        state.products_totalRows = state.products.length;
    },
    addMoreProducts(state, payload) {
        payload.forEach(item => {
            state.products.push(item)
        })
    },
    saveProduct(state, payload) {
        let product = state.products.findIndex(x => x.id === payload.id)
        state.products[product] = payload;
    },
    async removeProduct(state, payload) {
        let prod = state.products.findIndex(x => x.id === payload)

        if (prod >= 0) {
            let tmp = state.products[prod];
            tmp.deleted_at = "-";
            state.products.splice(prod, 1);
            state.deleted_products.push(tmp)
            state.products_totalRows = state.products.length;
            state.deleted_products_totalRows = state.deleted_products.length;
        }
    },

    forceDeleteProduct(state, payload) {
        let product = state.deleted_products.findIndex(x => x.id === payload)
        state.deleted_products.splice(product, 1)
        state.products_totalRows = state.products.length;
        state.deleted_products_totalRows = state.deleted_products.length;
    },
    restoreProduct(state, payload) {
        let product = state.deleted_products.findIndex(x => x.id === payload)
        let item = state.deleted_products[product];
        item.deleted_at = null;
        state.deleted_products.splice(product, 1)
        state.products.push(item)
        state.products_totalRows = state.products.length;
        state.deleted_products_totalRows = state.deleted_products.length;
    },
    async removeAllProduct(state, payload) {
        payload.ids.forEach(id => {
            let prod = state.products.findIndex(x => x.id === id)

            if (prod >= 0) {
                let tmp = state.products[prod];
                tmp.deleted_at = "-";
                state.products.splice(prod, 1);
                state.deleted_products.push(tmp)
            }
        });
        state.products_totalRows = state.products.length;
        state.deleted_products_totalRows = state.deleted_products.length;
    },

    forceDeleteAllProduct(state, payload) {
        payload.ids.forEach(id => {
            let product = state.deleted_products.findIndex(x => x.id === payload)
            state.deleted_products.splice(product, 1)
        })
        state.products_totalRows = state.products.length;
        state.deleted_products_totalRows = state.deleted_products.length;
    },
    restoreAllProduct(state, payload) {
        payload.ids.forEach(id => {
            let product = state.deleted_products.findIndex(x => x.id === id)
            let item = state.deleted_products[product];
            item.deleted_at = null;
            state.deleted_products.splice(product, 1)
            state.products.push(item)
        });
        state.products_totalRows = state.products.length;
        state.deleted_products_totalRows = state.deleted_products.length;
    },
    saveAllProduct(state, payload) {
        payload.ids.forEach(id => {
            let product = state.products.findIndex(x => x.id === id)
            // state.products[product].category = payload.category;
            state.products[product][payload.key]= payload.value;
        });
    },
    incPage(state, payload) {
        let index = state.product_categories.findIndex(x => x.title === payload)
        state.product_categories[index].page++;
    },

}

export default {
    state,
    getters,
    actions,
    mutations
}
