const state = {
    admin_categories: [],
    deleted_categories: [],
}

// getters
const getters = {
    admin_categories: (state, getters, rootState) => {
        return state.admin_categories;
    },
    deleted_categories: (state, getters, rootState) => {
        return state.deleted_categories;
    },
}

// actions
const actions = {
    loadDataCategory({state, commit}) {
        return axios
            .get('/categories/get')
            .then(resp => {
                state.admin_categories = resp.data.categories;
                state.deleted_categories = resp.data.deleted_categories;
            });
    },

    async removeCategory({state, commit}, id) {
        commit('removeCategory', id)
        return await axios
            .delete(`/categories/${id}`)
    },
    async forceDeleteCategory({state, commit}, id) {
        commit('forceDeleteCategory', id)
        return await axios
            .post(`/categories/forceDelete/${id}`)
    },
    async restoreCategory({state, commit}, id) {
        commit('restoreCategory', id)
        return await axios
            .post(`/categories/restore/${id}`)
    },
    async saveCategory({state, commit}, param) {
        return await axios
            .put(`/categories/${param.id}`, {
                param: param.key,
                value: param.value
            });
    },
    async addCategory({state, commit}, category) {
        return await axios
            .post('/categories', category);
    },
}

// mutations
const mutations = {
    addCategory(state, payload) {
        state.admin_categories.push(payload)
    },
    saveCategory(state, payload) {
        let category = state.admin_categories.findIndex(x => x.id === payload.id)
        state.admin_categories[category] = payload;
    },
    forceDeleteCategory(state, payload) {
        let category = state.deleted_categories.findIndex(x => x.id === payload)
        state.deleted_categories.splice(category, 1)
    },
    async removeCategory(state, payload) {
        let cat = state.admin_categories.findIndex(x => x.id === payload)

        if (cat >= 0) {
            let tmp = state.admin_categories[cat];
            tmp.deleted_at = "-";
            state.admin_categories.splice(cat, 1);
            state.deleted_categories.push(tmp)
        }
    },
    restoreCategory(state, payload) {
        let category = state.deleted_categories.findIndex(x => x.id === payload)
        let item = state.deleted_categories[category];
        item.deleted_at = null;
        state.deleted_categories.splice(category, 1)
        state.admin_categories.push(item)
    },

}

export default {
    state,
    getters,
    actions,
    mutations
}
