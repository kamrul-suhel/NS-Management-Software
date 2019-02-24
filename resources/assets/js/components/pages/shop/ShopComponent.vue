<template>
    <section class="setting-page">
        <v-container grid-list-xl>
            <v-layout row wrap>
                <v-flex xs7>
                    <v-card>
                        <v-card-title>
                            <h2>Shops</h2>
                        </v-card-title>

                        <v-card-text>
                            <v-data-table
                                    dark
                                    :headers="headers"
                                    :items="shops"
                                    :search="search"
                                    :rows-per-page-items=row_per_page
                            >
                                <template slot="items" slot-scope="props">
                                    <td>{{ props.index + 1 }}</td>
                                    <td class="text-xs-left">{{ props.item.name }}</td>
                                    <td class="text-xs-left">{{ props.item.address }}</td>
                                    <td class="justify-start layout px-0">
                                        <v-btn icon class="mx-0" @click="editItem(props.item)">
                                            <v-icon color="dark">edit</v-icon>
                                        </v-btn>
                                        <v-btn icon class="mx-0" @click="openDeleteDialog(props.item)">
                                            <v-icon color="dark">delete</v-icon>
                                        </v-btn>
                                    </td>
                                </template>

                                <v-alert slot="no-results" :value="true" color="error" icon="warning">
                                    Your search for "{{ search }}" found no results.
                                </v-alert>

                                <template slot="no-data">
                                    <v-btn color="dark">Reset</v-btn>
                                </template>
                            </v-data-table>
                        </v-card-text>
                    </v-card>
                </v-flex>

                <v-flex xs5>
                    <v-card>
                        <v-card-title>
                            <h2>Shop Information</h2>
                            <v-spacer></v-spacer>

                        </v-card-title>

                        <v-card-text>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-text-field
                                            color="white"
                                            label="Shop Name"
                                            v-model="editedItem.name"
                                            hint="Name of Shop">

                                    </v-text-field>
                                </v-flex>

                                <v-flex xs12>
                                    <v-text-field
                                            color="white"
                                            label="Shop Shop number"
                                            hint="Enter you shop number"
                                            v-model="editedItem.serial"></v-text-field>
                                </v-flex>

                                <v-flex xs12>
                                    <v-textarea
                                            color="white"
                                            label="Address"
                                            v-model="editedItem.address"
                                            hint="Address of Shop">
                                    </v-textarea>
                                </v-flex>

                                <v-flex xs12>
                                    <v-text-field
                                            color="white"
                                            label="Phone"
                                            v-model="editedItem.phone"
                                            hint="Shop phone number">
                                    </v-text-field>
                                </v-flex>

                                <v-flex xs12>
                                    <v-text-field
                                            color="white"
                                            label="Mobile"
                                            v-model="editedItem.mobile"
                                            hint="Shop mobile number">
                                    </v-text-field>
                                </v-flex>

                                <v-flex xs12>
                                    <v-text-field
                                            color="white"
                                            label="Email"
                                            hint="Shop email"
                                            v-model="editedItem.email"></v-text-field>
                                </v-flex>

                                <v-flex xs12>
                                    <v-text-field
                                            color="white"
                                            label="Fax"
                                            v-model="editedItem.fax"
                                            hint="Shop fax"></v-text-field>
                                </v-flex>

                                <v-flex xs12>
                                    <v-text-field
                                            color="white"
                                            label="Shop website"
                                            v-model="editedItem.website"
                                            hint="Shop website"></v-text-field>
                                </v-flex>

                                <v-flex xs12>
                                    <v-text-field
                                            color="white"
                                            label="Shop logo"
                                            v-model="editedItem.logo"
                                            hint="Shop logo"></v-text-field>
                                </v-flex>

                            </v-layout>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn dark
                                   raised
                                   color="dark"
                                   @click="onReset">Reset</v-btn>
                            <v-btn
                                    dark
                                    raised
                                    color="dark"
                                    @click="onUpdateSetting()">{{ editedIndex <= 0 ? 'Create shop' : 'Update shop'}}
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-flex>
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
    </section>
</template>

<script>
    import axios from 'axios'

    export default {
        data: () => ({
            search: '',
            row_per_page: [20, 30, 50, {'text': 'All', 'value': -1}],
            snackbar: false,
            snackbar_message: '',

            defaultItem: {
                name: '',
                address: '',
                email: '',
                serial:'',
                phone: '',
                mobile: '',
                fax: '',
                website:'',
                logo:'',
            },

            editedItem: {},
            editedIndex:-1,


            headers: [
                {
                    text: 'Id',
                    align: 'left',
                    sortable: true,
                    value: 'id'
                },
                {
                    text: 'Name',
                    value: 'name',
                    sortable: true
                },
                {
                    text: 'Address',
                    value: 'address',
                },
                {
                    text: 'Action',
                    value: 'action'
                }
            ],

        }),

        computed: {
            shops: {
                get(){
                    return this.$store.getters.getShops;
                },

                set(value){

                }
            }
        },

        watch: {},

        created() {
        },

        methods: {

            onUpdateSetting() {
                let url = '/shops';
                if(this.editedIndex >= 0){
                    url = '/shops/'+ this.editedItem.id
                }
                let method = this.editedIndex >= 0 ? 'patch' : 'post';
                axios[method](url, this.editedItem).then((response) => {

                    let newShop = {...response.data};

                    //edit item. replace with existing item
                    if(this.editedIndex >= 0){
                        let index = this.shops.findIndex(shop => {
                            return newShop.id == shop.id;
                        })
                        let shops = [...this.shops];
                        shops[index] = newShop;
                        this.$store.commit('setShops', shops)
                        return;
                    }

                    this.shops.push(newShop);
                })
            },

            editItem(item){
                let editedItem = {...item}
                this.editedIndex = 1
                this.editedItem = editedItem
            },

            onReset(){
                this.editedIndex = -1
                this.editedItem = this.defaultItem
            }
        }
    }
</script>
