<template>
    <div class="transaction-component">
                <v-card
                        raised
                        width="100%">
                    <v-card-text>
                        <v-text-field
                                dark
                                color="dark"
                                prepend-icon="search"
                                label="Search"
                                v-model="search"></v-text-field>

                        <v-data-table
                                :headers="headers"
                                :items="items"
                                :search="search"
                                :pagination.sync="pagination"
                                :rows-per-page-items="row_per_page"
                                item-key="name"
                        >

                            <template slot="headers" slot-scope="props">
                                <tr>
                                    <th
                                            v-for="header in props.headers"
                                            :key="header.value"
                                            :class="['column sortable', pagination.descending ? 'desc' : 'asc', header.value === pagination.sortBy ? 'active' : '']"
                                            @click="changeSort(header.value)"
                                    >
                                        <v-icon small>arrow_upward</v-icon>
                                        {{ header.text}}
                                    </th>
                                </tr>
                            </template>

                            <template slot="items" slot-scope="props">
                                <td class="text-xs-center">{{ props.item.invoice_number.toUpperCase() }}</td>
                                <td class="text-xs-center">{{ props.item.products.length ? props.item.products.length : 0 }}</td>
                                <td class="text-xs-center">{{ getPaymentStatus(props.item.payment_status) }}</td>
                                <td class="text-xs-center">TK. {{ props.item.discount_amount? price_format(props.item.discount_amount): 0 }}</td>
                                <td class="text-xs-center">TK. {{ props.item.paid ? price_format(props.item.paid): 0 }}</td>
                                <td class="text-xs-center">TK. {{ props.item.payment_due? price_format(props.item.payment_due): 0 }}</td>
                                <td class="text-xs-center">TK. {{ props.item.total? price_format(props.item.total): 0 }}</td>
                            </template>

                            <v-alert slot="no-results" :value="true" color="error" icon="warning">
                                Your search for "{{ search }}" found no results.
                            </v-alert>

                            <template slot="no-data">
                                Sorry this customer do not have transition.
                            </template>
                        </v-data-table>
                    </v-card-text>
                </v-card>
    </div>
</template>
<script>

    import axios from 'axios'

    import {mapGetters} from 'vuex'

    export default {
        data: () => ({


            search: '',
            pagination: {
                sortBy: 'name'
            },

            headers: [
                {
                    text: 'Invoice number',
                    value: 'invoice_number',
                    sortable: false
                },
                {
                    text: 'Product',
                    value: 'product',
                    sortable: true
                },

                {
                    text: 'Status',
                    value: 'transaction_status',
                    sortable: false
                },

                {
                    text: 'Discount',
                    value: 'discount',
                    sortable: true
                },

                {
                    text: 'paid',
                    value: 'paid',
                    sortable: false
                },

                {
                    text: 'Due',
                    value: 'payment_due',
                    sortable: true
                },

                {
                    text: 'Total',
                    value: 'total',
                    sortable: true
                },

            ],
            products: [],
            allProductData: [],


            row_per_page: [{'text': 'All', 'value': -1}],

        }),

        computed: {
            ...mapGetters({
                items: 'getAllTransaction'
            })
        },

        watch: {
            items(){
                console.log(this.items);
            }
        },

        created() {
            // this.initialize()
        },

        methods: {
            initialize() {
                // get all transaction
                axios.get('/api/transactions')
                    .then((response) => {
                        if(response.data.transactions){
                            this.items = response.data.transactions;
                        }
                        this.total_transactions = response.data.total_transactions;
                        this.total_amount_transactions = response.data.total_tk;
                    })
                    .catch((error) => {
                        console.log(error)
                    })

            },


            changeSort(column) {
                if (this.pagination.sortBy === column) {
                    this.pagination.descending = !this.pagination.descending
                } else {
                    this.pagination.sortBy = column
                    this.pagination.descending = false
                }
            },

            price_format(val){
                return val.toFixed(2).replace(/./g, function(c, i, a) {
                    return i && c !== "." && ((a.length - i) % 3 === 0) ? ',' + c : c;
                });
            },

            getPaymentStatus(value){
                if(value === 1){
                    return 'Paid'
                }
                if(value === 2){
                    return 'Due'
                }

                if(value === 3){
                    return 'Half paid'
                }
            }
        }
    }
</script>
