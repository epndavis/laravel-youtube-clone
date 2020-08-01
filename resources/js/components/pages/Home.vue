<template>
    <div id="home">
        <div v-show="status">
            <list-item 
                v-for="(video, index) in videos"
                :key="index"
                :video="video"
            />
        </div>
    </div>
</template>

<script>
import ListItem from '../ListItem'

export default {
    components: {
        'list-item': ListItem
    },

    data() {
        return {
            status: false
        }
    },

    methods: {
        fetch() {
            this.$store.dispatch('list/getVideoList', {
                query: this.$route.query.q
            }).then(response => {
                this.status = true
            })
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
