<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-card>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs6>
                            <v-select
                                    dark
                                    color="white"
                                    v-model="select"
                                    :hint="`${select.state}, ${select.abbr}`"
                                    :items="items"
                                    item-text="state"
                                    item-value="abbr"
                                    label="Choose a field"
                                    persistent-hint
                                    @input="onSelected()"
                                    return-object
                                    single-line
                            ></v-select>
                        </v-flex>

                        <v-flex xs6 v-if="customDate">
                            <v-layout row wrap>
                                <v-flex :class="{'xs6': customDate && customRangeDate, 'xs12': customDate && !customRangeDate}">
                                    <v-menu
                                            ref="startDate"
                                            :close-on-content-click="false"
                                            v-model="startDateDialog"
                                            :nudge-right="40"
                                            lazy
                                            transition="scale-transition"
                                            offset-y
                                            full-width
                                            max-width="290px"
                                            min-width="290px"
                                    >
                                        <v-text-field
                                                dark
                                                color="white"
                                                slot="activator"
                                                label="Start Date"
                                                v-model="startDate"
                                                persistent-hint
                                                prepend-icon="event"
                                                @blur="date = parseDate(dateFormatted)"
                                        ></v-text-field>
                                        <v-date-picker v-model="startDate" no-title @input="startDateDialog = false"></v-date-picker>
                                    </v-menu>
                                </v-flex>

                                <v-flex xs6 v-if="customRangeDate">
                                    <v-menu
                                            :close-on-content-click="false"
                                            v-model="endDateDialog"
                                            :nudge-right="40"
                                            lazy
                                            transition="scale-transition"
                                            offset-y
                                            full-width
                                            max-width="290px"
                                            min-width="290px"
                                    >
                                        <v-text-field
                                                dark
                                                color="white"
                                                slot="activator"
                                                v-model="endDate"
                                                label="End date"
                                                persistent-hint
                                                prepend-icon="event"
                                                readonly
                                        ></v-text-field>
                                        <v-date-picker v-model="endDate" no-title @input="endDateDialog = false"></v-date-picker>
                                    </v-menu>
                                </v-flex>
                            </v-layout>

                        </v-flex>
                    </v-layout>
                </v-card-text>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    import {mapActions} from 'vuex';
    export default {
        data(){
            return {
                select: { state: 'Today transaction', abbr: 'TDT' },
                customDate : false,
                customRangeDate: false,
                date: null,
                dateFormatted: null,
                startDateDialog: false,
                endDateDialog: false,
                startDate:'',
                endDate:'',


                items: [
                    { state: 'Today', abbr: 'TDT' },
                    { state: 'Yesterday', abbr: 'YDT' },
                    { state: 'This Week', abbr: 'TWT' },
                    { state: 'Last Week', abbr: 'LWT' },
                    { state: 'This Month', abbr: 'TMT' },
                    { state: 'Last Month', abbr: 'LMT' },
                    { state: 'This Year', abbr: 'TYT' },
                    { state: 'Last Year', abbr: 'LYT' },
                    { state: 'Custom Date', abbr: 'CDT' },
                    { state: 'Custom Rrange Date', abbr: 'CRT' },
                ]
            }
        },

        created(){
            let data = this.generateData();
            this.getTransaction(data);
        },

        computed: {
            computedDateFormatted () {
                return this.formatDate(this.date)
            }
        },

        watch: {
            date (val) {
                this.dateFormatted = this.formatDate(this.date)
            },

            startDate(){
                let data = this.generateData();
                this.getTransaction(data);
            },

            endDate(){
                let data = this.generateData();
                this.getTransaction(data);
            },


        },

        methods: {
            ...mapActions({
                getTransaction: 'fetchAllTransaction'
            }),
            onSelected(){
                let data = this.generateData();
                this.$store.dispatch('fetchAllTransaction', data);
            },

            generateData(){
                this.customDate = false;
                this.customRangeDate = false;
                let data = {};
                if(this.select.abbr === 'CDT'){
                    this.customDate = true;
                    data.customdate = true;
                }
                if(this.select.abbr === 'CRT'){
                    this.customDate = true;
                    this.customRangeDate = true;
                    data.customRangeDate = true;
                }


                // if range detection run this code
                if(this.customDate && this.customRangeDate){
                    data.range = true;
                }

                data.startdate = this.startDate;
                data.enddate = this.endDate;
                data.select = this.select;
                return data;
            },

            formatDate (date) {
                if (!date) return null

                const [year, month, day] = date.split('-')
                return `${month}/${day}/${year}`
            },
            parseDate (date) {
                if (!date) return null

                const [month, day, year] = date.split('/')
                return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
            }
        }
    }
</script>
