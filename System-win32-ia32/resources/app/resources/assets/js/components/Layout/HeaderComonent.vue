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
                <span class="title">NS Software</span>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-layout row align-center style="max-width: 650px">
                <v-text-field
                        placeholder="Search..."
                        single-line
                        prepend-icon="search"
                        color="white"
                        hide-details
                ></v-text-field>
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
    export default {
        data() {
            return {
                snackbar: false,
                text:'',
                timeout: 2000
            }
        },

        created() {
            TransactionEventBus.$on('productCreate', (message) =>{
                this.text = message;
                this.snackbar = true;
            });
        },

        methods: {

        }
    }
</script>
