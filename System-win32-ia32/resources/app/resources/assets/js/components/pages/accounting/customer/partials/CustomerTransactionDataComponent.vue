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
                        hide-actions
                        :headers="headers"
                        :items="items"
                        :search="search"
                        :pagination.sync="pagination"
                        :rows-per-page-items="row_per_page"
                        item-key="invoice_number"
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
                        <tr @click="props.expanded = !props.expanded">
                            <td class="text-xs-center">{{ props.item.created_at | convertDate }}</td>
                            <td class="text-xs-center">{{ props.item.invoice_number.toUpperCase() }}</td>
                            <td class="text-xs-center">{{ props.item.products.length ? props.item.products.length : 0
                                }}
                            </td>
                            <td class="text-xs-center">{{ getPaymentStatus(props.item.payment_status) }}</td>
                            <td class="text-xs-center">TK. {{ props.item.discount_amount?
                                price_format(props.item.discount_amount): 0 }}
                            </td>
                            <td class="text-xs-center">TK. {{ props.item.paid ? price_format(props.item.paid): 0 }}</td>
                            <td class="text-xs-center">TK. {{ props.item.payment_due?
                                price_format(props.item.payment_due): 0 }}
                            </td>
                            <td class="text-xs-center">TK. {{ props.item.total? price_format(props.item.total): 0 }}
                            </td>
                        </tr>
                    </template>

                    <template slot="expand" slot-scope="props">
                        <v-card flat>
                            <v-card-text>
                                <table style="width:100%">
                                    <thead>
                                    <th>Product name</th>
                                    <th>Product Description</th>
                                    <th>Product Sale price</th>
                                    </thead>
                                    <tr v-for="(product, index) in props.item.products" :key="index">
                                        <td class="text-xs-center">{{ product.name }}</td>
                                        <td class="text-xs-center">{{ product.description }}</td>
                                        <td class="text-xs-center">TK. {{ product.sale_price }}</td>
                                    </tr>
                                </table>
                            </v-card-text>
                        </v-card>
                    </template>

                    <v-alert slot="no-results" :value="true" color="error" icon="warning">
                        Your search for "{{ search }}" found no results.
                    </v-alert>

                    <template slot="no-data">
                        Sorry this customer do not have transition.
                    </template>

                    <template slot="footer">
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-xs-right"><strong>Total Transactions:</strong></td>
                            <td class="text-xs-left"><strong>{{ totalTransaction }}</strong></td>
                            <td class="text-xs-right"><strong>Total due</strong></td>
                            <td class="text-xs-right"><strong>TK. {{ due }}</strong></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-xs-right"><strong>Grand total</strong></td>
                            <td class="text-xs-right"><strong>TK. {{ total }}</strong></td>
                        </tr>
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
                    text: 'Date',
                    value: 'created_at',
                    sortable: true
                },

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
                items: 'getAllTransaction',
                totalTransaction: 'getTotalTransaction',
                total: 'getCustomerTransactionTotal',
                due: 'getCustomerTransactionDue'
            })
        },

        watch: {},

        created() {
        },

        methods: {
            changeSort(column) {
                if (this.pagination.sortBy === column) {
                    this.pagination.descending = !this.pagination.descending
                } else {
                    this.pagination.sortBy = column
                    this.pagination.descending = false
                }
            },

            price_format(val) {
                return val.toFixed(2).replace(/./g, function (c, i, a) {
                    return i && c !== "." && ((a.length - i) % 3 === 0) ? ',' + c : c;
                });
            },

            getPaymentStatus(value) {
                if (value === 1) {
                    return 'Paid'
                }
                if (value === 2) {
                    return 'Due'
                }

                if (value === 3) {
                    return 'Half paid'
                }
            }
        },

        destroyed() {
            this.$store.commit('setResetAllCustomerTransactionData');
        }
    }
</script>
