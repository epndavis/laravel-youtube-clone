<template>
    <div id="video" v-if="video.title">
        <div class="video-container">
            <player 
                :src="video.video.src"
            />   

            <div class="video-info">
                <h2 class="video-title">
                    {{ video.title }}
                </h2>
            </div>

            <div class="video-description">
                <p>
                    {{ video.description }}
                </p>
            </div>           
        </div> 
        <div class="related-videos">
            <list-item 
                v-for="(video, index) in videos"
                :key="index"
                :video="video"
            />
        </div>
    </div>
</template>

<script>
import { get } from '@services/video'
import Player from '../Player'
import ListItem from '../ListItem'

export default {
    components: {
        player: Player,
        'list-item': ListItem
    },

    data: () => {
        return {
            video: {}
        }
    },

    methods: {
        fetch() {
            this.$store.dispatch('list/getVideoList')
            
            return get(this.$route.params.id)
                .then((response) => {
                    this.video = response.data.data
                })
        }
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
