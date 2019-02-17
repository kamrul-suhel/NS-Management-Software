const state = {
    theme:'dark'
};

const mutations = {
    setTheme(state, theme){
        state.theme = theme;
    }
}

const getters = {
    getTheme(state){
       return state.theme;
    }
}

const actions = {

}

export default  {
    state,
    mutations,
    getters,
    actions
}