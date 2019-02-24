<template>
    <v-layout row wrap>
        <v-flex xs6>
        <v-card flat class="cyan lighten-1 white--text">
                <v-card-title>Customer due {{ title }}</v-card-title>
                <v-card-text class="pt-0">
                    <h2 class="display-2 white--text text-xs-center">
                        <span style="font-size:12px">TK.</span>
                        <strong>{{paymentDue}}</strong>
                    </h2>
                </v-card-text>
            </v-card>
        </v-flex>

        <!--<v-flex xs6>-->
            <!--<v-card flat class="light-blue white&#45;&#45;text">-->
                <!--<v-card-title>Paid {{ title }}</v-card-title>-->
                <!--<v-card-text class="pt-0">-->
                    <!--<h2 class="display-2 white&#45;&#45;text text-xs-center">-->
                        <!--<span style="font-size:12px">TK.</span>-->
                        <!--<strong>{{paid}}</strong>-->
                    <!--</h2>-->
                <!--</v-card-text>-->
            <!--</v-card>-->
        <!--</v-flex>-->

        <v-flex xs6 v-if="balance">
            <v-card flat class="light-green lighten-1 white--text">
                <v-card-title>Discount {{ title }}</v-card-title>
                <v-card-text class="pt-0">
                    <h2 class="display-2 white--text text-xs-center">
                        <span style="font-size:12px">TK.</span>
                        <strong>{{discount}}</strong>
                    </h2>
                </v-card-text>
            </v-card>
        </v-flex>

        <v-flex xs6 v-if="balance">
            <v-card flat class="orange darken-1 white--text">
                <v-card-title>Total {{ title }}</v-card-title>
                <v-card-text class="pt-0">
                    <h2 class="display-2 white--text text-xs-center">
                        <span style="font-size:12px">TK.</span>
                        <strong>{{total}}</strong>
                    </h2>
                </v-card-text>
            </v-card>
        </v-flex>

        <v-flex xs6 v-if="balance">
            <v-card flat class="primary darken-1 white--text">
                <v-card-title>{{ title }} expenses</v-card-title>
                <v-card-text class="pt-0">
                    <h2 class="display-2 white--text text-xs-center">
                        <span style="font-size:12px">TK.</span>
                        <strong>{{totalExpense}}</strong>
                    </h2>
                </v-card-text>
            </v-card>
        </v-flex>

        <v-flex xs6 v-if="balance">
            <v-card flat class="purple darken-1 white--text">
                <v-card-title>{{ title }} total profit</v-card-title>
                <v-card-text class="pt-0">
                    <h2 class="display-2 white--text text-xs-center">
                        <span style="font-size:12px">TK.</span>
                        <strong>{{totalProfit}}</strong>
                    </h2>
                </v-card-text>
            </v-card>
        </v-flex>

        <v-flex xs6 v-if="balance">
            <v-card flat class="blue darken-1 white--text">
                <v-card-title>{{ title }} net profit</v-card-title>
                <v-card-text class="pt-0">
                    <h2 class="display-2 white--text text-xs-center">
                        <span style="font-size:12px">TK.</span>
                        <strong>{{ totalProfiteAll }}</strong>
                    </h2>
                </v-card-text>
            </v-card>
        </v-flex>

        <v-flex xs6>
            <v-card flat class="cyan lighten-1 white--text">
                <v-card-title>Cash</v-card-title>
                <v-card-text class="pt-0">
                    <h2 class="display-2 white--text text-xs-center">
                        <span style="font-size:12px">TK.</span>
                        <strong>{{cash}}</strong>
                    </h2>
                </v-card-text>
            </v-card>
        </v-flex>

        <v-flex xs6 v-if="!balance">
            <v-card flat class="cyan lighten-1 white--text">
                <v-card-title>Product stock</v-card-title>
                <v-card-text class="pt-0">
                    <h2 class="display-2 white--text text-xs-center">
                        <span style="font-size:12px">TK.</span>
                        <strong>{{productTotalStock}}</strong>
                    </h2>
                </v-card-text>
            </v-card>
        </v-flex>

        <v-flex xs6 v-if="!balance">
            <v-card flat class="blue darken-1 white--text">
                <v-card-title>Gross profit</v-card-title>
                <v-card-text class="pt-0">
                    <h2 class="display-2 white--text text-xs-center">
                        <span style="font-size:12px">TK.</span>
                        <strong>{{grossProfit}}</strong>
                    </h2>
                </v-card-text>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    import {mapGetters} from 'vuex';
    export default {
        data(){
            return {
                totalBalance:0
            }
        },

        props : [
        ],

        computed: {
            ...mapGetters({
                paymentDue: 'getTPaymentDue',
                paid : 'getTPaid',
                discount : 'getTDiscount',
                total : 'getTTotal',
                totalExpense : 'getExpense',
                totalProfit : 'getProfit',
                afterExpenseProfit : 'getAfterExpenseProfit',
                afterDueProfit : 'getAfterDueProfit',
                title:'getChartTitle',
                cash: 'getCash',
                productTotalStock: 'getProductTotalStock',
                balance: 'getBalance',
                grossProfit: 'getGrossProfit'
            }),

            totalProfiteAll(){
                return Number(this.afterExpenseProfit) - Number(this.paymentDue);
            }
        },

        watch: {
        },

        created(){
        },

        methods:{

        }
    }
</script>
