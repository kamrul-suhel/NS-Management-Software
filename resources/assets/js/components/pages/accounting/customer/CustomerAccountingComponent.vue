<template>
    <v-container grid-list-lg>
        <v-layout row wrap>
            <v-flex xs12>
                <h2>Customer Laser</h2>
                <v-divider class="mb-3 dark"></v-divider>
            </v-flex>
        </v-layout>

        <customer-search></customer-search>

        <customer-transaction-data-component></customer-transaction-data-component>


    </v-container>
</template>

<script>
    import CustomerSearch from './partials/CustomerSearch'
    import CustomerTransactionDataComponent from './partials/CustomerTransactionDataComponent'
    export default {
        components: {
            CustomerSearch,
            CustomerTransactionDataComponent
        },

        data(){
            return {

            }
        },

        computed: {

        },

        created(){

        },

        watch: {

        },

        methods: {

        }
    }
</script>
