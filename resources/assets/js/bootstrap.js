window._ = require('lodash');

window.axios = require('axios');

window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest'
};


/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

// try {
//     window.$ = window.jQuery = require('jquery');
// } catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import Vue from 'vue'
import Vuetify from 'vuetify'
import Vuex from 'vuex';
import VueBarcodeScanner from 'vue-barcode-scanner';

//Option barcode scanner
let options = {
    sound: false, // default is false
    soundSrc: '', // default is blank
    sensitivity: 300, // default is 100
    requiredAttr: false // default is false
}

/**
 * Import filter
 */

import "./filters/price-filter"

import router from './router'
Vue.use(Vuetify);
Vue.use(Vuex);
Vue.use(VueBarcodeScanner, options);



Vue.config.productionTip = true

/**
 * Event bus
 */

import TransactionEventBus from './event_bus/transaction_event';

/**
 * Plugins
 */
import AmCharts from 'amcharts3'
import AmSerial from 'amcharts3/amcharts/serial'

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */


import App from './components/App'
import store from './store';


/* eslint-disable no-new */
new Vue({
    el: '#budgetapp',
    store,
    router,
    components: {App},
    template: '<App/>',

    created(){

    },

    methods: {
    }
})




/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key',
//     cluster: 'mt1',
//     encrypted: true
// });
