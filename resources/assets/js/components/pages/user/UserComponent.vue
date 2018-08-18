<template>
    <div class="user">
        <v-container grid-list-md>
            <v-layout row wrap>
                <v-flex xs12>
                    <h2>User Setup</h2>
                </v-flex>
            </v-layout>

            <v-divider class="mb-3 dark"></v-divider>
        </v-container>

        <v-container grid-list-lg>
            <v-layout row wrap>
                <v-card
                        raised
                        width="100%">

                    <v-card-text>
                        <v-form>
                        <v-layout row wrap>
                            <v-flex xs12>
                                <v-text-field
                                        dark
                                        color="dark"
                                        label="User name"
                                        v-model="user.name"
                                ></v-text-field>
                            </v-flex>

                            <v-flex xs12>
                                <v-text-field
                                        dark
                                        color="dark"
                                        label="User email"
                                        v-model="user.email"
                                ></v-text-field>
                            </v-flex>

                            <v-divider></v-divider>
                            <v-flex xs12>
                                <h3>Reset your password</h3>
                                <v-divider style="margin-top:20px;"></v-divider>
                            </v-flex>


                            <v-flex xs12>
                                <v-flex xs12>
                                    <v-text-field
                                            dark
                                            class="email"
                                            color="dark"
                                            label="Enter your old password"
                                            v-model="oldPassword"
                                            :append-icon="showpassword ? 'visibility' : 'visibility_off'"
                                            @click:append="showpassword = !showpassword"
                                            :type="showpassword ? 'password' : 'text'"
                                            :rules="passwordRules"
                                            @keyup.enter="onSubmit()"
                                            required
                                    ></v-text-field>
                                </v-flex>
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

                            <v-flex xs12 class="text-xs-right">
                                <v-btn
                                    dark
                                    @click="save()"
                                    color="dark">
                                    Update User
                                </v-btn>
                            </v-flex>
                        </v-layout>
                        </v-form>
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
    import  LoginEventBus  from '../../../event_bus/login-event-bus';

    export default {
        data: () => ({
            user: {},
            snackbar: false,
            snackbar_message: '',
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

            oldEmail: ''

        }),

        computed: {

        },

        watch: {
            password(val){
                if(val === this.confirm_password){
                    this.formValidate = true;
                }
            },

            confirm_password(val){
                this.passwordChange = true;
                if(val === this.password){
                    this.formValidate = true;
                }else{
                    this.formValidate = false;
                }
            }
        },

        created() {
            this.initialize()
        },

        methods: {
            initialize() {
                // get all product
                axios.get('/user?id=1')
                    .then((response) => {
                        this.user = response.data;
                        this.oldEmail = this.user.email;
                    })
                    .catch((error) => {
                        console.log(error)
                    })

            },

            save() {
                let form = new FormData();
                let url = '/api/users/'+this.user.id;

                form.append('name', this.user.name);
                form.append('email', this.user.email);

                if(this.password !== '' && this.confirm_password !== ''){
                    form.append('password', this.password);
                }
                form.append('_method', "PATCH");

                // create product
                axios.post(url, form)
                    .then((response) => {
                        if(response.data){
                            this.snackbar_message = 'User '+this.user.name + ' successfully updated.';
                            this.snackbar = true;
                            setTimeout(()=> {
                                if(this.oldEmail !== this.user.email || this.password !== ''){
                                    axios.get('/logout').then(() => {
                                        LoginEventBus.logoutStateChange();
                                        this.$router.push({name: 'login'});
                                    });
                                }
                            }, 3000)
                        }
                    })

            }
        }
    }
</script>
