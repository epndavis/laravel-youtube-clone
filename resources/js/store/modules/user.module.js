import { logout, login } from '@services/auth.service'

export default {
    namespaced: true,

    state: {
        loggedIn: localStorage.getItem('isLoggedIn') === 'true',
        user: {}
    },

    getters: {
        isLoggedIn: (state) => {
            return state.loggedIn
        },

        getUser: (state) => {
            return state.user
        }
    },

    mutations: {
        setUser (state, user) {
            state.user = user
        },

        setLoggedIn (state, logged) {
            if(logged) {
                localStorage.setItem('isLoggedIn', 'true')
            }  else {
                localStorage.removeItem('isLoggedIn')
            }

            state.loggedIn = logged
        }
    },  

    actions: {
        getUser ({ commit }) {
            return axios.get('api/user').then(response => {
                commit('setUser', response.data)
            }).catch(error => {
                if (error.response.state == '401') {
                    commit('user/setLoggedIn', false)
                }
            })
        },

        login ({ commit, dispatch }, params) {
            return login(params).then(response => {
                commit('setLoggedIn', true)
                dispatch('getUser')
            })
        },

        logout ({ commit }) {
            return logout().then(response => {
                commit('setUser', {})
                commit('setLoggedIn', false)
            });
        }
    }
}
