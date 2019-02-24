<template>
    <div class="products">
        <!--<v-dialog-->
        <!--v-model="barcodeDialog"-->
        <!--persistent-->
        <!--max-width="290">-->
        <!--<v-card>-->
        <!--<v-card-title class="headline">You scanned a Barcode</v-card-title>-->
        <!--<v-card-text>Your barcode is : {{ barcode }}</v-card-text>-->
        <!--<v-card-actions>-->
        <!--<v-spacer></v-spacer>-->
        <!--<v-btn color="green darken-1" flat @click.native="onbarcodeDialogClose()">Ok</v-btn>-->
        <!--</v-card-actions>-->
        <!--</v-card>-->
        <!--</v-dialog>-->

        <v-container grid-list-md class="pt-0">
            <v-layout row wrap>
                <v-flex xs12 class="pt-0">
                    <h2>Products</h2>
                </v-flex>
            </v-layout>

            <v-divider class="mb-3 dark"></v-divider>

            <v-layout row wrap>
                <v-flex xs6>
                    <v-card flat class="cyan lighten-1 white--text">
                        <v-card-title>Total product</v-card-title>
                        <v-card-text class="pt-0">
                            <h2 class="display-2 white--text text-xs-center">
                                <strong>{{total_product}}</strong>
                            </h2>
                        </v-card-text>
                    </v-card>
                </v-flex>

                <v-flex xs6>
                    <v-card flat class="light-blue white--text">
                        <v-card-title>Product Available</v-card-title>
                        <v-card-text class="pt-0">
                            <h2 class="display-2 white--text text-xs-center">
                                <strong>{{avaliable_product}}</strong>
                            </h2>
                        </v-card-text>
                    </v-card>
                </v-flex>

                <v-flex xs6>
                    <v-card flat class="light-green lighten-1 white--text">
                        <v-card-title>Product Unavailable</v-card-title>
                        <v-card-text class="pt-0">
                            <h2 class="display-2 white--text text-xs-center">
                                <strong>{{unavaliable_product}}</strong>
                            </h2>
                        </v-card-text>
                    </v-card>
                </v-flex>

                <v-flex xs6>
                    <v-card flat class="orange darken-1 white--text">
                        <v-card-title>Total Stock</v-card-title>
                        <v-card-text class="pt-0">
                            <h2 class="display-2 white--text text-xs-center">
                                <span style="font-size:12px">TK.</span>
                                <strong>{{total_stock}}</strong>
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
                        <v-btn dark fab small color="dark" @click="$router.push('products/add')">
                            <v-icon>add</v-icon>
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

                            <template slot="items" slot-scope="props">
                                <tr @click="props.expanded = !props.expanded">
                                    <td>{{ props.item.created_at | convertDate }}</td>
                                    <td>{{ props.item.name }}</td>
                                    <td>{{ props.item.quantity }}</td>
                                    <!--<td class="text-xs-center">TK. {{ props.item.sale_price }}</td>-->
                                    <td>TK. {{ props.item.sale_price }}</td>
                                    <td>{{ props.item.status }}</td>
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
                                        <table width="100%">
                                            <tr>
                                                <td><strong>Additional info</strong></td>
                                            </tr>

                                            <tr v-for="(serial, index) in props.item.serials" :key="index">
                                                <td>
                                                    Barcode: {{ serial.barcode }}
                                                </td>
                                                <td>
                                                    IMEI: {{ serial.imei }}
                                                </td>
                                                <td>
                                                    Color: {{ serial.color }}
                                                </td>
                                            </tr>
                                        </table>

                                        <v-divider></v-divider>

                                        <table with="100%">
                                            <tr>
                                                <td><strong>Companies</strong></td>
                                                <td><strong>Mobile</strong></td>
                                                <td><strong>Phone</strong></td>
                                                <td><strong>Quantity</strong></td>
                                                <td><strong>Purchased date</strong></td>
                                            </tr>
                                            <tr v-for="(company, index) in props.item.companies" :key="index">
                                                <td>{{ company.name}}</td>
                                                <td>{{ company.mobile }}</td>
                                                <td>{{ company.phone }}</td>
                                                <td>{{ company.pivot.product_quantity }}</td>
                                                <td>{{ company.pivot.created_at | convertDate }}</td>
                                            </tr>
                                        </table>

                                        <p>Purchase price : {{ props.item.purchase_price }}</p>
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
    import {mapGetters} from 'vuex'

    export default {
        data: () => ({
            search: '',
            pagination: {
                sortBy: 'name'
            },

            avaliable_product: 0,
            unavaliable_product: 0,
            total_product: 0,
            total_stock: 0,

            deleteDialog: false,
            deleteItem: {},


            snackbar: false,
            snackbar_message: '',

            warranties: ['3 Month', '6 Month', '1 Year', '1.5 Year', '2 Year', '3 Year', '4 year', '5 year'],

            quantityToFeetError: false,
            quantityToFeet: 0,
            totalFeets: 0,

            headers: [
                {
                    text: 'Date',
                    align: 'left',
                    sortable: true,
                    value: 'created_at'
                },

                {
                    text: 'Title',
                    value: 'name',
                    sortable: true
                },

                {
                    text: 'Quantity',
                    value: 'quantity',
                    sortable: true
                },
                {
                    text: 'Price',
                    value: 'sale_price',
                    sortable: true
                },
                {
                    text: 'Status',
                    value: 'status',
                    sortable: true
                },
                {
                    text: 'Action',
                    value: 'action'
                }
            ],

            items: [],
            row_per_page: [20, 30, 50, {'text': 'All', 'value': -1}],

            barcodeDialogvalue: false,
            barcode: ''

        }),

        computed: {
            ...mapGetters({
                selectedShop: 'getSelectedShop'
            }),
        },

        watch: {
            selectedShop() {
                this.initialize();
            },

            dialog(val) {
                val || this.close()
            },

        },

        created() {
            this.initialize();
            //Barcode scannser
            this.$barcodeScanner.init(this.onBarcodeScanned);
        },

        methods: {
            initialize() {
                const shopId = this.selectedShop.id
                let productsUrl = '/api/products'
                if (shopId) {
                    productsUrl += '?shopId=' + shopId + '&allSerial=true'
                }

                // get all product
                axios.get(productsUrl)
                    .then((response) => {
                        this.items = response.data.products;
                        this.quantity_type = response.data.quantity_types;
                        this.total_product = response.data.total_product;
                        this.avaliable_product = response.data.avaliable_product;
                        this.unavaliable_product = response.data.unavaliable_product ? response.data.unavaliable_product : 0;
                        this.total_stock = response.data.total_stock;
                    })
                    .catch((error) => {
                        console.log(error)
                    });
            },

            openDeleteDialog(deleteItem) {
                this.deleteItem = deleteItem;
                this.deleteDialog = true;
            },

            deleteItemD() {
                let url = 'api/products/' + this.deleteItem.id
                axios.delete(url).then((response) => {
                    this.deleteDialog = false;
                    const index = this.items.indexOf(this.deleteItem)
                    this.items.splice(index, 1)
                    this.snackbar_message = 'You successfully delete ' + this.deleteItem.name;
                    this.snackbar = true;
                    this.initialize();
                });
            },

            close() {
                this.dialog = false;
                this.selectedCategories = [];
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem);
                    this.editedIndex = -1;
                }, 300)
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
                search = search && search.toString().toLowerCase();
                if (search === '') {
                    return items;
                }
                let filterItem = [];
                items.forEach((item) => {
                    let isItem = false;
                    let itemSerial = false;
                    if (item.serials.length > 0) {
                        item.serials.forEach((serial) => {
                            let productSerial = serial.barcode.toString().toLowerCase();
                            if (productSerial.includes(search)) {
                                isItem = true;
                            }
                        })

                    }

                    let itemName = item.name.toString().toLowerCase();
                    if (itemName.includes(search)) {
                        itemSerial = true
                    }

                    if (isItem || itemSerial) {
                        filterItem.push(item);
                    }
                })
                return filterItem;

            }
        }
    }
</script>

<style>
    .products table.table thead th:first-child {
        padding: 0 15px;
    }

</style>
