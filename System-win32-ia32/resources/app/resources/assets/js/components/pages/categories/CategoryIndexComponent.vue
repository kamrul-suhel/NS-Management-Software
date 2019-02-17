<template>
    <section class="categories-page">

        <v-dialog v-model="dialog" max-width="500px">
            <v-btn
                    dark
                    color="dark"
                    slot="activator"
                    raised
                    class="mb-2 ml-0">Add Category</v-btn>

            <v-card>
                <v-card-title>
                    <span class="headline">{{ formTitle }}</span>
                </v-card-title>

                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-text-field label="Title" v-model="editedItem.name"></v-text-field>
                            </v-flex>

                            <v-flex xs12>
                                <v-textarea
                                    label="Description"
                                    v-model="editedItem.description"

                                    ></v-textarea>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn color="dark" dark raised @click.native="close">Cancel</v-btn>

                    <v-btn color="dark" dark raised @click.native="save">{{ buttonTitle }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-card>
            <v-card-title>
                Categories
                <v-spacer></v-spacer>
                <v-text-field
                    hide-details
                    label="Search"
                    v-model="search"
                    append-icon="search"></v-text-field>
            </v-card-title>

            <v-card-text>
                <v-data-table
                    :headers="headers"
                    :items="items"
                    :search="search"
                    :rows-per-page-items=row_per_page
                    >
                    <template slot="items" slot-scope="props">
                        <td>{{ props.index + 1 }}</td>
                        <td class="text-xs-left">{{ props.item.name }}</td>
                        <td class="text-xs-left">{{ props.item.description }}</td>
                        <td class="justify-start layout px-0">
                            <v-btn icon class="mx-0" @click="editItem(props.item)">
                                <v-icon color="primary">edit</v-icon>
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
                        <v-btn color="primary" @click="initialize">Reset</v-btn>
                    </template>
                </v-data-table>
            </v-card-text>
        </v-card>

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
            dialog: false,
            search:'',
            snackbar: false,
            snackbar_message:'',
            headers: [
                {
                    text: 'Identifier',
                    align: 'left',
                    sortable: true,
                    value: 'id'
                },
                {
                    text: 'Title',
                    value: 'name',
                    sortable: true
                },
                {
                    text: 'Description',
                    value: 'description',
                },
                {
                    text: 'Action',
                    value: 'action'
                }
            ],
            items: [],
            editedIndex: -1,
            editedItem: {
                id:'',
                name: '',
                description: '',
            },
            defaultItem: {
                name: '',
                descriptin: '',
            },
            row_per_page: [20, 30, 50, {'text': 'All', 'value': -1}],
        }),

        computed: {
            formTitle () {
                return this.editedIndex === -1 ? 'New Category' : 'Edit Category'
            },

            buttonTitle () {
                return this.editedIndex === -1 ? 'Create Category' : 'Update Category'
            }
        },

        watch: {
            dialog (val) {
                val || this.close()
            }
        },

        created () {
            this.initialize()
        },

        methods: {
            initialize () {
                axios.get('api/categories')
                .then((response) => {
                    this.items = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
            },

            editItem (item) {
                this.editedIndex = this.items.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true
            },

            deleteItem (item) {
                const index = this.items.indexOf(item)
                confirm('Are you sure you want to delete this item?') && this.items.splice(index, 1)
            },

            close () {
                this.dialog = false
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem);
                    this.editedIndex = -1;
                }, 300)
            },

            save () {
                let form = new FormData();
                let url = 'api/categories';

                form.append('name', this.editedItem.name);
                form.append('description', this.editedItem.description);

                if (this.editedIndex > -1) {
                    form.append('_method', 'PUT');
                    url = url +'/'+ this.editedItem.id;

                    axios.post(url, form)
                        .then((response) => {
                            Object.assign(this.items[this.editedIndex], this.editedItem);
                            this.snackbar_message = "Category "+this.editedItem.name+" successfully update.";
                            this.snackbar = true;
                        })
                    .catch((error)=> {

                    });
                } else {
                    axios.post(url, form)
                    .then((response) => {
                        this.items.push(response.data);
                        this.snackbar_message = "Category "+this.editedItem.name+" successfully created.";
                        this.snackbar = true;
                    });
                }
                this.close()

            }
        }
    }
</script>
