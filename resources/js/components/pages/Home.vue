<template>
    <div id="home">
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

    methods: {
        fetch() {
            this.$store.dispatch('list/getVideoList')
        },
    },

    mounted() {
        this.fetch()
    },

    computed: {
        videos() {
            return this.$store.getters['list/videoList']
        }
    }
}
</script>
