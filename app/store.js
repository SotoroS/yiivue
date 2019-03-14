import Vue from 'vue'
import Vuex from 'vuex'
import Test from './stores/test'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        test: Test,
    }
})