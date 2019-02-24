<template>
    <section class="expense-categories-page">
        <v-dialog v-model="dialog" persistent max-width="290">
            <v-card color="error">
                <v-card-text>
                    <div class="text-xs-center"><v-icon color="white" size="50">warning</v-icon></div>
                    <p class="text-xs-center">Are you sure you want to delete {{deleteItem.name}} {{ deleteItem.email}}</p>
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
                        <h2>All user</h2>
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
                                <td class="text-xs-left">{{ props.item.name }}</td>
                                <td class="text-xs-left">{{ props.item.email }}</td>
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
                                            label="Name"
                                            v-model="editedItem.name"
                                            dark></v-text-field>
                                </v-flex>

                                <v-flex xs12>
                                    <v-text-field
                                            label="email"
                                            color="dark"
                                            v-model="editedItem.email"
                                    ></v-text-field>
                                </v-flex>

                                <v-divider></v-divider>
                                <v-flex xs12>
                                    <h3>{{ passwordTitle }}</h3>
                                    <v-divider style="margin-top:20px;"></v-divider>
                                </v-flex>


                                <v-flex xs12>
                                    <small style="color:red" v-if="error && errors.password !== undefined">{{ errors.password[0] }}</small>
                                    <v-text-field
                                            dark
                                            name="password"
                                            color="dark"
                                            label="Enter your new password"
                                            hint="At least 8 characters"
                                            v-model="password"
                                            :append-icon="passwordType ? 'visibility' : 'visibility_off'"
                                            @click:append="passwordType = !passwordType"
                                            :type="passwordType ? 'password' : 'text'"
                                            :counter="counter"
                                            required
                                    ></v-text-field>
                                </v-flex>

                                <v-flex xs12>
                                    <v-text-field
                                            dark
                                            name="password"
                                            color="dark"
                                            label="Confirm your new password"
                                            v-model="confirm_password"
                                            :append-icon="passwordTypeConfirm ? 'visibility' : 'visibility_off'"
                                            @click:append="passwordTypeConfirm = !passwordTypeConfirm"
                                            :type="passwordTypeConfirm ? 'password' : 'text'"
                                            :counter="counter"
                                            :error-messages="passwordChange && !formValidate ? 'Password is not match': ''"
                                            required
                                            @keyup.enter="onPasswordResetSubmit()"
                                    ></v-text-field>
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
    import  LoginEventBus  from '../../../event_bus/login-event-bus';
    export default {
        data: () => ({

            //user info
            users:[],

            user: {},
            password:'',
            confirm_password:'',

            showpassword: true,
            valid: false,
            login_progress: false,

            emailRules: [
                v => !!v || 'Email is required',
                v => /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'Email must be valid'
            ],
            passwordType: true,
            passwordTypeConfirm: true,

            counter:30,
            passwordRules: [
                (v) => {
                    return !!v || 'Password is required'
                }
            ],
            passwordConfirmationRules: [
                (v) => !!v || 'Confirmation password is required'
            ],

            oldPassword: '',

            newPasswordError:false,
            newConfirmPasswordError:false,

            showMessage: false,
            message: '',
            error: false,
            errors: [],

            formValidate: false,
            passwordChange: false,

            oldEmail: '',
            //End user info





            dialog: false,
            search:'',
            snackbar: false,
            snackbar_message:'',
            headers: [
                {
                    text: 'Id',
                    value: 'id',
                    sortable: true
                },
                {
                    text: 'Name',
                    value: 'name',
                    sortable: true
                },
                {
                    text: 'Email',
                    value: 'email',
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
                email: '',
                password:'',
            },
            defaultItem: {
                name: '',
                email: '',
            },
            row_per_page: [10, 30, 50, {'text': 'All', 'value': -1}],

            deleteItem:{},
        }),

        computed: {
            theme(){
                this.$store.getters.getTheme;
            },
            formTitle () {
                return this.editedIndex === -1 ? 'Add new user' : 'Edit user'
            },

            buttonTitle () {
                return this.editedIndex === -1 ? 'Create User' : 'Update User'
            },

            passwordTitle () {
                return this.editedIndex === -1 ? 'Create new password' : 'Reset password'
            }
        },

        created () {
            this.initialize()
        },

        methods: {
            initialize () {
                axios.get('api/users')
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
                let url = 'api/users/'+this.deleteItem.id;
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

            save() {
                let form = new FormData();
                let url = '/api/users';

                form.append('name', this.editedItem.name);
                form.append('email', this.editedItem.email);

                if(this.password !== '' && this.confirm_password !== ''){
                    form.append('_method', "POST");
                    form.append('password', this.password);
                    form.append('password_confirmation', this.confirm_password);
                }

                if(this.editedIndex != -1){
                    url += '/'+this.editedItem.id
                    form.append('_method', "PATCH");
                }


                // create product
                axios.post(url, form)
                    .then((response) => {

                        if(response.data){
                            this.snackbar_message = 'User '+response.data.name + ' successfully updated.';
                            this.snackbar = true;
                            setTimeout(()=> {
                                console.log(response.data);
                                if (this.editedIndex > -1) {
                                    Object.assign(this.items[this.editedIndex], response.data);
                                    this.editedItem = Object.assign({}, this.defaultItem);
                                } else {
                                    this.items.push(response.data);
                                    this.editedItem = Object.assign({}, this.defaultItem);
                                }
                            }, 3000)

                        }
                    })
            }
        }
    }
</script>
