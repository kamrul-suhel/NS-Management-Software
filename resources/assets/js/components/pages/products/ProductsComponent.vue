<template>
    <div class="products">

        <v-dialog
                v-model="dialog"
                persistent>
            <v-card class="px-2 py-2">
                <v-card-title>
                    <span class="headline">{{ formTitle }}</span>
                </v-card-title>

                <v-card-text>
                    <v-form ref="product_form"
                            v-model="valid"
                            lazy-validation>
                        <v-container fluid grid-list-md>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-text-field
                                            label="Title"
                                            v-model="editedItem.name"
                                            dark
                                            color="dark"
                                            :rules="[v => !!v || 'Title is required']"
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

                                <v-flex xs12>
                                    <v-select
                                            dark
                                            color="dark"
                                            label="Is this product has serial"
                                            :items="isSerials"
                                            required
                                            :rules="[v => !!v || 'This field required']"
                                            v-model="isSerial"></v-select>
                                </v-flex>

                                <v-flex xs12
                                        v-for="(company, totalCompanyIndex) in totalCompanies"
                                        :key="totalCompanyIndex">
                                    <v-layout row wrap
                                    >
                                        <v-flex xs6>
                                            <v-select
                                                    dark
                                                    color="dark"
                                                    label="Which company"
                                                    :items="company.companies"
                                                    v-model="company.selectedCompany"
                                                    item-text="name"
                                                    item-value="id"
                                                    required
                                                    :reles="[v => !!v || 'Select A company']"
                                                    return-object
                                            ></v-select>
                                        </v-flex>

                                        <v-flex xs6>
                                            <v-text-field
                                                    label="How many quantity"
                                                    dark
                                                    v-model="company.quantity"
                                                    required
                                                    :reles="[v => !!v || 'Quantity is required' ]"
                                                    color="dark">
                                            </v-text-field>

                                            <v-btn
                                                    right
                                                    fab
                                                    dark
                                                    small
                                                    color="error"
                                                    style="width:20px;height:20px;position:absolute"
                                                    @click="onRemoveCompany(totalCompanyIndex)"
                                            >
                                                <v-icon>remove</v-icon>
                                            </v-btn>
                                        </v-flex>
                                        <v-layout row wrap v-if="isSerial">
                                            <v-flex xs6>
                                                <v-autocomplete
                                                        dark
                                                        color="white"
                                                        label="Select warranty"
                                                        v-model="company.product_warranty"
                                                        :items="warranties"
                                                ></v-autocomplete>
                                                <span class="red--text"
                                                      v-if="productWarrantyError">Please select warranty</span>
                                            </v-flex>

                                            <v-flex xs6>
                                                <v-layout row wrap>
                                                    <v-flex xs3
                                                            v-for="(serial, index) in company.serials"
                                                            :key="index">
                                                        <v-text-field
                                                                dark
                                                                color="dark"
                                                                :label="'Product Serial ' +  (Number(index) + 1)"
                                                                v-model="company.serials[index]"
                                                        ></v-text-field>
                                                    </v-flex>
                                                </v-layout>
                                            </v-flex>
                                        </v-layout>
                                    </v-layout>
                                </v-flex>


                                <v-flex xs12>
                                    <v-btn
                                            dark
                                            color="dark"
                                            class="ml-0"
                                            @click="onAddCompany()">Add company
                                    </v-btn>
                                </v-flex>

                                <v-flex xs6>
                                    <v-text-field
                                            label="Quantity"
                                            type="number"
                                            dark
                                            required
                                            :rules="[v => v >! 0 || 'Please Select A company']"
                                            color="dark"
                                            placeholder="00.00"
                                            disabled
                                            v-model="editedItem.quantity"
                                    ></v-text-field>
                                </v-flex>

                                <v-flex xs6>
                                    <v-select
                                            dark
                                            color="dark"
                                            label="Quantity type"
                                            :items="quantity_type"
                                            v-model="editedItem.quantity_type"
                                            required
                                            :rules="[v => !!v || 'Quantity type is required']"
                                            auto
                                    ></v-select>
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
                                            auto
                                    ></v-select>
                                </v-flex>

                                <v-flex xs6>
                                    <v-text-field
                                            dark
                                            color="dark"
                                            label="Sale Price"
                                            type="number"
                                            placeholder="00.00"
                                            prefix="TK"
                                            required
                                            :rules="[v => !!v  || 'Sale price is required']"
                                            v-model="editedItem.sale_price"
                                    ></v-text-field>
                                </v-flex>

                                <v-flex xs6>
                                    <v-text-field
                                            dark
                                            color="dark"
                                            label="Purchase price"
                                            type="number"
                                            placeholder="00.00"
                                            prefix="TK"
                                            required
                                            :rules="[v => !!v || 'Purchase price is required']"
                                            v-model="editedItem.purchase_price">
                                    </v-text-field>
                                </v-flex>

                                <v-flex xs6>
                                    <v-select
                                            dark
                                            color="dark"
                                            label="Categories"
                                            :items="categories"
                                            v-model="selectedCategories"
                                            multiple
                                            chips
                                            required
                                            :rules="[v => !!v || 'Please select category']"
                                            persistent-hint
                                            return-object
                                    >
                                    </v-select>
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
                    >Cancel</v-btn>

                    <v-btn dark
                           color="dark"
                           raised
                           :disabled="!valid"
                           @click.native="save">{{ editedIndex === -1 ? 'Create product' :
                        'Update product' }}
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

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
                        <v-card-title>Not is stock</v-card-title>
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
                        <v-btn dark fab small color="dark" @click="dialog = true">
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
                                    <td class="text-xs-center">{{ props.item.name }}</td>
                                    <td class="text-xs-center">{{ props.item.quantity }}</td>
                                    <td class="text-xs-center">{{ props.item.quantity_type }}</td>
                                    <td class="text-xs-center">TK. {{ props.item.sale_price }}</td>
                                    <td class="text-xs-center">TK. {{ props.item.purchase_price }}</td>
                                    <td class="text-xs-center">{{ props.item.status }}</td>
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
                                                <td><strong>Serials</strong></td>
                                            </tr>

                                            <tr v-if="props.item.serials && props.item.serials.length > 0">
                                                <td v-for="(serial, index) in props.item.serials" :key="index">
                                                    {{ serial.product_serial }}
                                                </td>
                                            </tr>
                                            <tr v-else>
                                                <td>This product has no serial.</td>
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
    /* eslint-disable no-unreachable */


    export default {
        data: () => ({
            dialog: false,
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

            warranties: ['3 Month', '6 Month', '1 Year', '1.5 Year', '2 Year'],

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
                    text: 'Type',
                    value: 'quantity_types',
                    sortable: true
                },
                {
                    text: 'Sale price',
                    value: 'sale_price',
                    sortable: true
                },
                {
                    text: 'Purchase price',
                    value: 'purchase_price',
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
            status: [
                {
                    text: 'Avaliable',
                    value: 'available'
                },
                {
                    text: 'Unavaliable',
                    value: 'unavailable'
                }
            ],
            editedIndex: -1,
            editedItem: {
                id: '',
                name: '',
                description: '',
                quantity: 0,
                status: '',
                sale_price: '',
                purchase_price: '',
                quantity_type: ''
            },

            quantity_type: [],

            categories: [],
            selectedCategories: [],
            update_form: false,

            defaultItem: {
                id: '',
                name: '',
                description: '',
                quantity: '',
                status: '',
                sale_price: '',
                purchase_price: '',
                quantity_type: ''
            },
            row_per_page: [20, 30, 50, {'text': 'All', 'value': -1}],

            purchase_price_field: false,

            companies: [],
            selectedCompanies: [],

            isSerials: [{text: 'yes', value: 'true'}, {text: 'No', value: 'false'}],
            isSerial: '',
            productSerials: [],

            valid: true,

            productWarrantyError: false,


        }),

        computed: {
            formTitle() {
                return this.editedIndex === -1 ? 'New Product' : 'Edit Product'
            },

            totalCompanies() {
                let quantity = 0;
                let serials = [];
                this.selectedCompanies.forEach((company) => {
                    if (company.serials) {
                        serials = company.serials;
                    }

                    quantity += Number(company.quantity)
                    company.serials = [];
                    for (let i = 0; i < company.quantity; i++) {
                        if (this.isSerial === 'true') {
                            if (serials.length > 0) {
                                company.serials.push(serials[i]);
                            } else {
                                company.serials.push('');
                            }
                        }

                    }
                })

                this.editedItem.quantity = quantity;

                return this.selectedCompanies;
            }
        },

        watch: {
            dialog(val) {
                val || this.close()
            },

            isSerial(value) {
                this.productSerials = [];
                if (value === 'true') {
                    let count = Number(this.editedItem.quantity);
                    for (let i = 0; i < count; i++) {
                        this.productSerials.push('0');
                    }
                }
            },

            editedItem(value) {
                if (value.quantity) {
                    this.productSerials = [];
                    let count = Number(this.editedItem.quantity);
                    for (let i = 0; i < count; i++) {
                        this.productSerials.push('0');
                    }
                }

                if(value.companies){

                }
            }
        },

        created() {
            this.initialize()
        },

        methods: {
            initialize() {
                // get all product
                axios.get('/api/products')
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

                // get all categories
                axios.get('/api/categories')
                    .then((response) => {
                        let categories = response.data;
                        categories.forEach((value) => {
                            let category = {};
                            category.value = value.id;
                            category.text = value.name;

                            this.categories.push(category)
                        })
                    })
                    .catch((error) => {
                        console.log('categories error');
                        console.log(error)
                    })

                // get All product
                axios.get('/api/productcompany')
                    .then((response) => {
                        this.companies = response.data;
                    })
                    .catch((error) => {
                        console.log('Companies error');
                        console.log(error)
                    })
            },

            editItem(item) {
                // get selected categories & all categories
                let url = '/api/products/' + item.id + '/categories';

                axios.get(url)
                    .then((response) => {
                        let selectedCategories = response.data;
                        selectedCategories.forEach((value) => {
                            let categories = {}
                            categories.value = value.id
                            categories.text = value.name
                            this.selectedCategories.push(categories)
                        })
                    })
                this.editedIndex = this.items.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true
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

            onAddCompany() {
                let newcompany = {quantity: 0, companies: this.companies, selectedCompany: {}};
                this.selectedCompanies.push(newcompany);
            },

            onRemoveCompany(index) {
                this.selectedCompanies.splice(index, 1);
            },

            save() {
                if(!this.$refs.product_form.validate()){
                    return;
                }

                if(this.isSerial === 'true'){
                    console.log(this.totalCompanies);
                    let error = false;
                    this.totalCompanies.forEach((company) => {
                        if(company.product_warranty === undefined){
                            this.productWarrantyError = true;
                            error = true;
                        }
                    })

                    if(error){
                        return;
                    }
                }

                let form = new FormData();
                let url = '/api/products';

                form.append('name', this.editedItem.name);
                form.append('sellerId', this.$store.getters.getUserId);
                form.append('description', this.editedItem.description);
                form.append('purchase_price', this.editedItem.purchase_price);
                form.append('sale_price', this.editedItem.sale_price);
                form.append('quantity', this.editedItem.quantity);
                form.append('status', this.editedItem.status);
                form.append('quantity_type', this.editedItem.quantity_type);
                form.append('totalCompanies', JSON.stringify(this.totalCompanies));

                if (this.selectedCategories) {
                    form.append('categories', JSON.stringify(this.selectedCategories));
                }

                if (this.editedIndex > -1) {
                    // update product
                    form.append('_method', 'PATCH')
                    url = url + '/' + this.editedItem.id;
                    axios.post(url, form)
                        .then((response) => {
                            Object.assign(this.items[this.editedIndex], this.editedItem);
                            this.snackbar_message = 'Product ' + this.editedItem.name + ' successfully updated.';
                            this.snackbar = true;
                            this.close()
                            this.initialize();
                            this.$refs.product_form.reset();
                            this.selectedCompanies = [];
                            this.productWarrantyError = false
                        })
                } else {
                    // create product
                    axios.post(url, form)
                        .then((response) => {
                            this.items.push(response.data);
                            this.snackbar_message = 'Product ' + this.editedItem.name + ' successfully created.';
                            this.snackbar = true;
                            // update total product & stock
                            this.total_product++;

                            // let total = this.total_stock.replace(',', '');
                            // total = Number(total);
                            // this.total_stock = total + this.editedItem.quantity * this.editedItem.purchase_price;
                            this.close()
                            this.initialize();
                            this.$refs.product_form.reset();
                            this.selectedCompanies = [];
                            this.productWarrantyError = false;
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
                    let isItem = false;
                    let itemSerial = false;
                    if (item.serials.length > 0) {
                        item.serials.forEach((serial) => {
                            let productSerial = serial.product_serial.toString().toLowerCase();
                            if (productSerial.includes(search)) {
                                isItem = true;
                            }
                        })

                    }

                    let itemName = item.name.toString().toLowerCase();
                    if(itemName.includes(search)){
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
