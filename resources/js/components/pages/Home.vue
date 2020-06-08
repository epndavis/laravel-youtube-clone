<template>
    <div>
        <list-item 
            v-for="(video, index) in videos"
            :key="index"
            :video="video"
        />
    </div>
</template>

<script>
import ListItem from '../ListItem'

export default {
    components: {
        'list-item': ListItem
    },

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
