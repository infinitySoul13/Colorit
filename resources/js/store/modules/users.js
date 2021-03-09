const state = {
    users: [],
    deleted_users: [],
}

// getters
const getters = {
    users: (state, getters, rootState) => {
        return state.users;
    },
    deleted_users: (state, getters, rootState) => {
        return state.deleted_users;
    },
}

// actions
const actions = {
    loadDataUser({state, commit}) {
        return axios
            .get('/users/get')
            .then(resp => {
                state.users = resp.data.users;
                state.deleted_users = resp.data.deleted_users;
            });
    },
    async removeUser({state, commit}, id) {
        commit('removeUser', id)
        return await axios
            .delete(`/users/${id}`)
    },
    async forceDeleteUser({state, commit}, id) {
        commit('forceDeleteUser', id)
        return await axios
            .post(`/users/forceDelete/${id}`)
    },
    async restoreUser({state, commit}, id) {
        commit('restoreUser', id)
        return await axios
            .post(`/users/restore/${id}`)
    },
    async saveUser({state, commit}, param) {
        return axios
            .put(`/users/${param.id}`, {
                param: param.key,
                value: param.value
            });
    },
    async addUser({state, commit}, user) {
        await axios
            .post('/users').then(resp => {
                commit('addUser', resp.data.user)
            })
    },
}

// mutations
const mutations = {
    addUser(state, payload) {
        state.users.push(payload)
    },
    saveUser(state, payload) {
        let user = state.users.findIndex(x => x.id === payload.id)
        state.users[user] = payload;
    },
    forceDeleteUser(state, payload) {
        state.deleted_users.filter(user => user.id !== payload);
    },
    restoreUser(state, payload) {
        let user = state.deleted_users.findIndex(x => x.id === payload)
        let item = state.deleted_users[user];
        item.deleted_at = null;
        state.deleted_users.splice(user, 1)
        state.users.push(item)
    },

}

export default {
    state,
    getters,
    actions,
    mutations
}
