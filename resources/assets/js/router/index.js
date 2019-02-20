import Vue from 'vue'
import Router from 'vue-router'
import RoomsComponent from '../components/pages/rooms/RoomsComponent'
import RentsComponent from '../components/pages/rent/RentsComponent'
import CheckInComponent from '../components/pages/rent/CheckInComponent'
import CheckOutComponent from '../components/pages/rent/CheckInOutComponent'
import CustomerComponent from '../components/pages/customer/CustomerComponent'
import ShopComponent from '../components/pages/shop/ShopComponent'
import LoginComponent from '../components/pages/login/LoginComponent'
import ExpenseComponent from '../components/pages/expense/ExpenseComponent'
import ExpenseCategoryComponent from '../components/pages/expense/ExpenseCategoryComponent'
import CompanyComponent from '../components/pages/company/CompanyComponent'
import CompanyTransaction from '../components/pages/company/CompanyTransitionsComponent'
import AccountExpenseComponent from '../components/pages/accounting/expense/ExpenseAccountingComponent'
import ProductTransaction from '../components/pages/accounting/product_transaction/TransactionAccountingComponent'
import CustomerAccountingTransaction from '../components/pages/accounting/customer/CustomerAccountingComponent'
import UserComponent from '../components/pages/user/UserComponent'



Vue.use(Router)

const routes = [
    {
        path: '/',
        name: 'login',
        component: LoginComponent
    },

    {
        path: '/home',
        name: 'home',
        component: ProductTransaction
    },


    {
        path: '/rooms',
        name: 'rooms',
        component: RoomsComponent
    },

    {
        path: '/rents',
        name: 'rents',
        component: RentsComponent
    },

    {
        path: '/checkin',
        name: 'checkin',
        component: CheckInComponent
    },

    {
        path: '/checkout',
        name: 'checkout',
        component: CheckOutComponent
    },

    {
        path: '/customers',
        name: 'customers',
        component: CustomerComponent
    },
    {
        path: '/customers/:id/transitions',
        name: 'customers_transitions',
        component: CustomerComponent
    },

    {
        path: '/expense',
        name: 'expense',
        component: ExpenseComponent
    },

    {
        path: '/company',
        name: 'company',
        component: CompanyComponent
    },

    {
        path: '/companytransaction',
        name: 'ctransaction',
        component: CompanyTransaction
    },

    {
        path: '/expensecategories',
        name: 'expense_categories',
        component: ExpenseCategoryComponent
    },

    {
        path: '/account/transaction',
        name:'accounting_product_transaction',
        component: ProductTransaction
    },

    {
        path: '/account/customer',
        name:'accounting_customer_transaction',
        component: CustomerAccountingTransaction
    },

    {
        path: '/account/expense',
        name:'account_expense',
        component: AccountExpenseComponent
    },

    {
        path: '/shops',
        name: 'shops',
        component: ShopComponent
    },

    {
        path: '/user',
        name: 'user',
        component: UserComponent
    }
]

export default new Router({
    mode: 'history',
    routes
})
