<template>
    <div class="transaction-component">
        <v-container grid-list-md>
            <v-layout row wrap>
                <v-flex xs12>
                    <h2>Transition</h2>
                </v-flex>
            </v-layout>

            <v-divider class="mb-3 dark"></v-divider>

            <v-layout row wrap>
                <v-flex xs6>
                    <v-card flat class="cyan lighten-1 white--text">
                        <v-card-title>Total Transactions</v-card-title>
                        <v-card-text class="pt-0">
                            <h2 class="display-2 white--text text-xs-center"><strong>{{ total_transactions }}</strong>
                            </h2>
                        </v-card-text>
                    </v-card>
                </v-flex>

                <v-flex xs6>
                    <v-card flat class="light-blue white--text">
                        <v-card-title>Total Amount Transactions</v-card-title>
                        <v-card-text class="pt-0">
                            <h2 class="display-2 white--text text-xs-center"><strong>&#2547;
                                {{ price_format(total_amount_transactions) }}</strong></h2>
                        </v-card-text>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>


        <v-dialog v-model="deleteDialog" persistent max-width="290">
            <v-card color="error">
                <v-card-text>
                    <div class="text-xs-center"><v-icon color="white" size="50">warning</v-icon></div>
                    <p class="text-xs-center">Are you sure you want to delete {{deleteItem.title}} {{ deleteItem.description}}</p>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="dark darken-1" flat @click.native="deleteDialog = false">Disagree</v-btn>
                    <v-btn color="dark darken-1" flat @click.native="deleteItemD()">Agree</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-container grid-list-lg>
            <v-layout row wrap>
                <v-card
                        raised
                        width="100%">
                    <v-card-title class="pb-0 pt-0">
                        <v-btn
                                :disabled="!createTransaction"
                                dark
                                small
                                color="dark"
                                @click="onGotoCreateTransaction('create')">
                            Create new Transaction
                        </v-btn>
                        <p class="red--text" v-if="!createTransaction">To make a transaction please first make a customer & product</p>

                        <v-btn
                                small
                                :disabled="!createTransaction"
                                dark
                                color="dark"
                                @click="onDueTransaction()">
                            Crate Due Transaction
                        </v-btn>

                        <v-spacer></v-spacer>
                        <v-text-field
                                dark
                                color="dark"
                                prepend-icon="search"
                                label="Search"
                                v-model="search"></v-text-field>
                    </v-card-title>

                    <v-card-text>
                        <v-data-table
                                :headers="headers"
                                :items="items"
                                :search="search"
                                :pagination.sync="pagination"
                                :rows-per-page-items="row_per_page"
                                :custom-filter="customFilter"
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
                                    <td class="text-xs-center">{{ props.item.customer.name }}</td>
                                    <td class="text-xs-center">{{ props.item.invoice_number.toUpperCase() }}</td>
                                    <td class="text-xs-center">{{ props.item.products.length ? props.item.products.length : 0 }}</td>
                                    <td class="text-xs-center">{{ getPaymentStatus(props.item.payment_status) }}</td>
                                    <td class="text-xs-center">TK. {{ props.item.discount_amount? price_format(props.item.discount_amount): 0 }}</td>
                                    <td class="text-xs-center">TK. {{ props.item.paid ? price_format(props.item.paid): 0 }}</td>
                                    <td class="text-xs-center">TK. {{ props.item.payment_due? price_format(props.item.payment_due): 0 }}</td>
                                    <td class="text-xs-center">TK. {{ props.item.service_charge? price_format(props.item.service_charge): 0 }}</td>
                                    <td class="text-xs-center">TK. {{ props.item.total? price_format(props.item.total): 0 }}</td>
                                    <td class="justify-start layout px-0">
                                        <!--<v-btn icon class="mx-0" @click="editItem(props.item)">-->
                                            <!--<v-icon color="dark">edit</v-icon>-->
                                        <!--</v-btn>-->

                                        <v-btn icon class="mx-0" @click="viewTransition(props.item)">
                                            <v-icon clor="dark">view_comfy</v-icon>
                                        </v-btn>

                                        <v-btn icon class="mx-0" @click="openDeleteDialog(props.item)">
                                            <v-icon color="dark">delete</v-icon>
                                        </v-btn>

                                        <v-btn icon class="mx-0" @click="onPrintTransaction(props.item)">
                                            <v-icon color="dark">print</v-icon>
                                        </v-btn>
                                    </td>
                                </tr>
                            </template>

                            <template slot="expand" slot-scope="props">
                                <v-card flat>
                                    <v-card-text>
                                        <h3>Serials: </h3>
                                        <ul>
                                            <li v-for="(serial, index) in props.item.serials" :key="index">{{ serial.product_serial }}</li>
                                        </ul>
                                    </v-card-text>
                                </v-card>
                            </template>

                            <v-alert slot="no-results" :value="true" color="error" icon="warning">
                                Your search for "{{ search }}" found no results.
                            </v-alert>

                            <template slot="no-data">
                                Sorry no transition found
                            </template>
                        </v-data-table>
                    </v-card-text>
                </v-card>
            </v-layout>
        </v-container>
    </div>
