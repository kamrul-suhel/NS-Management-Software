<template>
    <v-container grid-list-lg>
        <v-layout row wrap>
            <v-flex xs12>
                <h2>Transaction accounting</h2>
                <v-divider class="mb-3 dark"></v-divider>
            </v-flex>
        </v-layout>

        <search-component></search-component>

        <chart-component></chart-component>

        <over-view-component></over-view-component>

        <table-data></table-data>

    </v-container>
</template>

<script>
    import SearchComponent from '../partials/SearchComponent'
    import OverViewComponent from '../partials/OverviewComponent'
    import ChartComponent from '../../../Partials/ChartComponent'
    import TableData from '../partials/TransactionOverviewComponent'
    import {mapActions} from 'vuex';
    export default {

        data: () => ({
        }),
        components: {
            chartComponent: ChartComponent,
            SearchComponent,
            OverViewComponent,
            TableData
        },

        computed: {

        },

        watch: {

        },

        created () {
            // this.getTransaction()
        },

        methods: {
            ...mapActions({
                getTransaction: 'fetchAllTransaction'
            }),
        },

        destroyed(){
            this.$store.commit('setResetTAll');
        }
    }
</script>
