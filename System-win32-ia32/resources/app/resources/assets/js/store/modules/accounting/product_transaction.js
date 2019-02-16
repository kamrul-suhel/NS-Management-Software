const state = {
    tPaymentDue: 0,
    tPaid: 0,
    tDiscount: 0,
    tTotal:0,
    tChartData: '',
    tTableData: '',
    chartTitle:'',

    expense:'',
    profit:'',
    afterExpenseProfit:'',
    afterDueProfit: 0,

}

const getters = {
    getTPaymentDue(state) {
        return state.tPaymentDue;
    },

    getTPaid(state) {
        return state.tPaid;
    },

    getTDiscount(state) {
        return state.tDiscount;
    },

    getTTotal(state) {
        return state.tTotal;
    },

    getTChartData(state) {
        return state.tChartData;
    },

    getTTableData(state) {
        return state.tTableData;
    },
    getChartTitle(state){
        return state.chartTitle;
    },

    getExpense(state){
        return state.expense;
    },

    getProfit(state){
        return state.profit;
    },

    getAfterExpenseProfit(state){
        return state.afterExpenseProfit;
    },

    getAfterDueProfit(state){
        return state.afterDueProfit;
    }
}

const mutations = {
    setTPaymentDue(state, value){
        state.tPaymentDue = value;
    },

    setTPaid(state, value) {
        state.tPaid = value;
    },

    setTDiscount(state, value) {
        state.tDiscount = value;
    },

    setTTotal(state, value) {
        state.tTotal = value;
    },

    setTChartData(state, value) {
        state.tChartData = value;
    },

    setTTableData(state, value) {
        state.tTableData = value;
    },

    setResetTAll(state) {
        state.tPaymentDue = '';
        state.tPaid = '';
        state.tDiscount = '';
        state.tTotal = '';
        state.chartData = '';
        state.expense = '';
        state.profit = '';
        state.afterExpenseProfit = ''
    },

    setChartTitle(state, value){
        state.chartTitle = value;
    },

    setExpense(state, value){
        state.expense = value;
    },

    setProfit(state, value){
        state.profit = value;
    },

    setAfterExpenseProfit(state, value){
        state.afterExpenseProfit = value;
    },

    setAfterDueProfit(state, value){
        state.afterDueProfit = value;
    }
}

const actions = {
    fetchAllTransaction({commit}, payload) {
        if(payload.select.abbr === 'TDT'){
            commit('setChartTitle', 'Today')
        }

        if(payload.select.abbr === 'YDT'){
            commit('setChartTitle', 'Yesterday')
        }

        if(payload.select.abbr === 'TWT'){
            commit('setChartTitle', 'This Week')
        }

        if(payload.select.abbr === 'LWT'){
            commit('setChartTitle', 'Last Week')
        }

        if(payload.select.abbr === 'TMT'){
            commit('setChartTitle', 'This Month')
        }

        if(payload.select.abbr === 'LMT'){
            commit('setChartTitle', 'Last Month')
        }

        if(payload.select.abbr === 'TYT'){
            commit('setChartTitle', 'This Year')
        }
        if(payload.select.abbr === 'LYT'){
            commit('setChartTitle', 'Last Year')
        }

        if(payload.select.abbr === 'CRT' || payload.select.abbr === 'CDT'){
            commit('setChartTitle', 'Custom Day')
        }

        axios.post('/api/accounting/transaction', payload)
            .then((response) => {
                commit('setTPaymentDue', response.data.payment_due);
                commit('setTPaid',response.data.paid);
                commit('setTDiscount', response.data.discount);
                commit('setTTotal', response.data.total);
                commit('setTChartData', response.data.chart_data);
                commit('setTTableData', response.data.transactions);
                commit('setExpense', response.data.total_expense);
                commit('setProfit', response.data.total_profit);
                commit('setAfterExpenseProfit', response.data.profit_after);
                commit('setAfterDueProfit', response.data.total_profit_after_due);
            });
    }
}

export default {
    state,
    getters,
    mutations,
    actions
}
