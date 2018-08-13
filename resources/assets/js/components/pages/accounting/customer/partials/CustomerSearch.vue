<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-card>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs12>
                            <v-autocomplete
                                    dark
                                    color="white"
                                    v-model="selectedCustomer"
                                    :items="customers"
                                    item-text="name"
                                    label="Select Customer"
                                    persistent-hint
                                    @input="onSelected()"
                                    return-object
                            ></v-autocomplete>
                        </v-flex>
                    </v-layout>
                </v-card-text>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    import {mapGetters} from 'vuex'
    export default {
        data(){
            return {
                selectedCustomer: {}
            }
        },

        created(){
            this.$store.dispatch('fetchAllCustomers');
        },

        computed: {
            ...mapGetters({
                customers: 'getAllCustomers'
            })
        },

        methods: {
            onSelected(){
                this.$store.dispatch('fetchSelectedCustomerTransactions', this.selectedCustomer);
            }
        }
    }
</script>
