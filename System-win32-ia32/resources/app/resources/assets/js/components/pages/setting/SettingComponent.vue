<template>
    <section class="setting-page">
        <v-container grid-list-xl>
            <v-card>
                <v-card-title>
                    <h2>Company Information</h2>
                    <v-spacer></v-spacer>

                </v-card-title>

                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs6>
                            <v-text-field
                                    label="Company Name"
                                    v-model="company_name"
                                    hint="Name of company">

                            </v-text-field>
                        </v-flex>

                        <v-flex xs6>
                            <v-text-field
                                label="Company Shop number"
                                hint="Enter you shop number"
                                v-model="company_shop_number"></v-text-field>
                        </v-flex>

                        <v-flex xs12>
                            <v-text-field
                                    label="Address"
                                    multi-line
                                    v-model="company_address"
                                    hint="Address of company">
                            </v-text-field>
                        </v-flex>

                        <v-flex xs6>
                            <v-text-field
                                    label="Phone"
                                    v-model="company_phone"
                                    hint="Company phone number">
                            </v-text-field>
                        </v-flex>

                        <v-flex xs6>
                            <v-text-field
                                    label="Mobile"
                                    v-model="company_mobile"
                                    hint="Company mobile number">
                            </v-text-field>
                        </v-flex>

                        <v-flex xs6>
                            <v-text-field
                                label="Email"
                                hint="Company email"
                                v-model="company_email"></v-text-field>
                        </v-flex>

                        <v-flex xs6>
                            <v-text-field
                                    label="Fax"
                                    v-model="company_fax"
                                    hint="Company fax"></v-text-field>
                        </v-flex>

                        <v-flex xs6>
                            <v-text-field
                                    label="Company website"
                                    v-model="company_website"
                                    hint="Company website"></v-text-field>
                        </v-flex>

                        <v-flex xs6>
                            <v-text-field
                                    label="Company logo"
                                    v-model="company_logo"
                                    hint="Company logo"></v-text-field>
                        </v-flex>


                    </v-layout>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                            dark
                            raised
                            color="dark"
                            @click="onUpdateSetting()">Update company info
                    </v-btn>
                </v-card-actions>
            </v-card>
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

            snackbar: false,
            snackbar_message: '',

            company_name: '',
            company_address: '',
            company_email: '',
            company_phone: '',
            company_mobile: '',
            company_fax: '',
            company_website:'',
            company_logo:'',
            company_shop_number:'',
            setting_id:'',

        }),

        computed: {},

        watch: {},

        created() {
            this.initialize()
        },

        methods: {
            initialize() {
                axios.get('/settings')
                    .then((response) => {
                        console.log(response);
                        this.company_name = response.data.company_name;
                        this.company_address = response.data.company_address;
                        this.company_email = response.data.company_email;
                        this.company_phone = response.data.company_phone;
                        this.company_shop_number = response.data.company_shop_number;
                        this.company_mobile = response.data.company_mobile;
                        this.company_fax = response.data.company_fax;
                        this.setting_id = response.data.id;
                        this.company_website = response.data.company_website;
                        this.company_logo = response.data.company_logo;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },

            onUpdateSetting() {
                let form = new FormData();
                let url = 'settings/'+this.setting_id;
                console.log(url);

                form.append('company_name', this.company_name);
                form.append('company_address', this.company_address);
                form.append('company_email', this.company_email);
                form.append('company_phone', this.company_phone);
                form.append('company_mobile', this.company_mobile);
                form.append('company_shop_number', this.company_shop_number);
                form.append('company_fax', this.company_fax);
                form.append('company_website', this.company_website);
                form.append('company_logo', this.company_logo);
                form.append('_method', 'PATCH');


                axios.post(url, form)
                    .then((response) => {
                        console.log(response);

                        form.append('company_name', this.company_name);
                        form.append('company_address', this.company_address);
                        form.append('company_email', this.company_email);
                        form.append('company_phone', this.company_phone);
                        form.append('company_shop_number', this.company_shop_number);
                        form.append('company_fax', this.company_fax);
                        form.append('company_website', this.company_website);
                        form.append('company_logo', this.company_logo);
                        form.append('_method', 'PATCH');

                        this.snackbar_message = "Setting data update successfully.";
                        this.snackbar = true;
                    });
            }
        }
    }
</script>