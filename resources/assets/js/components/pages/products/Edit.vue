<template>
    <div class="products">
        <v-container grid-list-md>
            <v-layout row wrap>
                <v-card class="px-2 py-2">
                    <v-card-title>
                        <span class="headline">Edit Product</span>
                    </v-card-title>

                    <v-card-text>
                        <v-form ref="product_form"
                                v-model="valid"
                                lazy-validation>
                            <v-container fluid grid-list-md>
                                <v-layout row wrap>
                                    <v-flex xs12>
                                        <v-text-field
                                                label="Item Name"
                                                v-model="editedItem.name"
                                                dark
                                                color="dark"
                                                :rules="[v => !!v || 'Item name is required']"
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
                                                label="Does product has barcode ?"
                                                :items="productHasBarcode"
                                                item-text="text"
                                                item-value="value"
                                                disabled
                                                required
                                                :rules="[v => !!v || 'This field required']"
                                                v-model="editedItem.is_barcode"></v-select>
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


                                            <v-flex class="xs6">
                                                <v-text-field
                                                        label="How many quantity"
                                                        dark
                                                        v-model="company.quantity"
                                                        required
                                                        mask="####"
                                                        :reles="[v => !!v || 'Quantity is required' ]"
                                                        color="dark">
                                                </v-text-field>
                                            </v-flex>

                                            <v-layout row wrap>
                                                <v-flex xs12>
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

                                                <v-flex xs12 v-if="editedItem.is_barcode === 'yes'">
                                                    <v-layout row wrap
                                                              v-for="(serial, index) in company.serials"
                                                              :key="index">
                                                        <v-flex xs4>
                                                            <v-text-field
                                                                    required
                                                                    :reles="[v => !!v || 'Barcode is required' ]"
                                                                    dark
                                                                    color="dark"
                                                                    :label="'Barcode ' + (parseInt(index)+1)"
                                                                    v-model="company.serials[index].barcode"
                                                            ></v-text-field>
                                                        </v-flex>

                                                        <v-flex xs4>
                                                            <v-text-field
                                                                    required
                                                                    :reles="[v => !!v || 'IMEI is required' ]"
                                                                    dark
                                                                    color="dark"
                                                                    :label="'IMEI Number '+ (parseInt(index)+1)"
                                                                    v-model="company.serials[index].imei"
                                                            ></v-text-field>
                                                        </v-flex>

                                                        <v-flex xs4>
                                                            <v-text-field
                                                                    required
                                                                    :reles="[v => !!v || 'Color is required' ]"
                                                                    dark
                                                                    color="dark"
                                                                    :label="'Color ' + (parseInt(index)+1)"
                                                                    v-model="company.serials[index].color"
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
                                                :rules="[v => !!v || 'Quantity is required']"
                                                color="dark"
                                                placeholder="00.00"
                                                :hint="'Your total quantity is : '+ (parseFloat(prevQuantity) + parseFloat(editedItem.quantity))"
                                                persistent-hint
                                                disabled
                                                v-model="editedItem.quantity"
                                        ></v-text-field>
                                    </v-flex>

                                    <v-flex xs6>
                                        <v-text-field
                                                dark
                                                color="dark"
                                                label="Sale price per item"
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
                                                disabled
                                                dark
                                                color="dark"
                                                label="Purchase price per item"
                                                type="number"
                                                placeholder="00.00"
                                                prefix="TK"
                                                required
                                                :rules="[v => !!v || 'Purchase price is required']"
                                                v-model="editedItem.purchase_price">
                                        </v-text-field>
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
                               @click.native="save">{{ editedIndex === -1 ? 'Create product' :
                            'Update product' }}
                        </v-btn>
                    </v-card-actions>
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

            warranties: ['No warranty', '3 Month', '6 Month', '1 Year', '1.5 Year', '2 Year', '3 Year', '4 year', '5 year'],

            quantityToFeetError: false,
            quantityToFeet: 0,
            totalFeets: 0,


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
            editedItem: {},
            prevQuantity:0,

            quantity_type: [],

            categories: [],
            selectedCategories: [],
            update_form: false,

            defaultItem: {},

            purchase_price_field: false,

            companies: [],
            selectedCompanies: [],

            productHasBarcode: [
                {text: 'Yes', value: 'yes'},
                {text: 'No', value: 'no'}
            ],
            productSerials: [],

            valid: true,

            productWarrantyError: false,

            barcodeDialogvalue: false,
            barcode: ''

        }),

        computed: {
            ...mapGetters({
                selectedShop: 'getSelectedShop'
            }),

            formTitle() {
                return this.editedIndex === -1 ? 'New Product' : 'Edit Product'
            },

            totalCompanies(value) {
                let quantity = 0;
                let feets = 0;
                let serials = [];
                this.selectedCompanies.forEach((company) => {

                    quantity += Number(company.quantity)

                    if (this.editedItem.is_barcode === 'yes') {
                        if (company.serials) {
                            serials = [...company.serials];
                        }
                        company.serials = [];
                        for (let i = 0; i < company.quantity; i++) {
                            if(i < 100){
                                if (serials.length > 0) {
                                    company.serials.push({...serials[i]});
                                } else {
                                    company.serials.push({});
                                }
                            }

                        }
                    } else {
                        company.serials = [];
                    }

                })

                this.editedItem.quantity = quantity;

                if (isNaN(feets)) {
                    feets = Number(0);
                }

                this.totalFeets = (quantity * this.quantityToFeet) + feets;

                return this.selectedCompanies;
            },

            // barcodeDialog(){
            //     return this.barcodeDialogvalue;
            // }
        },

        watch: {
            selectedShop() {
                this.initialize();
            },

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
                return;
                if (value.quantity) {
                    this.productSerials = [];
                    let count = Number(this.editedItem.quantity);
                    for (let i = 0; i < count; i++) {
                        this.productSerials.push('0');
                    }
                }

                if (value.companies) {

                }
            },

            quantityToFeet(value) {
                let feetPerUnit = Number(value);
                if (!isNaN(feetPerUnit) && value >= 0) {
                    this.quantityToFeetError = false;
                    this.valid = true;
                }
            }
        },

        created() {
            this.initialize();
            //Barcode scannser
            this.$barcodeScanner.init(this.onBarcodeScanned);
        },

        methods: {
            initialize() {
                const shopId = this.selectedShop.id
                let categoriesUrl = '/api/categories'
                if (shopId) {
                    categoriesUrl += '?shopId=' + shopId
                }

                // get all categories
                axios.get(categoriesUrl)
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

                // get All companies
                axios.get('/api/productcompany')
                    .then((response) => {
                        this.companies = [...response.data];
                    })
                    .catch((error) => {
                        console.log('Companies error');
                        console.log(error)
                    })

                // Get selected product
                const productUrl = '/api/products/'+this.$route.params.id;
                axios.get(productUrl)
                    .then((response) => {
                        this.editedItem = {...response.data}

                        this.prevQuantity = this.editedItem.quantity && this.editedItem.quantity;
                        console.log(this.editedItem);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },


            editItem(item) {
                // get selected product & all categories
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

            onAddCompany() {
                let newcompany = {quantity: 0, companies: this.companies, selectedCompany: {}};
                this.selectedCompanies.push(newcompany);
            },

            onRemoveCompany(index) {
                this.selectedCompanies.splice(index, 1);
            },

            save() {
                let companies = [];

                // custom validate error
                let error = false;

                _.map(this.totalCompanies, (company, index) => {
                    let curCompany = {};
                    _.forEach(company, (value, key) => {

                        if (key === 'companies') {
                            return;
                        }
                        if (key === 'serials') {
                            curCompany.serials = {...value};
                            return;
                        }

                        if (key === 'selectedCompany') {
                            curCompany.selectedCompany = {...value};
                            return;
                        }
                        curCompany[key] = value
                    })

                    companies.push(curCompany);
                })

                if (!this.$refs.product_form.validate()) {
                    return;
                }

                this.totalCompanies.forEach((company) => {
                    if (!company.product_warranty) {
                        this.productWarrantyError = true;
                        error = true;
                    }
                })

                if (error) {
                    return;
                }

                let url = '/api/products/'+this.editedItem.id;
                let formData = new FormData();
                formData.append('_method', 'PATCH');
                formData.append('id', this.editedItem.id);
                formData.append('name', this.editedItem.name);
                formData.append('seller_id', this.$store.getters.getUserId);
                formData.append('store_id', this.$store.getters.getSelectedShopId);
                formData.append('description', this.editedItem.description);
                formData.append('sale_price', this.editedItem.sale_price);
                formData.append('quantity', this.editedItem.quantity);
                formData.append('product_type', this.editedItem.productHasBarcode);
                formData.append('totalCompanies', JSON.stringify(companies));

                // create product
                axios.post(url, formData)
                    .then((response) => {
                        this.items.push(response.data);
                        this.snackbar_message = 'Product ' + this.editedItem.name + ' successfully updated.';
                        this.snackbar = true;
                        setTimeout(() => {
                            this.$router.push({name: 'products'});
                        }, 2000)
                    })
            },

            onBarcodeScanned(code) {
                this.barcodeDailog = true;
                this.barcode = code;
                this.barcodeDialogvalue = true;
                if (code !== '') {
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

            toJson(object) {
                var Prototype = window.Prototype
                if (Prototype && Prototype.Version < '1.7' &&
                    Array.prototype.toJSON && Object.toJSON) {
                    return Object.toJSON(object)
                }
                return JSON.stringify(object)
            }
        }
    }
</script>

<style>
    .products table.table thead th:first-child {
        padding: 0 15px;
    }

</style>
