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

import list from './store/modules/list'
import user from './store/modules/user.module'

const store = new Vuex.Store({
    modules: {
        list: list,
        user: user
    }
})

if (store.getters['user/isLoggedIn']) {
    store.dispatch('user/getUser')
}

//------ Components ------//

Vue.component('app', require('./components/App').default)
Vue.component('channel-icon', require('./components/user/icon').default)

window.app = new Vue({
    el: '#app',  
    store,
    router  
})
