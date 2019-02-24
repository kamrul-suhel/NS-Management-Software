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
        },

        removeProduct(index) {
            this.$emit('removeProduct', index);
        }
    }

})
export default TransactionEventBus;