<template>
    <div class="products">

        <v-dialog
                v-model="barcodeDialog"
                persistent
                max-width="290">
            <v-card>
                <v-card-title class="headline">You scanned a Barcode</v-card-title>
                <v-card-text>Your barcode is : {{ barcode }}</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="green darken-1" flat @click.native="onbarcodeDialogClose()">Ok</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog
                width="800"
                v-model="dialog"
                persistent>
            <v-card class="px-2 py-2">
                <v-card-title>
                    <span class="headline">{{ formTitle }}</span>
                </v-card-title>

                <v-card-text>
                    <v-form ref="service_form"
                            v-model="valid"
                            lazy-validation>
                        <v-container fluid grid-list-md>
                            <v-layout row wrap>
                                <v-flex xs6>
                                    <v-text-field
                                            label="Brand"
                                            v-model="editedItem.brand"
                                            dark
                                            color="dark"
                                            :rules="[v => !!v || 'Brand is required']"
                                            required
                                    ></v-text-field>
                                </v-flex>

                                <v-flex xs6>
                                    <v-text-field
                                            label="Problem"
                                            v-model="editedItem.problem"
                                            dark
                                            color="dark"
                                            :rules="[v => !!v || 'Problem is required']"
                                            required
                                    ></v-text-field>
                                </v-flex>

                                <v-flex xs12>
                                    <v-textarea
                                            dark
                                            color="dark"
                                            label="Description"
                                            v-model="editedItem.description"
                                            :rules="[v => !!v || 'Description is required']"
                                            required
                                    ></v-textarea>
                                </v-flex>

                                <v-flex xs6>
                                    <v-autocomplete
                                            dark
                                            color="dark"
                                            :items="customers"
                                            v-model="editedItem.customer"
                                            item-text="name"
                                            return-object
                                    >
                                    </v-autocomplete>
                                </v-flex>

                                <v-flex xs6>
                                    <v-text-field
                                            label="Service Charge"
                                            v-model="editedItem.service_charge"
                                            dark
                                            color="dark"
                                            :rules="[v => !!v || 'Title is required']"
                                            required
                                    ></v-text-field>
                                </v-flex>

                                <v-flex xs6>
                                    <v-select
                                            dark
                                            color="dark"
                                            :items="status"
                                            v-model="editedItem.status"
                                            label="Status"
                                            required
                                            :rules="[v => !!v || 'Status is required']"
                                            menu-props="auto"
                                    ></v-select>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-form>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                            dark
                            color="dark"
                            raised
                            @click.native="close"
                    >Cancel
                    </v-btn>

                    <v-btn dark
                           color="dark"
                           raised
                           :disabled="!valid"
                           @click.native="save">{{ editedIndex === -1 ? 'Create Service' :
                        'Update Service' }}
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-container grid-list-md class="pt-0">
            <v-layout row wrap>
                <v-flex xs12 class="pt-0">
                    <h2>Services</h2>
                </v-flex>
            </v-layout>

            <v-divider class="mb-3 dark"></v-divider>

            <v-layout row wrap>
                <v-flex xs12>
                    <v-card flat class="cyan lighten-1 white--text">
                        <v-card-title>Total Service</v-card-title>
                        <v-card-text class="pt-0">
                            <h2 class="display-2 white--text text-xs-center">
                                <strong>{{total_count}}</strong>
                            </h2>
                        </v-card-text>
                    </v-card>
                </v-flex>

                <v-flex xs6>
                    <v-card flat class="light-blue white--text">
                        <v-card-title>Due</v-card-title>
                        <v-card-text class="pt-0">
                            <h2 class="display-2 white--text text-xs-center">
                                <span style="font-size:12px"></span>
                                <strong>{{due}}</strong>
                            </h2>
                        </v-card-text>
                    </v-card>
                </v-flex>

                <v-flex xs6>
                    <v-card flat class="light-green lighten-1 white--text">
                        <v-card-title>Due Amount</v-card-title>
                        <v-card-text class="pt-0">
                            <h2 class="display-2 white--text text-xs-center">
                                <span style="font-size:12px">TK.</span>
                                <strong>{{due_amount}}</strong>
                            </h2>
                        </v-card-text>
                    </v-card>
                </v-flex>

                <v-flex xs6>
                    <v-card flat class="orange darken-1 white--text">
                        <v-card-title>Paid</v-card-title>
                        <v-card-text class="pt-0">
                            <h2 class="display-2 white--text text-xs-center">
                                <span style="font-size:12px"></span>
                                <strong>{{paid}}</strong>
                            </h2>
                        </v-card-text>
                    </v-card>
                </v-flex>

                <v-flex xs6>
                    <v-card flat class="cyan lighten-1 white--text">
                        <v-card-title>Paid Amount</v-card-title>
                        <v-card-text class="pt-0">
                            <h2 class="display-2 white--text text-xs-center">
                                <span style="font-size:12px">TK.</span>
                                <strong>{{paid_amount}}</strong>
                            </h2>
                        </v-card-text>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>

        <v-container grid-list-md>
            <v-layout row wrap>
                <v-card width="100%">
                    <v-card-title>
                        <v-btn dark fab small color="dark" @click="dialog = true">
                            <v-icon>add</v-icon>
                        </v-btn>

                        <v-spacer></v-spacer>
                        <v-text-field
                                dark
                                color="dark"
                                prepend-icon="search"
                                label="Search by problem"
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

                            <template slot="items" slot-scope="props">
                                <tr @click="props.expanded = !props.expanded">
                                    <td class="">{{ props.item.brand }}</td>
                                    <td class="">{{ props.item.problem }}</td>
                                    <td>{{props.item.customer && props.item.customer.name }}</td>
                                    <td class="">TK. {{ props.item.service_charge }}</td>
                                    <td class="">{{ props.item.status }}</td>
                                    <td>{{ props.item.created_at | convertDate }}</td>
                                    <td class="justify-start layout px-0">
                                        <v-btn dark
                                               color="dark"
                                               icon
                                               class="mx-0"
                                               @click="editItem(props.item)">
                                            <v-icon color="white">edit</v-icon>
                                        </v-btn>
                                        <v-btn icon
                                               dark
                                               color="dark"
                                               class="mx-0"
                                               @click="openDeleteDialog(props.item)">
                                            <v-icon color="white">delete</v-icon>
                                        </v-btn>
                                    </td>
                                </tr>
                            </template>

                            <v-divider></v-divider>

                            <template slot="expand" slot-scope="props">
                                <v-card flat>
                                    <v-card-text>
                                        <v-layout>
                                            <v-flex xs6>
                                                <table width="100%" class="datatable table">
                                                    <tr>
                                                        <td class="title">Created Time: {{props.item.created_at}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Brand: {{props.item.brand}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Problem: {{props.item.problem}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Service charge: {{props.item.service_charge}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>status: {{props.item.status}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">Description: {{props.item.description}}</td>
                                                    </tr>
                                                </table>
                                            </v-flex>

                                            <v-flex xs6>
                                                <table width="100%" class="datatable table">
                                                    <tr>
                                                        <td class="title">Customer</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Name : {{props.item.customer && props.item.customer.name}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Email : {{props.item.customer &&
                                                            props.item.customer.email}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Mobile : {{props.item.customer &&
                                                            props.item.customer.phone}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Phone : {{props.item.customer &&
                                                            props.item.customer.mobile}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Address : {{props.item.customer &&
                                                            props.item.customer.address}}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </v-flex>
                                        </v-layout>
                                    </v-card-text>
                                </v-card>
                            </template>

                            <v-alert slot="no-results" :value="true" color="error" icon="warning">
                                Your search for "{{ search }}" found no results.
                            </v-alert>

                            <template slot="no-data">
                                Sorry no products found
                            </template>
                        </v-data-table>
                    </v-card-text>
                </v-card>
            </v-layout>
        </v-container>

        <v-snackbar
                :timeout="4000"
                top
                right
                color="success"
                multi-line
                v-model="snackbar">
            {{ snackbar_message }}
        </v-snackbar>

        <v-dialog v-model="deleteDialog" persistent max-width="290">
            <v-card color="error">
                <v-card-text>
                    <div class="text-xs-center">
                        <v-icon color="white" size="50">warning</v-icon>
                    </div>
                    <p class="text-xs-center">Are you sure you want to delete {{deleteItem.title}} {{
                        deleteItem.description}}</p>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="dark darken-1" flat @click.native="deleteDialog = false">Disagree</v-btn>
                    <v-btn color="dark darken-1" flat @click.native="deleteItemD()">Agree</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
    export default {
        data: () => ({
            dialog: false,
            search: '',
            pagination: {
                sortBy: 'brand'
            },

            total_count: 0,
            due: 0,
            due_amount: 0,
            paid: 0,
            paid_amount: 0,

            customers: [],

            deleteDialog: false,
            deleteItem: {},

            snackbar: false,
            snackbar_message: '',

            warranties: ['3 Month', '6 Month', '1 Year', '1.5 Year', '2 Year', '3 Year', '4 year', '5 year'],

            headers: [
                {
                    text: 'Brand',
                    value: 'brand',
                    sortable: true
                },

                {
                    text: 'Problem',
                    value: 'problem',
                    sortable: true
                },

                {
                    text: 'Customer',
                    value: 'customer',
                    sortable: true
                },
                {
                    text: 'Service Charge',
                    value: 'service_charge',
                    sortable: true
                },
                {
                    text: 'Status',
                    value: 'status',
                    sortable: true
                },
                {
                    text: 'Date',
                    align: 'left',
                    sortable: true,
                    value: 'created_at'
                },
                {
                    text: 'Action',
                    value: 'action'
                },
            ],

            items: [],
            status: [
                {
                    text: 'Due',
                    value: 'due'
                },
                {
                    text: 'Paid',
                    value: 'paid'
                }
            ],
            editedIndex: -1,
            editedItem: {
                id: '',
                brand: '',
                problem: '',
                description: '',
                service_charge: 0,
                status: '',
                customer: {}
            },

            update_form: false,

            defaultItem: {
                id: '',
                brand: '',
                problem: '',
                description: '',
                service_charge: 0,
                status: '',
                created_at: '',
                customer: {}
            },
            row_per_page: [20, 30, 50, {'text': 'All', 'value': -1}],

            valid: true,

            barcodeDialogvalue: false,
            barcode: ''

        }),

        computed: {
            formTitle() {
                return this.editedIndex === -1 ? 'New Service' : 'Edit Service'
            },

            barcodeDialog() {
                return this.barcodeDialogvalue;
            }
        },

        watch: {
            dialog(val) {
                val || this.close()
            }
        },

        created() {
            this.initialize();
        },

        methods: {
            initialize() {
                // get all product
                axios.get('/api/services')
                    .then((response) => {
                        this.items = [...response.data.services];
                        this.customers = [...response.data.customers];
                        this.total_count = response.data.total_count;
                        this.due = response.data.due;
                        this.due_amount = response.data.due_amount;
                        this.paid = response.data.paid;
                        this.paid_amount = response.data.paid_amount;
                    })
                    .catch((error) => {
                        console.log(error)
                    })
            },

            openDeleteDialog(deleteItem) {
                this.deleteItem = deleteItem;
                this.deleteDialog = true;
            },

            editItem(item) {
                this.editedIndex = this.items.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true
            },

            deleteItemD() {
                let url = 'api/services/' + this.deleteItem.id
                axios.delete(url).then((response) => {
                    this.deleteDialog = false;
                    const index = this.items.indexOf(this.deleteItem)
                    this.items.splice(index, 1)
                    this.snackbar_message = 'You successfully delete ' + this.deleteItem.brand;
                    this.snackbar = true;
                    this.initialize();
                });
            },

            close() {
                this.dialog = false;
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem);
                    this.editedIndex = -1;
                }, 300)
            },


            save() {
                if (!this.$refs.service_form.validate()) {
                    return;
                }

                let form = new FormData();
                let url = '/api/services';

                form.append('brand', this.editedItem.brand);
                form.append('problem', this.editedItem.problem);
                form.append('description', this.editedItem.description);
                form.append('service_charge', this.editedItem.service_charge);
                this.editedItem.customer && form.append('customer_id', this.editedItem.customer.id);
                form.append('status', this.editedItem.status);

                if (this.editedIndex > -1) {
                    // update product
                    form.append('_method', 'PATCH')
                    url = url + '/' + this.editedItem.id;
                    axios.post(url, form)
                        .then((response) => {
                            Object.assign(this.items[this.editedIndex], this.editedItem);
                            this.snackbar_message = 'Service ' + this.editedItem.brand + ' successfully updated.';
                            this.snackbar = true;
                            this.close()
                            this.initialize();
                            this.$refs.service_form.reset();
                        })
                } else {
                    // create product
                    axios.post(url, form)
                        .then((response) => {
                            this.items.push(response.data);
                            this.snackbar_message = 'Service ' + this.editedItem.brand + ' successfully created.';
                            this.snackbar = true;
                            // update total product & stock
                            this.total_product++;

                            // let total = this.total_stock.replace(',', '');
                            // total = Number(total);
                            // this.total_stock = total + this.editedItem.quantity * this.editedItem.purchase_price;
                            this.close()
                            this.initialize();
                            this.$refs.service_form.reset();
                        })
                }

            },

            changeSort(column) {
                if (this.pagination.sortBy === column) {
                    this.pagination.descending = !this.pagination.descending
                } else {
                    this.pagination.sortBy = column
                    this.pagination.descending = false
                }
            },

            customFilter(items, search, filter) {
                search = search.toString().toLowerCase();
                if (search === '') {
                    return items;
                }
                let filterItem = [];
                items.forEach((item) => {
                    let itemSerial = false;
                    let itemName = item.brand.toString().toLowerCase();
                    if (itemName.includes(search)) {
                        itemSerial = true
                    }

                    if (itemSerial) {
                        filterItem.push(item);
                    }
                })
                return filterItem;
            },


            onBarcodeScanned(code) {
                console.log(code);
                console.log('barcode scanned');
                this.barcodeDailog = true;
                this.barcode = code;
                this.barcodeDialogvalue = true;
                if (code !== '') {
                    console.log('It is hiting');
                    console.log(this.barcode);
                    this.barcodeDailog = true;
                    this.barcode = code;
                }
            },

            openDialog() {
                this.barcodeDialogvalue = true;
            },

            onbarcodeDialogClose() {
                this.barcodeDialogvalue = false;
                this.code = '';
            },
        }
    }
</script>

<style>
    .products table.table thead th:first-child {
        padding: 0 15px;
    }

</style>
