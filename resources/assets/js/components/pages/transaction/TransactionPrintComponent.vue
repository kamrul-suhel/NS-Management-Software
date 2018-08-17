<template>
    <div class="transaction-print">

        <v-container grid-list-lg v-if="initialize">
            <v-layout row wrap>
                <v-card
                        raised
                        width="100%">
                    <v-card-text>
                        <h2 class="text-xs-center">{{ data.setting.company_name ? data.setting.company_name : ''}}</h2>
                        <h3 class="text-xs-center">Address: {{ data.setting.company_address }}</h3>
                        <v-layout row wrap>
                            <v-flex xs6 class="company-info-left">
                                <p>Phone: {{ data.setting.company_phone }}</p>
                                <p>Mobile: {{ data.setting.company_mobile }}</p>
                                <p>Email: {{ data.setting.company_email }}</p>
                                <p>Shop Number: {{ data.setting.company_shop_number }}</p>
                            </v-flex>
                            <v-flex xs6 style="text-align:right" class="company-info-right">
                                <p>Date: {{ new Date().toJSON().slice(0,10).split('-').reverse().join('/') }}</p>
                                <p>Invoice : {{ data.transaction.invoice_number.toUpperCase() }}</p>
                            </v-flex>
                            <v-spacer></v-spacer>
                        </v-layout>

                        <v-layout row wrap>
                            <v-flex xs6>
                                <h3>Bill To</h3>
                                <p>{{ data.transaction.customer.name }}</p>
                                <p>{{ data.transaction.customer.mobile }}</p>
                            </v-flex>

                            <v-spacer></v-spacer>

                            <v-flex xs4>
                                <h3>Ship To</h3>
                                <p>{{ data.transaction.customer.address }}</p>
                            </v-flex>
                        </v-layout>

                        <v-layout row wrap>
                            <v-divider></v-divider>
                        </v-layout>

                        <v-layout row wrap class="transaction-print-table">
                            <v-flex xs12>
                                <table width="100%">
                                    <thead>
                                        <td>Item</td>
                                        <td>S.N - Description</td>
                                        <td>Warranty</td>
                                        <td>Unit Price<br/>(Taka)</td>
                                        <td>Qty</td>
                                        <td>Total (Taka)</td>
                                    </thead>

                                    <tbody>
                                        <tr v-for="product in data.transaction.products" :key="product.id">
                                            <td>{{ product.name }}</td>
                                            <td><span v-for="(serial, index) in product.productSaleSerial" :key="index">{{ serial.product_serial }} </span></td>
                                            <td>{{ product.productWarranty}}</td>
                                            <td>TK. {{ product.sale_price | price_format }}</td>
                                            <td>{{ product.sale_quantity }}</td>
                                            <td>TK. {{ product.sale_price * product.sale_quantity }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </v-flex>
                        </v-layout>

                        <v-layout row wrap>
                            <v-flex xs12 class="text-xs-right">
                                <p>Subtotal: {{ subtotal | price_format(true) }}</p>
                                <p>Discount: {{ data.transaction.discount_amount | price_format }}</p>
                                <p>Balance Due: {{ data.transaction.payment_due | price_format }}</p>
                                <p>Total Pay: {{ subtotal - discount | price_format }}</p>
                            </v-flex>
                        </v-layout>

                        <v-layout row wrap class="print-signature-section">
                            <v-flex xs4>
                                <p>Client</p>
                                <p>Signutare</p>
                            </v-flex>

                            <v-spacer></v-spacer>

                            <v-flex xs4>
                                <p>Seller</p>
                                <p>Signutare</p>
                            </v-flex>
                        </v-layout>

                        <v-divider></v-divider>

                        <v-layout row wrap class="print-footer">
                            <v-flex xs6>
                                <p>Please Check Before You Buy. After Used No Return. Thanks For Business With Us.</p>
                                <p>{{ data.setting.company_website }}</p>
                            </v-flex>
                            <v-flex xs6></v-flex>
                        </v-layout>

                    </v-card-text>
                </v-card>
            </v-layout>
        </v-container>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                data: '',
                initialize: false,
                subtotal:0,
                discount:0
            }
        },

        created(){
            this.initilize();
        },

        methods: {
            initilize(){
                var id = this.$route.params.id;
                var url = '/transaction/'+id+'/print';
                axios.get(url).then((response) => {
                    this.data = response.data;
                    this.discount = response.data.transaction.discount_amount;
                    this.initializeSubtotal();
                    this.initialize = true;
                });
            },

            initializeSubtotal(){
                var subtotal = 0;
                this.data.transaction.products.forEach(function(product){
                    subtotal += product.sale_quantity * product.sale_price;
                });
                this.subtotal = subtotal;
            }
        }
    }
</script>

<style scoped>

</style>
