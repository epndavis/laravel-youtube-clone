import { getList } from '@services/video'

export default {
    namespaced: true,

    state: () => ({
        lastUpdate: null,
        videos: []
    }),

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
        getVideoList ({ getters, commit }, payload = {}) {
            let currentDate = new Date()

            if (payload.forceUpdate || (getters.getLastUpdate === null || getters.getLastUpdate < (currentDate.getTime() - 30000))) {
                return getList(payload).then((response) => {
                    commit('setVideoList', response.data.data)
                    commit('updateLastTime')
                });
            }
        }
    }
}
