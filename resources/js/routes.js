import Home from './components/Home'
import Upload from './components/Upload'

let routes = [
    {
        path: '/',
        name: 'home',
        component: Home
    },
    {
        path: '/upload',
        name: 'upload',
        component: Upload
    }
]

export default routes
