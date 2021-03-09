const state = {
    vinrequests: [],
    vincodes:  [],
    vin_status: '',
    tyres: [],
    disks: [],
    orders: [],
}
// getters
const getters = {
    vinrequests(state) {
        return state.vinrequests;
    },
    vincodes(state) {
        return state.vincodes;
    },
    vin_status(state) {
        return state.vin_status;
    },
    tyres(state) {
        return state.tyres;
    },
    disks(state) {
        return state.disks;
    },
    orders(state) {
        return state.orders;
    },
}

// actions
const actions = {

    async getVinRequests({state, commit}, user_id) {
        commit('change_status', 'loading');
        try {
            const resp = await axios.get('vin/history/'+user_id)
                .then((response) => {
                    commit('change_status', 'success');
                    commit('setVinRequests', response.data.history);
                    commit('setVinCodes', response.data.vincodes);
                });
        } catch (error) {
            console.log(error);
            commit('change_status', 'error');
            throw error
        }
    },
    async addVinRequest ({state, commit}, payload) {
        commit('change_status', 'loading');
        try {
            const resp = await axios.post('vin/create', payload)
                .then((response) => {
                    commit('change_status', 'success');
                    commit('addVinRequest', response.data.vin);
                    var data = {
                        id: response.data.vin.id,
                        vincode: response.data.vin.vincode
                    };
                    commit('addVinCode', data);
                });

        } catch (error) {
            console.log(error);
            commit('change_status', 'error');
            throw error
        }
    },
    async deleteVinRequest ({state, commit}, payload) {
        commit('change_status', 'loading');
        try {
            const resp = await axios.get('vin/delete/'+payload.id)
                .then((response) => {
                    commit('change_status', 'success');
                    commit('deleteVinRequest', payload);
                    commit('deleteVinCode', payload.id);
                })
        } catch (error) {
            commit('change_status', 'error');
            throw error
        }
    },
    async addTyresRequest ({state, commit}, payload) {
        commit('change_status', 'loading');
        try {
            const resp = await axios.post('vin/tyres/add', payload)
                .then((response) => {
                    commit('change_status', 'success');
                    commit('addTyresRequest', response.data.tyre);
                });

        } catch (error) {
            console.log(error);
            commit('change_status', 'error');
            throw error
        }
    },
    async addDisksRequest ({state, commit}, payload) {
        commit('change_status', 'loading');
        try {
            const resp = await axios.post('vin/disks/add', payload)
                .then((response) => {
                    commit('change_status', 'success');
                    commit('addDiskRequest', response.data.disk);
                });
        } catch (error) {
            console.log(error);
            commit('change_status', 'error');
            throw error
        }
    },
    clearOrders({state, commit}) {
        commit('clearOrders');
    }
    // addVinCode ({state, commit}, payload) {
    //     commit('change_status', 'loading');
    //     if(!state.vincodes.some(item => item.vincode == payload)) {
    //         commit('addVinCode', payload);
    //     }
    // }
}

// mutations
const mutations = {
    change_status(state, payload) {
        state.vin_status = payload;
    },
    setVinRequests(state, payload) {
        state.vinrequests = payload;
    },
    addVinRequest(state, payload) {
        state.vinrequests.push(payload)
    },
    setVinCodes(state, payload) {
        state.vincodes = payload;
    },
    addVinCode(state, payload) {
        if(!state.vincodes.some(item => item.vincode == payload.vincode)) {
            state.vincodes.push(payload);
        }
        // localStorage.setItem('vincodes', JSON.stringify(state.vincodes));
    },
    deleteVinRequest(state, payload) {
        state.vinrequests.splice(state.vinrequests.indexOf(payload), 1);
    },
    deleteVinCode(state, payload) {
        state.vincodes = state.vincodes.filter(vincode => vincode.id != payload);
    },
    addTyresRequest(state, payload) {
        state.tyres.push(payload)
    },
    addDisksRequest(state, payload) {
        state.disks.push(payload)
    },
    clearOrders(state) {
        state.orders=[];
    }
}

export default {
    state,
    getters,
    actions,
    mutations
}
