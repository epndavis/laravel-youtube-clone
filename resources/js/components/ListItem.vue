<template>
    <div class="list-item" @mouseover="onMouseOver()" @mouseleave="onMouseLeave()">
        <div class="item-thumbnail-wrapper">
            <router-link :to="videoLink()">
                <div class="item-thumbnail">
                    <img :src="video.video.thumb"/>

                    <thumbnail-preview v-if="mouseover" :src="video.video.gif" />

                    <div class="video-duration">
                        <span>{{ durationDisplay() }}</span>
                    </div>
                </div>  
            </router-link> 
        </div>

        <div class="item-details-wrapper">
            <div class="channel-image">
                <!-- Logo image here -->
            </div>
            <div class="item-details">
                <router-link :to="videoLink()">
                    <h2>{{ video.title }}</h2>
                </router-link> 

                <div>
                    Channel Name
                </div>
                <div>
                    {{ viewCount() }}
                </div>
            </div>
        </div>   
    </div>
</template>

<script>
import ThumbNailPreview from './ThumbnailPreview'
import { formatTime } from '../video/timer'

export default {
    components: {
        'thumbnail-preview': ThumbNailPreview
    },

    props: {
        video: {
            type: Object,
            required: true
        }
    },

    data() {
        return {
            mouseover: false,
        }
    },

    methods: {
        videoLink() {
            return { 
                name: 'watch', 
                query: { 
                    v: this.video.id 
                } 
            }
        },

        viewCount() {
            return '117K views'
        },

        durationDisplay() {
           return formatTime(this.video.video.duration)
        },

        onMouseOver() {
            this.mouseover = true
        },

        onMouseLeave() {
            this.mouseover = false
        }
    }
}
</script>
