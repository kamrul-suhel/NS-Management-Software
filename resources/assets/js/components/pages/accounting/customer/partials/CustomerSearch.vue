<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-card>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs6>
                            <v-select
                                    dark
                                    color="white"
                                    v-model="selected"
                                    :items="customers"
                                    item-text="state"
                                    item-value="abbr"
                                    label="Choose a field"
                                    persistent-hint
                                    @input="onSelected()"
                                    return-object
                                    single-line
                            ></v-select>
                        </v-flex>
                    </v-layout>
                </v-card-text>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    import {mapGetters} from 'vuex'
    export default {
        data(){
            return {
                selected: ''
            }
        },

        created(){
            this.$store.dispatch('fetchAllCustomers');
        },

        computed: {
            ...mapGetters({
                customers: 'getAllCustomers'
            })
        },

        methods: {
            onSelected(){

            }
        }
    }
</script>
