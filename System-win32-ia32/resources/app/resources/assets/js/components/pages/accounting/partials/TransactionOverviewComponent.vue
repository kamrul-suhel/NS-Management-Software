<template>
    <v-expansion-panel>
        <v-expansion-panel-content
                v-for="(transaction,i) in tableData"
                :key="i"
        >
            <div slot="header">{{ transaction.created_at }}</div>
            <v-card>
                <v-card-text>
                    <v-container fluid grid-list-xs class="pa-0">
                        <v-layout row wrap>
                            <v-flex xs2>Customer name</v-flex>
                            <v-flex xs2>Customer Mobile</v-flex>
                            <v-flex xs2>Discount amount</v-flex>
                            <v-flex xs2>Invoice number</v-flex>
                            <v-flex xs2>Payment status</v-flex>
                            <v-flex xs2>Total</v-flex>
                        </v-layout>

                        <v-divider></v-divider>

                        <v-layout row wrap>
                            <v-flex xs2>{{ transaction.customer.name }}</v-flex>
                            <v-flex xs2>{{ transaction.customer.mobile }}</v-flex>
                            <v-flex xs2>{{ transaction.discount_amount }}</v-flex>
                            <v-flex xs2>{{ transaction.invoice_number }}</v-flex>
                            <v-flex xs2> {{ transaction.payment_status === '1' ? 'Paid' : 'Due' }}</v-flex>
                            <v-flex xs2>TK.{{ transaction.total }}</v-flex>
                        </v-layout>

                        <v-layout row wrap>
                            <v-flex xs12>
                                <h2 class="subheading text-xs-center">Products</h2>
                                <v-divider></v-divider>
                            </v-flex>
                        </v-layout>

                        <v-layout row wrap>
                            <v-flex xs2>Name</v-flex>
                            <v-flex xs2>Description</v-flex>
                            <v-flex xs2>Purchase price</v-flex>
                            <v-flex xs2>Sale price</v-flex>
                            <v-flex xs2>Barcode</v-flex>
                        </v-layout>

                        <v-divider></v-divider>

                        <v-layout row wrap
                                  v-for="(product, index) in transaction.products"
                                  :key="index"
                        >
                            <v-flex xs2>{{product.name}}</v-flex>
                            <v-flex xs2>{{ product.description }}</v-flex>
                            <v-flex xs2>{{ product.purchase_price}}</v-flex>
                            <v-flex xs2>{{ product.sale_price}}</v-flex>
                            <v-flex xs2>{{ product.barcode }}</v-flex>
                        </v-layout>

                    </v-container>
                </v-card-text>
            </v-card>
        </v-expansion-panel-content>
    </v-expansion-panel>
</template>

<script>
    import { mapGetters } from 'vuex'
    export default {
        data(){
            return {

            }
        },

        computed: {
            ...mapGetters({
                tableData: 'getTTableData'
            })
        },

        created(){
        },

        methods : {

        },
    }

</script>
