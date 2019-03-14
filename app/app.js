import Vue from 'vue'
import VueJquery from 'vue-jquery'
import VueRouter from 'vue-router'
import router from './routes.js'
import store from './store'
import VueSocketIO from 'vue-socket.io'
import Buefy from 'buefy'
import VueCookies from 'vue-cookies'
import AsyncComputed from 'vue-async-computed'

import VueIndex from './VueIndex'

import './bootstrap'
import 'buefy/dist/buefy.css'

Vue.use(VueRouter);
Vue.use(AsyncComputed)
Vue.use(VueCookies)
Vue.use(VueJquery)
Vue.use(Buefy)
Vue.use(new VueSocketIO({
    debug: true,
    connection: 'http://localhost:1228',
    vuex: {
        store,
        actionPrefix: 'socket_',
    }
}))

Vue.config.productionTip = false;

new Vue({
    components: {'vue-index': VueIndex},
    router,
    store,
    render: h => h(VueIndex)
}).$mount('#app');
