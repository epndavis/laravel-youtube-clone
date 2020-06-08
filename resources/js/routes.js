import Home from './components/pages/Home'
import Upload from './components/pages/Upload'
import Video from './components/pages/Video'

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
    },
    {
        path: '/video/:id',
        name: 'video',
        component: Video
    }
]

export default routes
