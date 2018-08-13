<template>

    <v-navigation-drawer
    fixed
    clipped
    v-model="drawer"
    app
    width="250"
    >
        <v-list>
            <v-list-tile>
                <v-list-tile-action>
                    <v-icon>home</v-icon>
                </v-list-tile-action>
                <v-list-tile-title>Home</v-list-tile-title>
            </v-list-tile>

            <v-list-group
                    dark
                    color="dark"
                    v-for="(navs, index) in items"
                    :key="index"
                    :prepend-icon="navs.icon"
            >
                <v-list-tile slot="activator">
                    <v-list-tile-title v-text="navs.text"></v-list-tile-title>
                </v-list-tile>

                <v-list-tile
                        v-for="(nav, i) in navs.navs"
                        :key="i"
                        @click="onPageChange(nav)"
                >
                    <v-list-tile-title v-text="nav.text"></v-list-tile-title>
                    <v-list-tile-action>
                        <v-icon v-text="nav.icon"></v-icon>
                    </v-list-tile-action>
                </v-list-tile>
            </v-list-group>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
    import  LoginEventBus  from '../../event_bus/login-event-bus';
    export default {
        data() {
            return {
                drawer: true,
                items: [
                    {
                        icon: 'add_shopping_cart',
                        text: 'Products',
                        navs: [
                            {
                                icon: 'history',
                                text: 'All Products',
                                link:'products'
                            },

                            {
                                icon: 'subscriptions',
                                text: 'Categories',
                                link:'categories'
                            }
                        ],
                    },

                    {
                        icon: 'compare_arrows',
                        text: 'Transitions',
                        navs: [
                            {
                                icon: 'compare_arrows',
                                text: 'Transitions',
                                link: 'transaction'
                            }
                        ]
                    },
                    {
                        icon: 'people',
                        text: 'Customers',
                        navs: [
                            {
                                icon: 'people',
                                text: 'Customers',
                                link: 'customers'
                            },

                            {
                                icon: 'people',
                                text: 'Customer Laser',
                                link: 'accounting_customer_transaction'
                            }

                        ]
                    },

                    {
                        icon: 'assignment',
                        text: 'Expenses',
                        navs: [
                            {
                                icon: 'assignment',
                                text: 'Expenses',
                                link: 'expense'
                            },

                            {
                                icon: 'category',
                                text: 'Ex Categories',
                                link: 'expense_categories'
                            }

                        ]
                    },

                    {
                        icon: 'store',
                        text: 'Company',
                        navs: [
                            {
                                icon: 'store',
                                text: 'Companies',
                                link: 'company'
                            },

                            {
                                icon: 'compare_arrows',
                                text: 'C. Transaction',
                                link: 'ctransaction'
                            }

                        ]
                    },

                    {
                        icon: 'timeline',
                        text: 'Accounting',
                        navs: [
                            {
                                icon: 'transform',
                                text: 'A Transaction',
                                link: 'accounting_product_transaction'
                            },
                            {
                                icon: 'timeline',
                                text: 'A Expense',
                                link: 'account_expense'
                            },

                            {
                                icon: 'timeline',
                                text: 'A Product',
                                link: 'expenses'
                            }
                        ]
                    },

                    {
                        icon: 'settings',
                        text: 'Settings',
                        navs: [
                            {
                                icon: 'settings',
                                text: 'Settings',
                                link: 'settings'
                            }
                        ]
                    },

                    {
                        icon: 'settings_power',
                        text: 'Log out',
                        navs: [
                            {
                                icon: 'settings_power',
                                text: 'Log out',
                                link: 'logout'
                            }
                        ]
                    }
                ],
            }
        },

        methods : {
            onPageChange(item){
                if(item.link === 'logout'){
                    axios.get('/logout').then(() => {
                        LoginEventBus.logoutStateChange();
                        this.$router.push({name: 'login'});
                    });
                    return;
                }
                this.$router.push({name: item.link});
            },

            onCloseWindow(){
                window.close();
            }
        }
    }
</script>
