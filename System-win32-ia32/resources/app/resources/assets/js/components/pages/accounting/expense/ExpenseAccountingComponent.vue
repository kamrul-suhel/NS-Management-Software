<template>
    <v-container grid-list-lg>
        <v-layout row wrap>
            <v-flex xs12>

                <chart-component></chart-component>

            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import ChartComponent from '../../../Partials/ChartComponent'
    export default {
        data: () => ({

        }),
        components: {
            chartComponent: ChartComponent
        },

        computed: {

        },

        watch: {

        },

        created () {
            console.log('expense');
        },

        methods: {
        }
    }
</script>