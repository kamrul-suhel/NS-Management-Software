import Vue from 'vue';
import Vuex from 'vuex';

import settings from './modules/settings';
import productTransaction from './modules/accounting/product_transaction'
import customers from './modules/customer'

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        products:[],
    },

    mutations: {

    },

    actions: {
        setTransaction({state}, product){

            return new Promise((resolve, reject) => {
                if(state.products.length === 0){
                    state.products.push(product);
                    console.log(state.products);
                    resolve();
                }else{
                    if(state.products[product.product.index] === 'undefined'){
                        state.products.push(product);
                        console.log(state.products);
                        resolve();
                    }else{
                        state.products[product.index] = product;
                        console.log(state.products);
                        resolve();
                    }
                }
            });

        }
    },

    getters: {
        getProduct(state){
            return state.products;
        }
    },

    modules: {
        settings,
        productTransaction,
        customers,
    }
});

export default store;
