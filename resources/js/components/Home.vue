<template>
    <div>
        <h2>
            Youtube Clone
        </h2>

        <div>
            <div 
                v-for="(video, index) in videos"
                :key="index"
            >
                <router-link :to="{ name: 'video', params: { id: video.id } }">
                    <h3>{{ video.title }}</h3>

                    <img :src="video.video.thumb"/>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: () => {
        return {
            videos: []
        }
    },

    methods: {
        fetch() {
            return axios.get('/api/videos')
                .then((response) => {
                    this.videos = response.data.data
                })
        },
    },

    mounted() {
        this.fetch()
    }
}
</script>
