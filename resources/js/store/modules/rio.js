const state = {
    categories: [],
    bodyStyles: [],
    marks: [],
    models: [],
    gearboxes: [],
    driverType: [],
    fuelType: [],
    autoOptions: [],
    // apiKey: 'z8mnjiU2rKUg7pQScey1WhjDPZaeogbyh6aS5leK',
    status: 'success',
    // vinrequests: [],
    // vincodes:  [],
}
// JSON.parse(localStorage.getItem("vincodes")) ||
// getters
const getters = {
    categories(state) {
        return state.categories;
    },
    bodyStyles(state) {
        return state.bodyStyles;
    },
    marks(state) {
        return state.marks;
    },
    models(state) {
        return state.models;
    },
    gearboxes(state) {
        return state.gearboxes;
    },
    driverType(state) {
        return state.driverType;
    },
    fuelType(state) {
        return state.fuelType;
    },
    autoOptions(state) {
        return state.autoOptions;
    },
    status(state) {
        return state.status;
    },
    // vinrequests(state) {
    //     return state.vinrequests;
    // },
    // vincodes(state) {
    //     return state.vincodes;
    // },
}

// actions
const actions = {
    async getCategories({state, commit}) {
        commit('change_status', 'loading');
        try {
            const resp = await axios.get('vin/categories')
            .then((response) => {
                console.log(response.data)
                commit('setCategories', response.data);
                commit('change_status', 'success');
            });
        } catch (error) {
            console.log(error)
            commit('change_status', 'error');
            throw error
        }   
    },
    async getBodyStyles({state, commit}, id) {
        // commit('change_status', 'loading');
        try {
            const resp = await axios.get('vin/body/'+id)
            .then((response) => {
                commit('setBodyStyles', response.data);
                commit('change_status', 'success');
            });
        } catch (error) {
            console.log(error)
            commit('change_status', 'error');
            throw error
        }   
    },
    async getMarks({state, commit}, id) {
        commit('change_status', 'loading');
        try {
            const resp = await axios.get('vin/marks/'+id)
            .then((response) => {
                commit('setMarks', response.data);
                commit('change_status', 'success');
            });
        } catch (error) {
            console.log(error)
            commit('change_status', 'error');
            throw error
        }   
    },
    async getModels({state, commit}, payload) {
        commit('change_status', 'loading');
        try {
            const resp = await axios.get('vin/models/'+payload.category_id+'/'+payload.mark_id)
            .then((response) => {
                commit('setModels', response.data);
                commit('change_status', 'success');
            });
        } catch (error) {
            console.log(error)
            commit('change_status', 'error');
            throw error
        }   
    },
    async getGearboxes({state, commit}, id) {
        // commit('change_status', 'loading');
        try {
            const resp = await axios.get('vin/gear/'+id)
            .then((response) => {
                commit('setGearboxes', response.data);
                commit('change_status', 'success');
            });
        } catch (error) {
            console.log(error)
            commit('change_status', 'error');
            throw error
        }   
    },
    async getDriverType({state, commit}, id) {
        // commit('change_status', 'loading');
        try {
            const resp = await axios.get('vin/driver/'+id)
            .then((response) => {
                commit('setDriverType', response.data);
                commit('change_status', 'success');
            });
        } catch (error) {
            console.log(error)
            commit('change_status', 'error');
            throw error
        }   
    },
    async getFuelType({state, commit}) {
        // commit('change_status', 'loading');
        try {
            const resp = await axios.get('vin/fuel')
            .then((response) => {
                commit('setFuelType', response.data);
                // commit('change_status', 'success');
            });
        } catch (error) {
            console.log(error)
            commit('change_status', 'error');
            throw error
        }   
    },
    async getAutoOptions({state, commit}, id) {
        // commit('change_status', 'loading');
        try {
            const resp = await axios.get('vin/body/'+id)
            .then((response) => {
                commit('setAutoOptions', response.data);
                commit('change_status', 'success');
            });
        } catch (error) {
            console.log(error)
            commit('change_status', 'error');
            throw error
        }   
    },
    // async getVinRequests({state, commit}, user_id) {
    //     commit('change_status', 'loading');
    //     try {
    //         const resp = await axios.get('vin/history/'+user_id)
    //             .then((response) => {
    //                 commit('setVinRequests', response.data.history);
    //                 commit('setVinCodes', response.data.vincodes);
    //                 commit('change_status', 'success');
    //             });
    //     } catch (error) {
    //         console.log(error)
    //         commit('change_status', 'error');
    //         throw error
    //     }
    // },
    // async addVinRequest ({state, commit}, payload) {
    //     commit('change_status', 'loading');
    //     try {
    //         const resp = await axios.post('vin/create', payload)
    //             .then((response) => {
    //                 commit('addVinRequest', response.data.vin);
    //                 var data = {
    //                     id: response.data.vin.id,
    //                     vincode: response.data.vin.vincode
    //                 };
    //                 commit('addVinCode', data);
    //
    //                 // if (localStorage.getItem('vincodes')) {
    //                 //     state.vincodes.push(data);
    //                 //     localStorage.setItem('vincodes', JSON.stringify(state.vincodes));
    //                 // }
    //                 // else {
    //                 //     localStorage.setItem('vincodes', JSON.stringify(state.vincodes));
    //                 // }
    //
    //                 commit('change_status', 'success');
    //             });
    //
    //     } catch (error) {
    //         console.log(error);
    //         commit('change_status', 'error');
    //         throw error
    //     }
    // },
    // async deleteVinRequest ({state, commit}, payload) {
    //     try {
    //         const resp = await axios.post('vin/delete'+payload.id)
    //             .then((response) => {
    //                 commit('deleteVinRequest', payload);
    //             })
    //     } catch (error) {
    //         throw error
    //     }
    // }
    // addVinCode ({state, commit}, payload) {
    //     commit('change_status', 'loading');
    //     if(!state.vincodes.some(item => item.vincode == payload)) {
    //         commit('addVinCode', payload);
    //     }
    // }
}

// mutations
const mutations = {
    change_status(state, status){
        state.status = status;
    },
    setCategories(state, payload) {
        state.categories = payload;
    },
    setBodyStyles(state, payload) {
        state.bodyStyles = payload;
    },
    setMarks(state, payload) {
        state.marks = payload;
    },
    setModels(state, payload) {
        state.models = payload;
    },
    setGearboxes(state, payload) {
        state.gearboxes = payload;
    },
    setDriverType(state, payload) {
        state.driverType = payload;  
    },
    setFuelType(state, payload) {
        state.fuelType = payload;
    },
    setAutoOptions(state, payload) {
        state.autoOptions = payload;
    },
    // setVinRequests(state, payload) {
    //     state.vinrequests = payload;
    // },
    // addVinRequest(state, payload) {
    //     console.log(state.vinrequests)
    //     console.log(payload)
    //     state.vinrequests.push(payload)
    // },
    // setVinCodes(state, payload) {
    //     state.vincodes = payload;
    // },
    // addVinCode(state, payload) {
    //     if(!state.vincodes.some(item => item.vincode == payload.vincode)) {
    //         state.vincodes.push(payload);
    //     }
    //     // localStorage.setItem('vincodes', JSON.stringify(state.vincodes));
    // },
    // deleteVinrequest(state, payload) {
    //     state.vinrequests.splice(state.vinrequests.indexOf(payload), 1);
    // }
}

export default {
    state,
    getters,
    actions,
    mutations
}
