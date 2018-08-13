const state = {
    allCustomers:[],
    totalTransaction: 0,
    allTransactions:[],
}

const getters = {
    getAllCustomers(state){
        return state.allCustomers;
    },

    getTotalTransaction(state){
        return state.totalTransaction;
    },

    getAllTransaction(state){
        return state.allTransactions;
    }
}

const mutations = {
    setAllCustomers(state, customers){
        state.allCustomers = customers;
    },

    setTotalTransaction(state, totalTransaction){
        state.totalTransaction = totalTransaction;
    },

    setAllTransaction(state, transactions){
        state.allTransactions = transactions;
    }
}

const actions = {
    fetchAllCustomers({commit}){
        let url = '/api/customers';

        axios.get(url).then((response)=>{
            if(response.data){
                commit('setAllCustomers', response.data);
            }
        })
    },

    fetchSelectedCustomerTransactions({commit}, customer){
        let url = '/customers/'+customer.id+'/transactions';

        axios.get(url).then((response)=> {
            commit('setTotalTransaction', response.data.total_transactions);
            commit('setAllTransaction', response.data.transactions);
        });
    }
}

export default {
    state,
    getters,
    mutations,
    actions
}
