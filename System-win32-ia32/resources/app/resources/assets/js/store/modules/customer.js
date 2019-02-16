const state = {
    allCustomers: [],
    totalTransaction: 0,
    allTransactions: [],
    customerTransactionTotal: 0,
    customerTransactionDue: 0
}

const getters = {
    getAllCustomers(state) {
        return state.allCustomers;
    },

    getTotalTransaction(state) {
        return state.totalTransaction;
    },

    getAllTransaction(state) {
        return state.allTransactions;
    },

    getCustomerTransactionTotal(state) {
        return state.customerTransactionTotal;
    },

    getCustomerTransactionDue(state) {
        return state.customerTransactionDue;
    }

}

const mutations = {
    setAllCustomers(state, customers) {
        state.allCustomers = customers;
    },

    setTotalTransaction(state, totalTransaction) {
        state.totalTransaction = totalTransaction;
    },

    setAllTransaction(state, transactions) {
        state.allTransactions = transactions;
    },

    setCustomerTransactionTotal(state, total) {
        state.customerTransactionTotal = total;
    },

    setCustomerTransactionDue(state, due) {
        state.customerTransactionDue = due;
    },

    setResetAllCustomerTransactionData(state) {
        state.allCustomers = [];
        state.totalTransaction = 0;
        state.allTransactions = [];
        state.customerTransactionTotal = 0;
        state.customerTransactionDue = 0;
    }
}

const actions = {
    fetchAllCustomers({commit}) {
        let url = '/api/customers';

        axios.get(url).then((response) => {
            if (response.data) {
                commit('setAllCustomers', response.data);
            }
        })
            .catch(e => {})

    },

    fetchSelectedCustomerTransactions({commit}, customer) {
        let url = '/customers/' + customer.id + '/transactions';

        axios.get(url).then((response) => {
            commit('setTotalTransaction', response.data.total_transactions);
            commit('setAllTransaction', response.data.transactions);
            commit('setCustomerTransactionTotal', response.data.total);
            commit('setCustomerTransactionDue', response.data.due);
        });
    }
}

export default {
    state,
    getters,
    mutations,
    actions
}
