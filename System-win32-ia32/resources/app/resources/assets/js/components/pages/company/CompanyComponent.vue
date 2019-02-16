<template>
    <div class="company">
        <v-container grid-list-md>
            <v-layout row wrap>
                <v-flex xs12>
                    <h2>Company</h2>
                </v-flex>
            </v-layout>

            <v-divider class="mb-3 dark"></v-divider>

            <v-layout row wrap>
                <v-flex xs3>
                    <v-card flat class="cyan lighten-1 white--text">
                        <v-card-title>Total Companies</v-card-title>
                        <v-card-text class="pt-0">
                            <h2 class="display-2 white--text text-xs-center"><strong>{{ totalCompany }}</strong></h2>
                        </v-card-text>
                    </v-card>
                </v-flex>

                <v-flex xs3>
                    <v-card flat class="light-blue white--text">
                        <v-card-title>Active Company</v-card-title>
                        <v-card-text class="pt-0">
                            <h2 class="display-2 white--text text-xs-center"><strong>350</strong></h2>
                        </v-card-text>
                    </v-card>
                </v-flex>

                <v-flex xs3>
                    <v-card flat class="light-green lighten-1 white--text">
                        <v-card-title>Deactive Company</v-card-title>
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
                <v-card-text>
                    <v-container grid-list-lg class="pa-0">
                        <v-layout wrap>
                            <v-flex xs12>
                                <h2 class="headline">{{ formTitle }}</h2>
                            </v-flex>

                            <v-flex xs6>
                                <v-text-field
                                        dark
                                        color="dark"
                                        label="Company Name"
                                        v-model="editedItem.name"></v-text-field>
                            </v-flex>

                            <v-flex xs6>
                                <v-text-field
                                        dark
                                        color="dark"
                                        label="Company Email"
                                        v-model="editedItem.email"
                                ></v-text-field>
                            </v-flex>

                            <v-flex xs6>
                                <v-text-field
                                        dark
                                        color="dark"
                                        label="Company Phone"
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
                                <v-text-field
                                        dark
                                        color="dark"
                                        label="Fax"
                                        hint="Fax number"
                                        v-model="editedItem.fax">
                                </v-text-field>
                            </v-flex>

                            <v-flex xs6>
                                <v-text-field
                                        dark
                                        color="dark"
                                        label="website"
                                        hint="Web site URL"
                                        v-model="editedItem.websiteurl">
                                </v-text-field>
                            </v-flex>


                            <v-flex xs6>
                                <v-textarea
                                        dark
                                        color="dark"
                                    v-model="editedItem.address"
                                    label="Address"
                                ></v-textarea>
                            </v-flex>

                            <v-flex xs6>
                                <v-textarea
                                        dark
                                        color="dark"
                                        v-model="editedItem.description"
                                        label="Description"
                                ></v-textarea>
                            </v-flex>

                            <v-flex xs6>
                                <v-text-field
                                        dark
                                        color="dark"
                                        label="City"
                                        hint="Location of company"
                                        v-model="editedItem.city">
                                </v-text-field>
                            </v-flex>

                            <v-flex xs6>
                                <v-select
                                        color="dark"
                                        dark
                                        :items="active"
                                        label="Status"
                                        hint="Company status"
                                        v-model="editedItem.status">
                                </v-select>
                            </v-flex>

                            <v-flex xs12 class="text-xs-right">
                                <v-btn dark color="dark" raised @click.native="close">Cancel</v-btn>

                                <v-btn dark color="dark" raised @click.native="save">{{ editedIndex == -1 ? 'Create Company' :
                                    'Update Company' }}
                                </v-btn>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
            </v-card>
        </v-dialog>


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
                        <v-btn dark
                               medium
                               color="dark"
                               @click="dialog = true">
                            Add Company
                        </v-btn>

                        <v-spacer></v-spacer>
                        <v-text-field
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
                                    <v-btn icon class="mx-0" @click="openDeleteDialog(props.item)">
                                        <v-icon color="pink">delete</v-icon>
                                    </v-btn>
                                </td>
                            </template>

                            <v-alert slot="no-results" :value="true" color="error" icon="warning">
                                Your search for "{{ search }}" found no results.
                            </v-alert>

                            <template slot="no-data">
                                Sorry no company found
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
            deleteDialog:false,
            search: '',
            pagination: {
                sortBy: 'name'
            },

            snackbar: false,
            snackbar_message: '',

            totalCompany: 0,

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
                name: '',
                address: '',
                description: '',
                email: '',
                phone: '',
                mobile: '',
                fax: '',
                websiteurl: '',
                city: '',
                status: 'Active'
            },
            active: ['Active', 'Inactive'],


            defaultItem: {
                name: '',
                descriptin: ''
            },
            row_per_page: [20, 30, 50, {'text': 'All', 'value': -1}],

            deleteItem:{},

        }),

        computed: {
            formTitle() {
                return this.editedIndex === -1 ? 'New Company' : 'Update Company'
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
                axios.get('/company')
                    .then((response) => {
                        this.items = response.data.companies;
                        this.totalCompany = response.data.totalCompany
                    })
                    .catch((error) => {
                        console.log(error)
                    })

            },

            openDeleteDialog(deleteItem){
                this.deleteItem = deleteItem;
                this.deleteDialog = true;
            },

            deleteItemD () {
                let url = 'api/company/'+this.deleteItem.id;
                axios.delete(url).then((response) => {
                    this.deleteDialog = false;
                    const index = this.items.indexOf(this.deleteItem)
                    this.items.splice(index, 1)
                });
            },

            close() {
                this.dialog = false
                this.selectedCategories = [];
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem);
                    this.editedIndex = -1
                }, 300)
            },

            editItem(item) {
                // get selected categories & all categories
                this.editedIndex = this.items.indexOf(item);
                this.editedItem = Object.assign({}, item);
                this.dialog = true
            },

            save() {
                let form = new FormData();
                let url = '/api/company';

                form.append('name', this.editedItem.name);
                form.append('email', this.editedItem.email);
                form.append('phone', this.editedItem.phone);
                form.append('mobile', this.editedItem.mobile);
                form.append('fax', this.editedItem.fax);
                form.append('address', this.editedItem.address);
                form.append('description', this.editedItem.description);
                form.append('websiteurl', this.editedItem.websiteurl);
                form.append('city', this.editedItem.city);
                form.append('status', this.editedItem.status);

                if (this.editedIndex !== -1) {
                    // update product
                    form.append('_method', 'PATCH');
                    url = url + '/'+this.editedItem.id;
                    axios.post(url, form)
                        .then((response) => {
                            Object.assign(this.items[this.editedIndex], this.editedItem);
                            this.snackbar_message = 'Company '+this.editedItem.name + ' successfully updated.';
                            this.snackbar = true;
                            this.close();
                        })
                } else {
                    // create product
                    axios.post(url, form)
                        .then((response) => {
                            this.items.push(response.data);
                            this.snackbar_message = 'Company '+this.editedItem.name + ' successfully created.';
                            this.snackbar = true;
                            this.close()
                        })
                }

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
