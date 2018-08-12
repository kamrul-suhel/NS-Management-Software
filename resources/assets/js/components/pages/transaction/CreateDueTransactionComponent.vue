<template>
    <section class="create-transaction">
        <v-container grid-list-lg>
            <v-layout row wrap>
                <v-card
                        raised
                        width="100%">
                    <v-card-text>
                        <h2>Create Due Transaction</h2>
                        <v-container grid-list-md>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-autocomplete
                                            dark
                                            color="dark"
                                            label="Select Customer"
                                            :items="customers"
                                            v-model="selectedCustomer"
                                            append-icon="account_circle"
                                            chips
                                            persistent-hint
                                            return-object
                                    >
                                    </v-autocomplete>
                                </v-flex>

                                <v-flex xs12 v-if="previousDue > 0">
                                    <p class="red--text">This customer has {{ previousDue }} due.</p>
                                </v-flex>

                                <v-flex xs6>
                                    <v-text-field
                                            dark
                                            color="dark"
                                            label="Amount of pay"
                                            v-model="paid">
                                    </v-text-field>
                                </v-flex>

                                <v-flex xs6>
                                    <v-text-field
                                            dark
                                            color="dark"
                                            label="New amount due"
                                            disabled
                                            v-model="newAmountDue"
                                    ></v-text-field>
                                </v-flex>
                            </v-layout>

                            <v-layout row wrap>
                                <v-flex xs12 class="text-xs-right">
                                    <v-btn dark color="dark" raised @click.native="onCancelTransaction()">Cancel</v-btn>

                                    <v-btn dark raised @click="onCreateTransaction()">
                                        Create transaction
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-layout>
        </v-container>


    </section>
</template>

<script>
    import TransactionEventBus from '../../../event_bus/transaction_event';
    export default {
        data: () => ({
            customers: [{text: 'No customer', value: 1}],
            selectedCustomer:{},
            payment_due:'',
            paid: 0,
            previousDue: 0,
            newAmountDue:0,
        }),

        computed: {
        },

        watch: {
            selectedCustomer(val) {
                let url = '/transaction/due/create?customer_id='+val.value;
                axios.get(url).then((response)=>{
                    this.previousDue = response.data.previousDue;
                })
            },

            paid(val){
                this.newAmountDue = this.previousDue - val;
            }
        },

        created() {
            this.initialize();
        },

        methods: {
            initialize() {
                // get all customers
                axios.get('/customers')
                    .then((response) => {
                        if(response.data.length > 0){
                            this.customers = response.data;
                            var array_customer = [];
                            this.customers.forEach((customer)=> {
                                var customer = { text: customer.name, value : customer.id};
                                array_customer.push(customer);
                            })
                            this.customers = array_customer;
                        }


                    })
                    .catch((error) => {
                        console.log(error)
                    });

            },

            onCreateTransaction(){
                let form = new FormData()
                let url = '/api/customer/'+this.selectedCustomer.value+'/due/transactions';

                form.append('paid', this.paid);
                form.append('due', this.newAmountDue);

                axios.post(url, form)
                    .then((response)=>{
                        if(response.data){
                            TransactionEventBus.createProduct('Customer due Transaction successfully created');
                            this.$router.push({'name': 'transaction'});
                        }
                    });
            },

            onCancelTransaction(){
              this.$router.push({name: 'transaction'});
            }
        }
    }
</script>
