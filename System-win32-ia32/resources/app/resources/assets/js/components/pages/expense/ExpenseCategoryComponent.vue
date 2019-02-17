<template>
    <section class="expense-categories-page">
        <v-dialog v-model="dialog" persistent max-width="290">
            <v-card color="error">
                <v-card-text>
                    <div class="text-xs-center"><v-icon color="white" size="50">warning</v-icon></div>
                    <p class="text-xs-center">Are you sure you want to delete {{deleteItem.title}} {{ deleteItem.description}}</p>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="dark darken-1" flat @click.native="dialog = false">Disagree</v-btn>
                    <v-btn color="dark darken-1" flat @click.native="deleteItemD()">Agree</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

            <v-layout row wrap>
                <v-flex xs8>
                    <v-card>
                        <v-card-title>
                            <h2>Expense Categories</h2>
                            <v-spacer></v-spacer>
                            <v-text-field
                                    hide-details
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
                                    <td class="text-xs-left">{{ props.item.title }}</td>
                                    <td class="text-xs-left">{{ props.item.description }}</td>
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
                                    <v-btn color="dark" @click="initialize">Reset</v-btn>
                                </template>
                            </v-data-table>
                        </v-card-text>
                    </v-card>
                </v-flex>

                <v-flex xs4 class="pl-4">
                    <v-card>
                        <v-card-title class="pb-0">
                            <span class="headline">{{ formTitle }}</span>
                        </v-card-title>

                        <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex xs12>
                                        <v-text-field
                                                color="dark"
                                                label="Title"
                                                v-model="editedItem.title"
                                        dark></v-text-field>
                                    </v-flex>

                                    <v-flex xs12>
                                        <v-textarea
                                                label="Description"
                                                color="dark"
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
                </v-flex>
            </v-layout>


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
                    value: 'title',
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
                title: '',
                description: '',
            },
            defaultItem: {
                title: '',
                descriptin: '',
            },
            row_per_page: [10, 30, 50, {'text': 'All', 'value': -1}],

            deleteItem:{},
        }),

        computed: {
            theme(){
              this.$store.getters.getTheme;
            },
            formTitle () {
                return this.editedIndex === -1 ? 'New Expense Category' : 'Edit Expense Category'
            },

            buttonTitle () {
                return this.editedIndex === -1 ? 'Create Category' : 'Update Category'
            }
        },

        created () {
            this.initialize()
        },

        methods: {
            initialize () {
                axios.get('api/expensecategory')
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
            },

            openDeleteDialog(deleteItem){
                this.deleteItem = deleteItem;
                this.dialog = true;
            },

            deleteItemD () {
                let url = 'api/expensecategory/'+this.deleteItem.id;
                axios.delete(url).then((response) => {
                    this.dialog = false;
                    const index = this.items.indexOf(this.deleteItem)
                    this.items.splice(index, 1)
                });
            },

            close () {

                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            },

            save () {
                let form = new FormData();
                let url = 'api/expensecategory';

                form.append('title', this.editedItem.title);
                form.append('description', this.editedItem.description);


                if (this.editedIndex > -1) {
                    form.append('_method', 'PUT');
                    url = url +'/'+ this.editedItem.id;

                    axios.post(url, form)
                        .then((response) => {
                            Object.assign(this.items[this.editedIndex], response.data);
                            this.snackbar_message = "Category "+this.editedItem.title+" successfully update.";
                            this.snackbar = true;
                            this.close()

                        })
                    .catch((error)=> {
                        console.log(error);
                    });
                } else {
                    axios.post(url, form)
                    .then((response) => {
                        this.items.unshift(response.data);
                        this.snackbar_message = "Category "+this.editedItem.title+" successfully created.";
                        this.snackbar = true;
                        this.close()
                    });
                }
            }
        }
    }
</script>
