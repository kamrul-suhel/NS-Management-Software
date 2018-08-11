<template>
    <div class="products">
        <v-container grid-list-md>
            <v-layout row wrap>
                <v-flex xs12>
                    <h2>Customers</h2>
                </v-flex>
            </v-layout>

            <v-divider class="mb-3 dark"></v-divider>

            <v-layout row wrap>
                <v-flex xs3>
                    <v-card flat class="cyan lighten-1 white--text">
                        <v-card-title>Total Customers</v-card-title>
                        <v-card-text class="pt-0">
                            <h2 class="display-2 white--text text-xs-center"><strong>350</strong></h2>
                        </v-card-text>
                    </v-card>
                </v-flex>

                <v-flex xs3>
                    <v-card flat class="light-blue white--text">
                        <v-card-title>Active Customers</v-card-title>
                        <v-card-text class="pt-0">
                            <h2 class="display-2 white--text text-xs-center"><strong>350</strong></h2>
                        </v-card-text>
                    </v-card>
                </v-flex>

                <v-flex xs3>
                    <v-card flat class="light-green lighten-1 white--text">
                        <v-card-title>Deactive Customer</v-card-title>
                        <v-card-text class="pt-0">
                            <h2 class="display-2 white--text text-xs-center"><strong>350</strong></h2>
                        </v-card-text>
                    </v-card>
                </v-flex>

                <v-flex xs3>
                    <v-card flat class="orange darken-1 white--text">
                        <v-card-title>Highest Customer</v-card-title>
                        <v-card-text class="pt-0">
                            <h2 class="display-2 white--text text-xs-center"><strong>350</strong></h2>
                        </v-card-text>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>

        <v-dialog
                v-model="dialog"
                max-width="800px"
                height="1000px"
                persistent>
            <v-card class="px-2 py-2">
                <v-card-title>
                    <span class="headline">{{ formTitle }}</span>
                </v-card-title>

                <v-card-text class="pt-0">
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-text-field
                                        dark
                                        color="dark"
                                        label="Customer Name"
                                        v-model="editedItem.name"></v-text-field>
                            </v-flex>

                            <v-flex xs12>
                                <v-text-field
                                        dark
                                        color="dark"
                                        label="Email"
                                        v-model="editedItem.email"
                                ></v-text-field>
                            </v-flex>

                            <v-flex xs6>
                                <v-text-field
                                        dark
                                        color="dark"
                                        label="Phone"
                                        type="phone"
                                        hint="Phone number"
                                        v-model="editedItem.phone"
                                ></v-text-field>
                            </v-flex>

                            <v-flex xs6>
                                <v-text-field
                                        dark
                                        color="dark"
                                    label="mobile"
                                    type="mobile"
                                    hint="Mobile number"
                                    v-model="editedItem.mobile">
                                </v-text-field>
                            </v-flex>

                            <v-flex xs6>
                                <v-textarea
                                        dark
                                        color="dark"
                                    v-model="editedItem.address"
                                    label="Address"
                                    multi-line
                                ></v-textarea>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn dark color="dark" raised @click.native="close">Cancel</v-btn>

                    <v-btn dark color="dark" raised @click.native="save">{{ editedIndex == -1 ? 'Create customer' :
                        'Update customer' }}
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-container grid-list-lg>
            <v-layout row wrap>
                <v-card
                        raised
                        width="100%">
                    <v-card-title class="pb-0 pt-0">
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
                                <td class="text-xs-center">{{ props.item.name }}</td>
                                <td class="text-xs-center">{{ props.item.phone }}</td>
                                <td class="text-xs-center">{{ props.item.mobile }}</td>
                                <td class="text-xs-center">{{ props.item.address }}</td>
                                <td class="justify-start layout px-0">
                                    <v-btn icon class="mx-0" @click="editItem(props.item)">
                                        <v-icon color="dark">edit</v-icon>
                                    </v-btn>
                                    <v-btn icon class="mx-0" @click="viewTransition(props.item)">
                                        <v-icon clor="dark">view_comfy</v-icon>
                                    </v-btn>
                                    <v-btn icon class="mx-0" @click="deleteItem(props.item)">
                                        <v-icon color="pink">delete</v-icon>
                                    </v-btn>
                                </td>
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
    </div>
