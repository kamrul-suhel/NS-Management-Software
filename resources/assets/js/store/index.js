import Vue from 'vue';
import Vuex from 'vuex';

import settings from './modules/settings';
import productTransaction from './modules/accounting/product_transaction'
import customers from './modules/customer'
import user from './modules/user'
import shop from './modules/shop'

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        products:[],
    },

    mutations: {
        resetProductTransition(state){
            state.products = [];
        }
    },

    actions: {
        setTransaction({state}, product){

            return new Promise((resolve, reject) => {
                if(state.products.length === 0){
                    state.products.push(product);
                    resolve();
                }else{
                    if(state.products[product.index] === 'undefined'){
                        state.products.push(product);
                        console.log(state.products);
                        resolve();
                    }else{
                        state.products[product.index] = product;
                        resolve();
                    }
                }
            });
        },

        removeProduct({state}, removeProduct){
            return new Promise((resolve, reject) => {
                const totalProduct = state.products.filter((product, index) => {
                    return product.id !== removeProduct.id;
                });

                state.products = [...totalProduct];
                resolve();
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
        user,
        shop
    }
});

export default store;
