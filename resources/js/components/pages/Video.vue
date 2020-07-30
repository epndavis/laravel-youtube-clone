<template>
    <div id="video" v-if="video.title" :class="{ 'theater-mode': inTheaterMode }">
        <div id="theater_player" class="video-theater-container"></div>

        <div class="video-wrapper">
            <div class="video-info-container">
                <div id="classic_player">
                    <player 
                        id="player"
                        :src="video.video.src"
                        :time="getTime()"
                        @theaterMode="theaterMode"
                    /> 
                </div>    

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
            <div v-show="status" class="related-videos">
                <list-item 
                    v-for="(video, index) in videos"
                    :key="index"
                    :video="video"
                />
            </div>
        </div>
    </div>
</template>

<script>
import { get } from '@services/video'
import Player from '../Player'
import ListItem from '../ListItem'
import list from '../../store/modules/list'

export default {
    components: {
        player: Player,
        'list-item': ListItem
    },

    data: () => {
        return {
            video: {},
            inTheaterMode: false,
            status: false
        }
    },

    created() {
        if (!this.$route.query.v) {
            return this.$router.push({ name: 'home' })
        }

        if (!this.$store.hasModule('recommendList')) {
            this.$store.registerModule('recommendList', list)
        }

        this.fetch()
    },

    methods: {
        fetch() {
            this.$store.dispatch('recommendList/getVideoList', {
                forceUpdate: true,
                ignore: this.$route.query.v
            }).then(response => {
                this.status = true
            })
            
            return get(this.$route.query.v)
                .then((response) => {
                    this.video = response.data.data
                })
        },

        theaterMode(mode) {
            let playerContainer = 'classic_player'

            if (mode) {
                playerContainer = 'theater_player'
                document.getElementById('app').classList.add('theater-mode')
            } else {
                document.getElementById('app').classList.remove('theater-mode')
            }
            
            this.inTheaterMode = mode
            document.getElementById(playerContainer).appendChild(document.getElementById('player'))
        },

        getTime() {
            return 
        }
    },

    computed: {
        videos() {
            return this.$store.getters['recommendList/videoList']
        }
    }
}
</script>