</template>
<script>
    /* eslint-disable no-unreachable */

    import axios from 'axios'

    export default {
        data: () => ({
            dialog: false,
            total_transactions: 0,
            total_amount_transactions: 0,
            deleteDialog:false,
            deleteItem:{},
            companyExists : true,
            productExists: true,

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
                    text: 'C Name',
                    value: 'customer.name',
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
                    text: 'Service charge',
                    value: 'service_charge',
                    sortable: true
                },

                {
                    text: 'Total',
                    value: 'total',
                    sortable: true
                },

                {
                    text: 'Actions'
                }
            ],
            total_customer: '',
            items: [],
            products: [],
            allProductData: [],
            current_product_quantity: '',
            selectedProduct: [],
            customers: [],
            selectedCustomer:[],
            payment_due:'',
            paid:'',

            editedIndex: -1,
            editedItem: {
                id: '',
                name: 'New title',
                email: 'new Description',
                quantity: '',
                active: '1',

            },
            paymentStatus:[{text: 'Paid', value: 1}, {text: 'Due', value:2}, {text: 'Half paid', value:3}],
            selectedPaymentStatus:1,
            active: [1, 2],


            defaultItem: {
                name: '',
                descriptin: ''
            },
            row_per_page: [20, 30, 40, 50, {'text': 'All', 'value': -1}],

        }),

        computed: {
            formTitle() {
                return this.editedIndex === -1 ? 'New Transaction' : 'Edit Transaction'
            },

            createTransaction(){
                return this.companyExists && this.productExists ? true : false;
            }
        },

        watch: {
            dialog(val) {
                val || this.close()
            },

            selectedProduct(val) {
                var change_product = '';
                this.allProductData.forEach(function(product) {
                    if(val === product.id){

                        change_product =  product;
                    }
                });
                this.current_product_quantity = change_product.quantity;
            },

            selectedPaymentStatus(selectedValue){
            }
        },

        created() {
            this.initialize()
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

                //get all product
                axios.get('/api/products')
                    .then((response) => {
                        if(response.data.products.length > 0){
                            this.products = response.data.products;
                            this.allProductData = response.data.products;
                            var array_products = [];
                            this.products.forEach((product)=> {
                                var product = { text: product.name, value : product.id};
                                array_products.push(product);
                            })
                            this.products = array_products;
                        }else{
                            this.productExists = false;
                        }
                    })
                    .catch((error) => {
                        console.log(error)
                    });


                // get all customers
                axios.get('/customers')
                    .then((response) => {
                        if(response.data.length > 0){
                            this.customers = response.data;
                            var array_customer = [];
                            this.customers.forEach((customer)=> {
                                var customer = { text: customer.name, value : customer.id};
                                array_customer.push(customer);
                            })
                            this.customers = array_customer;
                        }else{
                            this.companyExists = false
                        }

                    })
                    .catch((error) => {
                        console.log(error)
                    });

            },

            openDeleteDialog(deleteItem){
                this.deleteItem = deleteItem;
                this.deleteDialog = true;
            },

            deleteItemD () {
                let url = 'api/transactions/'+this.deleteItem.id+'/delete ';
                axios.get(url).then((response) => {
                    this.deleteDialog = false;
                    const index = this.items.indexOf(this.deleteItem)
                    this.items.splice(index, 1)
                });
            },


            changeSort(column) {
                if (this.pagination.sortBy === column) {
                    this.pagination.descending = !this.pagination.descending
                } else {
                    this.pagination.sortBy = column
                    this.pagination.descending = false
                }
            },

            viewTransition(){

            },

            price_format(val){
                return val.toFixed(2).replace(/./g, function(c, i, a) {
                    return i && c !== "." && ((a.length - i) % 3 === 0) ? ',' + c : c;
                });
            },

            onGotoCreateTransaction(value){
                this.$router.push({name: 'create_transaction', params: {type: value}});
            },

            onPrintTransaction(item){
                this.$router.push({name: 'print_transaction', params: { id: item.id}});
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
            },

            onDueTransaction(){
                this.$router.push({name: 'create_due_transaction'});
            },

            customFilter(items, search, filter) {
                search = search.toString().toLowerCase();

                if(search === ''){
                    return items;
                }
                let filterItem = [];
                items.forEach((item)=>{
                    let isSerial = false;
                    let transaction = false;
                    if(item.serials.length > 0){
                        item.serials.forEach((serial)=>{
                            let itemSerial = serial.product_serial.toString().toLowerCase();
                            if(itemSerial.includes(search)){
                                isSerial = true;
                            }
                        })
                    }

                    if(item.invoice_number.toString().toLowerCase().includes(search)){
                        transaction = true;
                    }

                    if(isSerial || transaction){
                        filterItem.push(item);
                    }
                })
                return filterItem;

            }
        }
    }
</script>
