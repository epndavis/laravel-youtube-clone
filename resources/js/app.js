require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'
import Vuex from 'vuex'

Vue.use(VueRouter)
Vue.use(Vuex)


//------ Routes ------//

import routes from './routes'

const router = new VueRouter({
    mode: 'history',
    routes: routes,
})

//------ Modules ------//


//------ Components ------//

Vue.component('app', require('./components/app').default)


window.app = new Vue({
    el: '#app',  
    router  
})
