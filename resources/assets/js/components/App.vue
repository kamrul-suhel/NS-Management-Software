<template>
    <div id="app">
        <v-app
                dark
                id="inspire"
        >
            <navigation-component v-if="login"></navigation-component>
            <header-component v-if="login"></header-component>
            <v-content>
                <v-container fill-height :class="{'fill-height justify-center align-content-center': !login}">
                    <v-layout >
                        <v-flex>
                            <input type="text" hidden data-barcode="barcode"/>
                            <router-view></router-view>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-content>
        </v-app>

        <v-dialog v-model="barcodeDialog" persistent max-width="290">
            <v-card>
                <v-card-title class="headline">You scanned a Barcode</v-card-title>
                <v-card-text>Your barcode is : {{ barcode }}</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="green darken-1" flat @click.native="onbarcodeDialogClose()">Ok</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

    </div>
</template>

<script>
    import  HeaderComponent  from '../components/layout/HeaderComonent.vue';
    import  NavigationComponent  from '../components/layout/NavigationComponent.vue';
    import  LoginEventBus  from '../event_bus/login-event-bus';

    export default {
        name: 'App',
        components: {
            NavigationComponent,
            HeaderComponent,
        },

        data: () => ({
            login:false,
            barcodeDialog: false,
            barcode:''
        }),

        props: {
            source: String
        },

        created(){

            axios.get('/islogin').then((response) => {
                if(!response.data.error){
                    this.login = true;
                    this.$store.commit('setUser', response.data);

                    let route = this.$route.name;
                    if(route != 'login'){
                        this.$router.push({name: route});
                        return;
                    }
                    this.$router.push({name: 'home'})
                }
            })

            LoginEventBus.$on('successLogin', ()=> {
                this.login = true;
                this.$router.push({name: 'home'})
            });

            LoginEventBus.$on('logoutChangeState', () => {
                this.login = false;
            });

            //Barcode scannser
            this.$barcodeScanner.init(this.onBarcodeScanned);
        },

        methods: {
            onBarcodeScanned(code){
                console.log(code);
                console.log('barcode scanned');
                this.barcodeDailog = true;
                this.barcode = code;
                if(code !== ''){
                    this.barcodeDailog = true;
                    this.barcode = code;
                }
            },

            onbarcodeDialogClose(){
              this.barcodeDailog = false;
              this.code = '';
            },

            // Reset to the last barcode before hitting enter (whatever anything in the input box)
            resetBarcode () {
                let barcode = this.$barcodeScanner.getPreviousCode()
                // do something...
            }
        }
    }
</script>
