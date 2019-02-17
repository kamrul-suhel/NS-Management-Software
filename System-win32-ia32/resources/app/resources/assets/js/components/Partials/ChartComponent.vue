<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-card>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs12>
                            <div id="app2">
                                <div id="chartdiv" style="width: 100%; height: 250px;"></div>
                            </div>
                        </v-flex>
                    </v-layout>
                </v-card-text>
            </v-card>
        </v-flex>

    </v-layout>


</template>

<script>
    import {mapGetters} from 'vuex';
    export default {
        data () {
            return {
            }
        },

        computed:{
            ...mapGetters({
                dataProvider : 'getTChartData',
                title: 'getChartTitle'
            })
        },

        created(){
        },

        watch:{
            dataProvider(){
                this.generateChart()
            }
        },

        mounted(){
            this.generateChart();
        },

        methods:{
            generateChart(){
                    AmCharts.makeChart("chartdiv", {
                        "hideCredits":true,
                        "pathToImages": "/images/",
                        "type": "serial",
                        "theme": "light",
                        "marginRight": 70,
                        "dataProvider":this.dataProvider,
                        "valueAxes": [{
                            "axisAlpha": 1,
                            "axisColor": "#fff",
                            "color":"#fff",
                            "titleColor":"#fff",
                            "position": "left",
                            "title": this.title + ' Transactions',
                        }],

                        "startDuration": 1,
                        "graphs": [{
                            "balloonText": "<b>[[products]]: [[value]]</b>",
                            "fillColorsField": "color",
                            "fillAlphas": 0.9,
                            "lineAlpha": 0.2,
                            "type": "column",
                            "valueField": "total"
                        }],
                        "chartCursor": {
                            "categoryBalloonEnabled": false,
                            "cursorAlpha": 0,
                            "zoomable": true
                        },
                        "categoryField": "date",
                        "categoryAxis": {
                            "gridPosition": "start",
                            "labelRotation": 45,
                            "color":"#fff",
                            "autoGridCount": true,
                        },
                        "export": {
                            "enabled": true
                        }

                    });
            }
        }
    }
</script>

<style lang="scss">
    #app2 {
        font-family: 'Avenir', Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-align: center;
        color: #2c3e50;
    }

    h1, h2 {
        font-weight: normal;
    }

    ul {
        list-style-type: none;
        padding: 0;
    }

    li {
        display: inline-block;
        margin: 0 10px;
    }

    a {
        color: #42b983;
    }

    #chartdiv {
        width: 100%;
        height: 500px;
    }

    .amcharts-export-menu-top-right {
        top: 10px;
        right: 0;
    }
</style>
