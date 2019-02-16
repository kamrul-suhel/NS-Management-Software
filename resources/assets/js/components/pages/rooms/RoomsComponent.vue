<template>
    <div class="room-page">

        <v-dialog
                v-model="dialog"
                max-width="800"
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
                                <v-flex xs6>
                                    <v-text-field
                                            label="Room number"
                                            v-model="editedItem.room_number"
                                            dark
                                            color="dark"
                                            :rules="[v => !!v || 'Title is required']"
                                            required
                                    ></v-text-field>
                                </v-flex>

                                <v-flex xs6>
                                    <v-text-field
                                            label="Title"
                                            v-model="editedItem.title"
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

                                <v-flex xs6>
                                    <v-text-field
                                            label="Per day"
                                            v-model="editedItem.price"
                                            dark
                                            color="dark"
                                            :rules="[v => !!v || 'Price is required']"
                                            required
                                    ></v-text-field>
                                </v-flex>

                                <v-flex xs6>
                                    <v-text-field
                                            label="Additional Charge"
                                            v-model="editedItem.additional_price"
                                            dark
                                            color="dark"
                                            :rules="[v => !!v || 'Price is required']"
                                            required
                                    ></v-text-field>
                                </v-flex>

                                <v-flex xs6>
                                    <v-select
                                            :items="status"
                                            v-model="editedItem.status"
                                            label="Room status"
                                            single-line
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
                           @click.native="save">{{ editedIndex === -1 ? 'Create Room' :
                        'Update Room' }}
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-container grid-list-md class="pt-0">
            <v-layout row wrap>
                <v-flex xs12 class="pt-0">
                    <h2>ROOMS</h2>
                </v-flex>

                <v-flex xs12>
                    <v-flex xs-12>
                        <v-btn dark small class="ml-0" color="dark" @click="dialog = true">
                            <v-icon>add</v-icon> Add new room
                        </v-btn>
                    </v-flex>
                </v-flex>
            </v-layout>

            <v-divider class="mb-3 dark"></v-divider>

            <v-layout row wrap>
                <v-flex xs4>
                    <v-card flat class="cyan lighten-1 white--text">
                        <v-card-title>Total Rooms</v-card-title>
                        <v-card-text class="pt-0">
                            <h2 class="display-2 white--text text-xs-center">
                                <strong>{{total_rooms}}</strong>
                            </h2>
                        </v-card-text>
                    </v-card>
                </v-flex>

                <v-flex xs4>
                    <v-card flat class="light-blue white--text">
                        <v-card-title>Room Available</v-card-title>
                        <v-card-text class="pt-0">
                            <h2 class="display-2 white--text text-xs-center">
                                <strong>{{avaliable_rooms}}</strong>
                            </h2>
                        </v-card-text>
                    </v-card>
                </v-flex>

                <v-flex xs4>
                    <v-card flat class="light-green lighten-1 white--text">
                        <v-card-title>Unavailable Room</v-card-title>
                        <v-card-text class="pt-0">
                            <h2 class="display-2 white--text text-xs-center">
                                <strong>{{unavaliable_rooms}}</strong>
                            </h2>
                        </v-card-text>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>

        <v-container grid-list-md>
            <v-layout align-content-end justify-end>
                <v-flex>
                    <v-radio-group v-model="selectedView" row>
                        <v-radio label="Box view" value="box" color="dark"></v-radio>
                        <v-radio label="Table view" value="table" color="dark"></v-radio>
                    </v-radio-group>
                </v-flex>
            </v-layout>

            <v-layout row wrap v-if="selectedView === 'box'">
                <v-flex xs6 md4 v-for="room in items" :key="room.id">
                    <v-card height="250" :color="room.status === 'available' ? 'blue-grey darken-2' : 'lime darken-1'" class="white--text">
                        <v-card-title primary-title>
                            <div class="headline">{{room.title}}</div>
                            <div>{{room.description}}</div>
                            <div class="room-number">Room Number : <span class="price">{{room.room_number}}</span></div>
                            <div class="room-price-content">
                                <div class="room-price">Per day : {{room.price}}</div>
                                <div class="room-price">Extra : {{room.additional_price}}</div>
                                <div class="room-price">Status : {{room.status}}</div>
                            </div>
                        </v-card-title>

                        <v-card-actions wrap>
                            <v-btn flat>View</v-btn>
                            <v-btn flat v-if="room.status === 'available'">Rent</v-btn>
                            <v-btn flat color="white" @click="editItem(room)">Update</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-flex>
            </v-layout>

            <v-layout row wrap v-else>

                <v-card width="100%">
                    <v-card-title>
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
                                    <td>{{ props.item.title }}</td>
                                    <td>TK. {{ props.item.price }}</td>
                                    <td>TK. {{ props.item.additional_price }}</td>
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

                            <template slot="no-data">
                                Sorry no room found
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
            dialog: false,
            search: '',
            pagination: {
                sortBy: 'name'
            },

            status : [
                'available',
                'unavailable'
            ],

            selectedView: 'box',

            avaliable_rooms: 0,
            unavaliable_rooms: 0,
            total_rooms: 0,
            total_stock: 0,

            deleteDialog: false,
            deleteItem: {},


            snackbar: false,
            snackbar_message: '',

            headers: [
                {
                    text: 'Title',
                    value: 'name',
                    sortable: true
                },
                {
                    text: 'Per day',
                    value: 'sale_price',
                    sortable: true
                },

                {
                    text: 'Extra charge',
                    value: 'additional_price',
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
            editedItem: {},

            update_form: false,

            defaultItem: {},

            row_per_page: [20, 30, 50, {'text': 'All', 'value': -1}],

            valid: true,

            barcodeDialogvalue: false,
            barcode: ''

        }),

        computed: {
            ...mapGetters({
                selectedShop: 'getSelectedShop'
            }),

            formTitle() {
                return this.editedIndex === -1 ? 'New Room' : 'Edit Room'
            },

        },

        watch: {
            selectedShop() {
                this.initialize();
            },

            dialog(val) {
                val || this.close()
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
                let roomUrl = '/api/rooms'
                if (shopId) {
                    roomUrl += '?shopId=' + shopId
                }

                // get all product
                axios.get(roomUrl)
                    .then((response) => {
                        this.items = response.data.rooms;
                        this.total_rooms = response.data.total_rooms;
                        this.avaliable_rooms = response.data.avaliable_rooms;
                        this.unavaliable_rooms = response.data.unavaliable_rooms ? response.data.unavaliable_rooms : 0;
                        this.total_stock = response.data.total_stock;
                    })
                    .catch((error) => {
                        console.log(error)
                    });
            },

            editItem(item) {
                this.editedIndex = this.items.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true
            },

            openDeleteDialog(deleteItem) {
                this.deleteItem = deleteItem;
                this.deleteDialog = true;
            },

            deleteItemD() {
                let url = 'api/rooms/' + this.deleteItem.id
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
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem);
                    this.editedIndex = -1;
                }, 300)
            },

            save() {
                if (!this.$refs.product_form.validate()) {
                    return;
                }

                let form = new FormData();
                let url = '/api/rooms';

                form.append('room_number', this.editedItem.room_number);
                form.append('hotel_id', this.selectedShop.id);
                form.append('title', this.editedItem.title);
                form.append('description', this.editedItem.description);
                form.append('price', this.editedItem.price);
                form.append('additional_price', this.editedItem.additional_price);
                form.append('status', this.editedItem.status);

                if (this.editedIndex > -1) {
                    // update product
                    form.append('_method', 'PATCH')
                    url = url + '/' + this.editedItem.id;
                    axios.post(url, form)
                        .then((response) => {
                            Object.assign(this.items[this.editedIndex], this.editedItem);
                            this.snackbar_message = 'Room ' + this.editedItem.title + ' successfully updated.';
                            this.snackbar = true;
                            this.close()
                            this.initialize();
                            this.$refs.product_form.reset();
                        })
                } else {
                    // create product
                    axios.post(url, form)
                        .then((response) => {
                            this.items.push(response.data);
                            this.snackbar_message = 'Room ' + this.editedItem.title + ' successfully created.';
                            this.snackbar = true;
                            // update total product & stock
                            this.total_rooms++;

                            // let total = this.total_stock.replace(',', '');
                            // total = Number(total);
                            // this.total_stock = total + this.editedItem.quantity * this.editedItem.purchase_price;
                            this.close()
                            this.initialize();
                            this.$refs.product_form.reset();
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
                    if (itemName.includes(search)) {
                        itemSerial = true
                    }

                    if (isItem || itemSerial) {
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
