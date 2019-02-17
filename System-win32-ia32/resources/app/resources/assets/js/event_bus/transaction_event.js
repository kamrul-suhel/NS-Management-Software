import Vue from 'vue';

const TransactionEventBus = new Vue({
    data(){
        return{

        }
    },

    methods: {
        updateProduct() {
            this.$emit('updateProduct');
        },

        createProduct(message){
            
            this.$emit('productCreate', message)
        }
    }

})
export default TransactionEventBus;