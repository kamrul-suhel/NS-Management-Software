const state = {
    userId : null,
    userName: null,
    userEmail: null
}

const mutations = {
    setUser(state, user){
        state.userId = user.id;
        state.userName = user.name;
        state.userEmail = user.email
    },

    resetUser(state){
        state.userId = null;
        state.userName = null;
        state.userEmail = null
    }
}

const getters = {
    getUserId(state){
        return state.userId;
    },

    getUserName(state){
        return state.userName;
    },

    getUserEmail(state){

    }
}

const actions = {

}

export default {
    state,
    getters,
    mutations,
    actions

}
