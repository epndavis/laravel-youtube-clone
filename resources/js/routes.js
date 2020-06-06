import Home from './components/Home'
import Upload from './components/Upload'
import Video from './components/Video'

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
