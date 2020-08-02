import Home from './components/pages/Home'
import Video from './components/pages/Video'

const guest = (to, from, next) => {
    if (!localStorage.getItem('isLoggedIn')) {
      return next()
    } else {
      return next({ name: 'home' })
    }
}

const auth = (to, from, next) => {
    if (localStorage.getItem('isLoggedIn')) {
        return next()
    } else {
        return next({ 
            name: 'login', 
            query: { 
                redirect: to.path 
            }
        })
    }
}

let routes = [
    {
        path: '/',
        name: 'home',
        component: Home
    },  
    {
        path: '/watch',
        name: 'watch',
        component: Video
    },
    {
        path: '/upload',
        name: 'upload',
        beforeEnter: auth,
        component: () => import(/* webpackChunkName: "js/auth/upload" */  './components/pages/Upload')
    },
    {
        path: '/login',
        name: 'login',
        beforeEnter: guest,
        component: () => import(/* webpackChunkName: "js/auth/login" */ './components/auth/Login')
    }
]

export default routes
