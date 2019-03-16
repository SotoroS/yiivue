import Vue from 'vue'
import VueJquery from 'vue-jquery'
import VueRouter from 'vue-router'
import router from './routes.js'
import store from './store'
import BootstrapVue from 'bootstrap-vue'
import VueCookies from 'vue-cookies'
import AsyncComputed from 'vue-async-computed'

import VueIndex from './VueIndex'

import './bootstrap'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.use(VueRouter);
Vue.use(AsyncComputed)
Vue.use(VueCookies)
Vue.use(VueJquery)
Vue.use(BootstrapVue)

Vue.config.productionTip = false;

new Vue({
    components: {'vue-index': VueIndex},
    router,
    store,
    render: h => h(VueIndex)
}).$mount('#app');
