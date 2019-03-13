import router from './routes.js'
import VueSocketIO from 'vue-socket.io'

require('bootstrap')
require('./bootstrap')

var Vue = require('vue')
var $ = require('jquery')

Vue.use(new VueSocketIO({
    debug: true,
    connection: 'http://localhost:1228',
}))

var app = new Vue({
    el: '#app',
    router,
    data: {

    },
    methods: {
        isActiveMenu(path) {
            return window.location.pathname == path
        }
    }
});
