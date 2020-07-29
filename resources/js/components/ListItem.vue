<template>
    <div class="list-item">
        <div class="item-thumbnail-wrapper">
            <router-link :to="videoLink()">
                <div class="item-thumbnail">
                    <img :src="video.video.thumb"/>

                    <img :src="video.video.gif"/>

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
import { formatTime } from '../video/timer'

export default {
    props: {
        video: {
            type: Object,
            required: true
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
        }
    }
}
</script>