</template>
<script>
    /* eslint-disable no-unreachable */

    import axios from 'axios'

    export default {
        data: () => ({
            dialog: false,
            search: '',
            pagination: {
                sortBy: 'name'
            },

            snackbar: false,
            snackbar_message: '',

            headers: [
                {
                    text: 'Name',
                    value: 'name',
                    sortable: true
                },

                {
                    text: 'Phone',
                    value: 'phone',
                    sortable: false
                },
                {
                    text: 'Mobile',
                    value: 'mobile',
                    sortable: false
                },
                {
                    text: 'Address',
                    value: 'address',
                    sortable: true
                },
                {
                    text: 'Actions'
                }
            ],
            total_customer: '',
            items: [],

            editedIndex: -1,
            editedItem: {
                id: '',
                name: 'New title',
                email: 'new Description',
                phone: '01622296755',
                mobile: '075493188',
                address: 'available',
                active: '1'
            },
            active: [1,2],


            defaultItem: {
                name: '',
                descriptin: ''
            },
            row_per_page: [20, 30, 50, {'text': 'All', 'value': -1}],

        }),

        computed: {
            formTitle() {
                return this.editedIndex === -1 ? 'New Customer' : 'Edit Customer'
            }
        },

        watch: {
            dialog(val) {
                val || this.close()
            }
        },

        created() {
            this.initialize()
        },

        methods: {
            initialize() {
                // get all product
                axios.get('/customers')
                    .then((response) => {
                        if(response.data.length > 0){
                            this.items = response.data
                        }
                    })
                    .catch((error) => {
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
                            let categories = {};
                            categories.value = value.id;
                            categories.text = value.name;
                            this.selectedCategories.push(categories)
                        })
                    })
                this.editedIndex = this.items.indexOf(item);
                this.editedItem = Object.assign({}, item);
                this.dialog = true
            },

            deleteItem(item) {
                const index = this.items.indexOf(item);
                confirm('Are you sure you want to delete this item?') && this.items.splice(index, 1)
            },

            close() {
                this.dialog = false
                this.selectedCategories = [];
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem);
                    this.editedIndex = -1
                }, 300)
            },

            save() {
                let form = new FormData();
                let url = '/customers';

                form.append('name', this.editedItem.name);
                form.append('email', this.editedItem.email);
                form.append('phone', this.editedItem.phone);
                form.append('mobile', this.editedItem.mobile);
                form.append('address', this.editedItem.address);

                if (this.selectedCategories) {
                    form.append('categories', JSON.stringify(this.selectedCategories))
                }

                if (this.editedIndex !== -1) {
                    // update product
                    form.append('_method', 'PATCH');
                    url = url + '/'+this.editedItem.id;
                    axios.post(url, form)
                        .then((response) => {
                            Object.assign(this.items[this.editedIndex], this.editedItem);
                            this.snackbar_message = 'Customer '+this.editedItem.name + ' successfully updated.';
                            this.snackbar = true;
                        })
                } else {
                    // create product
                    axios.post(url, form)
                        .then((response) => {
                            this.items.push(response.data);
                            this.snackbar_message = 'Customer '+this.editedItem.name + ' successfully updated.';
                            this.snackbar = true;
                        })
                }

                this.close()
            },

            changeSort(column) {
                if (this.pagination.sortBy === column) {
                    this.pagination.descending = !this.pagination.descending
                } else {
                    this.pagination.sortBy = column;
                    this.pagination.descending = false
                }
            },

            viewTransition(){

            }
        }
    }
</script>
