<template>
    <v-container grid-list-lg>
        <v-layout row wrap>
            <v-flex xs12>
                <h2>Customer Laser</h2>
                <v-divider class="mb-3 dark"></v-divider>
            </v-flex>
        </v-layout>

        <customer-search></customer-search>


    </v-container>
</template>

<script>
    import CustomerSearch from './partials/CustomerSearch'
    export default {
        components: {
            CustomerSearch
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
