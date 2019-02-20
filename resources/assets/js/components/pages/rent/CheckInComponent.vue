<template>
    <div class="transaction-component">
        <v-container grid-list-md>
            <v-layout row wrap>
                <v-flex xs12>
                    <h2>Check in</h2>
                </v-flex>

                <v-flex xs12>
                    <v-card>
                        <v-card-text>
                            <v-form ref="checkin_form"
                                    v-model="valid"
                                    lazy-validation>
                                <v-layout row wrap>
                                    <v-flex xs6>
                                        <v-select
                                                required
                                                dark
                                                color="dark"
                                                label="Select room"
                                                v-model="selectedRoom"
                                                :items="items"
                                                item-text="room_number"
                                                item-value="id">
                                        </v-select>
                                    </v-flex>

                                    <v-flex xs6 d-flex align-center align-content-center>
                                        <span class="display-1 align-center">{{ selectedRoomPrice ? 'Price: TK.' + selectedRoomPrice : '' }}</span>
                                    </v-flex>

                                    <v-flex xs6>
                                        <v-text-field
                                                required
                                                :rules="[v => !!v || 'Name is required']"
                                                dark
                                                color="dark"
                                                v-model="name"
                                                label="Name"></v-text-field>
                                    </v-flex>

                                    <v-flex xs6>
                                        <v-text-field
                                                required
                                                :rules="[v => !!v || 'Father name is required']"
                                                dark
                                                v-model="fatherName"
                                                color="dark"
                                                label="Father name"></v-text-field>
                                    </v-flex>

                                    <v-flex xs6>
                                        <v-text-field
                                                required
                                                :rules="[v => !!v || 'National insurance or password is required']"
                                                dark
                                                v-model="niNumber"
                                                color="dark"
                                                label="National insurance / passport number"></v-text-field>
                                    </v-flex>

                                    <v-flex xs6>
                                        <v-text-field
                                                required
                                                :rules="[v => !!v || 'Address is required']"
                                                dark
                                                v-model="address"
                                                color="dark"
                                                label="Address"></v-text-field>
                                    </v-flex>

                                    <v-flex xs6>
                                        <v-text-field
                                                required
                                                :rules="[v => !!v || 'Phone is required']"
                                                dark
                                                v-model="phone"
                                                color="dark"
                                                label="Phone"></v-text-field>
                                    </v-flex>

                                    <v-flex xs6>
                                        <v-text-field
                                                required
                                                :rules="[v => !!v || 'Price is required']"
                                                dark
                                                v-model="advance"
                                                color="dark"
                                                label="Advance"></v-text-field>
                                    </v-flex>

                                    <v-flex xs12>
                                        <h3>Check in date</h3>
                                        <v-date-picker
                                                required
                                                :rules="[v => !!v || 'Date is required']"
                                                color="dark"
                                                v-model="checkin"
                                                :min="min && min"
                                                full-width
                                                landscape
                                                class="mt-3"
                                        ></v-date-picker>
                                    </v-flex>
                                </v-layout>
                            </v-form>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn dark
                                   color="dark"
                                   raised
                                   :disabled="!valid"
                                   @click.native="save">Check in
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
    </div>
</template>
<script>
    import axios from 'axios'

    import {mapGetters} from 'vuex'

    export default {
        data: () => ({
            dialog: false,
            checkin: '',
            name: '',
            fatherName: '',
            niNumber: '',
            address: '',
            phone: '',
            advance: 0,
            selectedRoom: 0,
            min: '',
            items: [],
            selectedRoomPrice: 0,
            valid: true,

            snackbar: false,
            snackbar_message: '',
        }),

        computed: {
            ...mapGetters({
                selectedShop: 'getSelectedShop',
                userId: 'getUserId'
            }),
        },

        watch: {
            dialog(val) {
                val || this.close()
            },

            selectedShop(value) {
                this.initialize();
            },

            selectedRoom(id){
                const selectRoom = this.items && this.items.filter((room) => room.id === id);
                this.selectedRoomPrice = selectRoom[0].price && selectRoom[0].price;
            }
        },

        created() {
            this.initialize();
            this.checkin = this.getDate();
            this.min = this.getDate();
        },

        methods: {
            initialize() {
                // get all transaction
                if (this.selectedShop.id) {
                    this.selectedShop.id && axios.get('/api/rooms?shopId=' + this.selectedShop.id + '&check_in=true')
                        .then((response) => {
                            if (response.data) {
                                this.items = [...response.data];
                            }
                        })
                        .catch((error) => {
                            console.log(error)
                        })
                }
            },

            openDeleteDialog(deleteItem) {
                this.deleteItem = deleteItem;
                this.deleteDialog = true;
            },

            getDate() {
                let today = new Date();
                let dd = today.getDate();
                let mm = today.getMonth() + 1;
                let yyyy = today.getFullYear();

                if (dd < 10) {
                    dd = '0' + dd
                }

                if (mm < 10) {
                    mm = '0' + mm
                }

                return yyyy + '-' + mm + '-' + dd;
            }

            ,

            save() {
                if (!this.$refs.checkin_form.validate()) {
                    return;
                }

                let form = new FormData();
                let url = '/api/rent';

                form.append('room_id', this.selectedRoom);
                form.append('hotel_id', this.selectedShop.id);
                form.append('staff_id', this.userId);
                form.append('name', this.name);
                form.append('father_name', this.fatherName);
                form.append('ni_number', this.niNumber);
                form.append('address', this.address);
                form.append('phone', this.phone);
                form.append('advance', this.advance);
                form.append('checkin', this.checkin);

                // create product
                axios.post(url, form)
                    .then((response) => {
                        this.snackbar_message = this.name + ' successfully rented.';
                        this.snackbar = true;
                        setTimeout(() => {
                            this.$refs.checkin_form.reset();
                            this.$router.push({name: 'rooms'})
                        }, 1000)

                    })

            },

            close() {
                this.dialog = false
                this.selectedCompany = '',
                    setTimeout(() => {
                        this.editedItem = Object.assign({}, this.defaultItem);
                        this.editedIndex = -1
                    }, 300)
            }
        }
    }
</script>
