require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)


//------ Routes ------//

import routes from './routes'

const router = new VueRouter({
    mode: 'history',
    routes: routes,
})


//------ Components ------//

Vue.component('app', require('./components/app').default)


window.app = new Vue({
    el: '#app',  
    router  
})
