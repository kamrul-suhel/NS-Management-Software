<template>
    <section class="expense-page">


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

        <v-dialog v-model="dialog" max-width="700px">
            <v-btn
                    dark
                    :color="theme"
                    slot="activator"
                    raised
                    class="mb-2 ml-0">Add Expense</v-btn>

            <v-card>
                <v-card-title>
                    <span class="headline">{{ formTitle }}</span>
                </v-card-title>

                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-text-field
                                        label="Title"
                                        v-model="editedItem.title"
                                        :color="theme"
                                ></v-text-field>
                            </v-flex>

                            <v-flex xs12>
                                <v-textarea
                                        :color="theme"
                                    label="Description"
                                    v-model="editedItem.description"
                                    ></v-textarea>
                            </v-flex>

                            <v-flex xs6>
                                <v-select
                                        :color="theme"
                                        v-model="editedItem.category"
                                        :items="expenseCategories"
                                        item-text="title"
                                        ite-value="id"
                                        label="Select Category"
                                        return-object
                                ></v-select>
                            </v-flex>

                            <v-flex xs6>
                                <v-select
                                        :color="theme"
                                        v-model="editedItem.payment_type"
                                        :items="payment_type"
                                        item-text="text"
                                        item-value="type"
                                        label="Select Payment Type"
                                ></v-select>
                            </v-flex>

                            <v-flex xs6>
                                <v-text-field
                                :color="theme"
                                prefix="TK."
                                label="Amount"
                                type="number"
                                v-model="editedItem.amount">
                                </v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn :color="theme"
                           dark
                           raised
                           @click.native="close">Cancel</v-btn>

                    <v-btn :color="theme" dark raised @click.native="save">{{ buttonTitle }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-card>
            <v-card-title>
                <h2>Expense</h2>
                <v-spacer></v-spacer>
                <v-text-field
                        :color="theme"
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
                        <td class="text-xs-left">TK.{{ props.item.amount }}</td>
                        <td class="text-xs-left">{{ props.item.payment_type }}</td>
                        <td class="text-xs-left">{{ getCategory(props.item)}}</td>
                        <td class="justify-start layout px-0">
                            <v-btn
                                    :color="theme"
                                    icon
                                    class="mx-0"
                                    @click="editItem(props.item)">
                                <v-icon :color="theme">edit</v-icon>
                            </v-btn>
                            <v-btn
                                    :color="theme"
                                    icon
                                    class="mx-0"
                                    @click="deleteExpense(props.item)">
                                <v-icon :color="theme">delete</v-icon>
                            </v-btn>
                        </td>
                    </template>

                    <v-alert slot="no-results" :value="true" color="error" icon="warning">
                        Your search for "{{ search }}" found no results.
                    </v-alert>

                    <template slot="no-data">
                        <v-btn
                                :color="theme"
                                @click="initialize">Reset</v-btn>
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
            deleteDialog: false,
            search:'',
            pagination: {
                sortBy: 'name'
            },
            snackbar: false,
            snackbar_message:'',
            headers: [
                {
                    text: 'Id',
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
                    text: 'Amount',
                    value: 'amount',
                },
                {
                    text: 'Payment Type',
                    value: 'payment_type',
                },

                {
                    text: 'Category',
                    value: 'category',
                },
                {
                    text: 'Action',
                    value: 'action'
                }
            ],
            items: [],
            expenseCategories: [],
            payment_type :['cash','cheque'],
            editedIndex: -1,
            editedItem: {
                id:'',
                title: '',
                description: '',
                payment_type:'cash',
                amount:'',
                category:{}
            },
            defaultItem: {
                id:'',
                title: '',
                description: '',
                payment_type:'cash',
                amount:'',
                category:{}
            },

            deleteItem: {},

            row_per_page: [20, 30, 50, {'text': 'All', 'value': -1}],
        }),

        computed: {

            theme(){
              return this.$store.getters.getTheme;
            },

            formTitle () {
                return this.editedIndex === -1 ? 'New Expense' : 'Edit '+ this.editedItem.title;
            },

            buttonTitle () {
                return this.editedIndex === -1 ? 'Create Expense' : 'Update expense'
            },


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
                axios.get('api/expense')
                .then((response) => {
                    this.items = response.data.expenses;
                    this.expenseCategories = response.data.expense_categories;
                })
                .catch((error) => {
                    console.log(error);
                });
            },

            getCategory(item){
                if(item.category){
                    return item.category.title
                }
                return 'unknown';
            },

            editItem (item) {
                // this.editedItem = item;
                this.editedItem = Object.assign({}, item);
                this.editedIndex = this.items.indexOf(item)
                this.expenseCategories.forEach((item)=> {
                    if(item.id === this.editedItem.category.id){
                        this.editedItem.category = item;
                    }
                });
                this.dialog = true
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
                let url = 'api/expense';

                form.append('title', this.editedItem.title);
                form.append('description', this.editedItem.description);
                form.append('payment_type', this.editedItem.payment_type);
                form.append('amount', this.editedItem.amount);

                if(this.editedItem.category.id){
                    console.log('not category');
                    form.append('expense_categories_id', this.editedItem.category.id);
                }

                if (this.editedIndex > -1) {
                    form.append('_method', 'PUT');
                    url = url +'/'+ this.editedItem.id;

                    axios.post(url, form)
                        .then((response) => {
                            console.log(this.editedItem);
                            console.log(response);
                            Object.assign(this.items[this.editedIndex], this.editedItem);
                            this.snackbar_message = "Expense "+this.editedItem.title+" successfully update.";
                            this.snackbar = true;
                            this.close()
                        })
                    .catch((error)=> {

                    });
                } else {
                    axios.post(url, form)
                    .then((response) => {
                        this.items.push(response.data);
                        this.snackbar_message = "Expense "+this.editedItem.title+" successfully created.";
                        this.snackbar = true;
                        this.close()
                    });
                }

            },

            deleteExpense (item) {
                this.deleteItem = item;
                this.deleteDialog = true;
            },

            deleteItemD () {
                let url = 'api/expense/'+this.deleteItem.id;
                axios.delete(url).then((response) => {
                    this.deleteDialog = false;
                    const index = this.items.indexOf(this.deleteItem)
                    this.items.splice(index, 1)
                });
            },

            changeSort(column) {
                if (this.pagination.sortBy === column) {
                    this.pagination.descending = !this.pagination.descending
                } else {
                    this.pagination.sortBy = column
                    this.pagination.descending = false
                }
            },
        }
    }
</script>
