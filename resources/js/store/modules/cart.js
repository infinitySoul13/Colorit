const state = {
    items: JSON.parse(localStorage.getItem('cart')) || [],
}

// getters
const getters = {
    cartProducts: (state, getters, rootState) => {
        return state.items;
    },
    cartTotalCount: (state, getters) => {
        let summ = 0;
        state.items.forEach((item)=>{
            summ+=item.quantity
        });
        return summ
    },
    cartTotalPrice: (state, getters) => {
        let summ = 0;
        state.items.forEach((item)=>{
            if (item.product.type == 'sale') {
                summ+=item.product.discount_price*item.quantity
            }
            else {
                summ+=item.product.price*item.quantity
            }
        });
        return summ
    }
}

// actions
const actions = {
    getProductList({state, commit}) {
        return state.items
    },
    addProductToCart({state, commit}, product) {
        commit('pushProductToCart', product);
    },
    incQuantity({state, commit}, product) {
        commit('incrementItemQuantity', product);
    },
    decQuantity({state, commit}, product) {
        commit('decrementItemQuantity', product);
    },
    removeCartProduct({state, commit}, product) {
        commit('removeItem', product);
    },
    clearCart({state, commit}) {
        commit('clearAllItems');
    }
}

// mutations
const mutations = {
    pushProductToCart(state, product) {
        const cartItem = state.items.find(item => {
            if (item.product.id === product.product.id){
                if(item.size == product.size && item.color == product.color)
                {
                    return true;
                }
            }
            return false;
        });
        if (!cartItem) {
            state.items.push({
                product: product.product,
                color: product.color,
                size: product.size,
                quantity: product.quantity
            });
        }
        else {
            cartItem.quantity = Number(cartItem.quantity)+Number(product.quantity);
        }

        localStorage.setItem('cart', JSON.stringify(state.items));
    },

    incrementItemQuantity(state, product) {
        const cartItem = state.items.find(item => {
            if (item.product.id === product.product.id){
                if(item.size == product.size && item.color == product.color)
                {
                    return true;
                }
            }
            return false;
        });
        cartItem.quantity++;
        localStorage.setItem('cart', JSON.stringify(state.items));
    },

    decrementItemQuantity(state, product) {
        const cartItem = state.items.find(item => {
            if (item.product.id === product.product.id){
                if(item.size == product.size && item.color == product.color)
                {
                    return true;
                }
            }
            return false;
        });
        if (cartItem.quantity > 1)
            cartItem.quantity--;
        localStorage.setItem('cart', JSON.stringify(state.items));
    },
    removeItem(state, product) {
        let tmp = state.items.filter((item) =>{
            if (item.product.id === product.product.id){
                if(item.size === product.size && item.color === product.color)
                {
                    return false;
                }
            }
            return true;
        });
        state.items = tmp;
        localStorage.setItem('cart', JSON.stringify(state.items));
        //commit('setCartItems',tmp)
    },

    clearAllItems(state) {
        state.items = [];
        localStorage.setItem('cart', JSON.stringify(state.items));
        //commit('setCartItems',tmp)
    },
    setCartItems(state, {items}) {
        state.items = items
    },

}

export default {
    state,
    getters,
    actions,
    mutations
}
