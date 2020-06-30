import { getList } from '@services/video'

export default {
    namespaced: true,

    state: {
        lastUpdate: null,
        videos: []
    },

    getters: {
        getLastUpdate: (state) => {
            return state.lastUpdate
        },

        videoList: (state) => {
            return state.videos
        }
    },

    mutations: {
        updateLastTime (state) {
            let currentDate = new Date()

            state.lastUpdate = currentDate.getTime()
        },

        setVideoList (state, videos) {
            state.videos = videos
        }
    },

    actions: {
        getVideoList ({ getters, commit }) {
            let currentDate = new Date()

            if (getters.getLastUpdate === null || getters.getLastUpdate < (currentDate.getTime() - 30000)) {
                return getList().then((response) => {
                    commit('setVideoList', response.data.data)
                    commit('updateLastTime')
                });
            }
        }
    }
}
