import router from './routes.js'

require('bootstrap')
require('./bootstrap')

window.Vue = require('vue')
window.$ = require('jquery')
window.JQuery = require('jquery')

window.app = new Vue({
    el: '#app',
    router,
    data: {

    },
    methods: {
        isActiveMenu(path) {
            return window.location.pathname == path;
        }
    }
});
