const state = {
    allCustomers:[]
}

const getters = {
    getAllCustomers(state){
        return state.allCustomers;
    }
}

const mutations = {
    setAllCustomers(state, customers){
        state.allCustomers = customers;
    }
}

const actions = {
    fetchAllCustomers({commit}){
        let url = '/api/customers';

        axios.get(url).then((response)=>{
            console.log(response);
        })
    }
}

export default {
    state,
    getters,
    mutations,
    actions
}
