<template>
    <div>
        <v-toolbar
                fixed
                clipped-left
                app
                color="dark"
                dark
        >
            <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
            <v-icon class="mx-3">fab fa-youtube</v-icon>
            <v-toolbar-title class="mr-5 align-center">
                <span class="title">NS Software - {{ selectedShop ? selectedShop.name : '' }}</span>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-layout row align-center style="max-width: 350px">
                <v-select
                        color="white"
                        placeholder="Select a shop"
                        prepend-icon="search"
                        v-model="selectedShop"
                        :items="shops"
                        item-text="name"
                        item-value="id"
                        return-object
                ></v-select>
            </v-layout>
        </v-toolbar>


        <v-snackbar
                v-model="snackbar"
                right
                :timeout="timeout"
                top
                color="green"
        >
            {{ text }}
            <v-btn
                    color="white"
                    flat
                    @click="snackbar = false"
            >
                Close
            </v-btn>
        </v-snackbar>
    </div>
</template>

<script>
    import TransactionEventBus from '../../event_bus/transaction_event'
    import {mapGetters} from 'vuex';
    export default {
        data() {
            return {
                snackbar: false,
                text:'',
                timeout: 2000,
            }
        },

        computed: {
            ...mapGetters({
                shops : 'getShops'
            }),

            selectedShop: {
                get(){
                    return this.$store.getters.getSelectedShop
                },

                set(value){
                    const updateStore = {
                        'user_id' :  this.$store.getters.getUserId,
                        'store_id' : value.id
                    }

                    axios.post('/api/users', updateStore)

                    this.$store.commit('setSelectedShop', value.id)
                }
            }
        },

        created() {
            this.getShop(1);

            TransactionEventBus.$on('productCreate', (message) =>{
                this.text = message;
                this.snackbar = true;
            });
        },

        methods: {
            getShop(){
                this.$store.dispatch('fetchShop', 1);
            }
        }
    }
</script>
