<template>
    <div class="video-player" :class="{ 'player-paused': paused, 'player-dragging': dragging }">
        <div class="video-container" @click="togglePlay()">
            <video id="video_player" :src="src" width="100%"></video>  
        </div> 

        <div class="player-gradient"></div>
        <div class="player-controls">
            <div class="progress-container" ref="progress-list">
                <div class="progress-bar-list">
                    <progress-bar class="buffer-bar" :progress="buffered"/>
                    <progress-bar class="seeker-bar" :progress="seeking"/>
                    <progress-bar class="progression-bar" :progress="progress"/>
                    <div class="progression-bar-indicator" :style="{ left: `${progress * 100}%` }"></div>
                </div>              
            </div>
            <div class="primary-controls">
                <a class="controls-action" @click="togglePlay()">
                    <span v-show="paused && !ended">
                        <i class="fas fa-play"></i>
                    </span>
                    <span v-show="!paused && !ended">
                        <i class="fas fa-pause"></i>
                    </span>
                    <span v-show="ended">
                        <i class="fas fa-redo-alt fa-flip-horizontal"></i>
                    </span>
                </a>
                <a class="controls-action" @click="toggleMute()">
                    <span v-show="!muted">
                        <i class="fas fa-volume-up"></i>
                    </span>
                    <span v-show="muted">
                        <i class="fas fa-volume-mute"></i>
                    </span>
                </a>
                <div class="player-time-display">
                    <span>{{ currentTime }} / {{ videoDuration() }}</span>
                </div>  
            </div>
            <div class="secondary-controls">
                <a class="controls-action" @click="video.fullscreen()">
                    <span>
                        <i class="fas fa-expand"></i>
                    </span>
                </a>
            </div>
        </div>    
    </div>
</template>

<script>
    import Video from '../video/video'
    import ProgressBar from './Player/ProgressBar'
    import { formatTime } from '../video/timer'

    export default {
        components: {
            'progress-bar': ProgressBar
        },

        props: {
            src: {
                type: String,
                required: true,
            }
        },

        data() {
            return {
                video: {},
                progressListElement: null,
                seeking: 0,
                dragging: false
            }
        },

        methods: {
            togglePlay() {
                if (this.video.paused) {
                    return this.video.play()
                }

                return this.video.pause()
            },

            videoDuration() {
                return formatTime(this.video.duration)
            },

            toggleMute() {
                this.muted = !this.muted
            },

            applyListeners() {
                this.progressListElement.addEventListener('mousemove', (e) => {
                    this.seeking = this.progressListMousePosition(e)
                })

                document.addEventListener('mousemove', (e) => {
                    if (this.dragging) {
                        e.preventDefault();
                        this.currentTime = this.progressListMousePosition(e) * this.video.duration
                    }
                })

                document.addEventListener('mouseup', (e) => {
                    this.dragging = false
                })

                this.progressListElement.addEventListener('mouseover', (e) => {
                    this.seeking = this.progressListMousePosition(e)
                })

                this.progressListElement.addEventListener('mouseout', (e) => {
                    this.seeking = 0
                })

                this.progressListElement.addEventListener('mousedown', (e) => {
                    this.dragging = true
                    this.currentTime =  this.progressListMousePosition(e) * this.video.duration
                })
            },

            progressListMousePosition(e) {
                let rect = this.progressListElement.getBoundingClientRect();

                return (e.screenX - rect.left) / this.progressListElement.offsetWidth;
            }
        },

        mounted() {
            this.video = new Video(document.getElementById('video_player'))

            this.video.play().catch(function(er) {
                console.log(er, "Cannot play video right now!")
            }) 

            this.progressListElement = this.$refs["progress-list"]

            this.applyListeners()
        },

        computed: {
            currentTime: {
                get() {
                    return formatTime(this.video.currentTime)
                },

                set(val) {
                    this.video.jumpTo(val)
                }
            },

            progress() {
                let progress = this.video.currentTime / this.video.duration

                if (progress > 1) {
                    progress = 1
                }

                return progress
            },

            buffered() {
                let progress = this.video.buffered / this.video.duration

                if (progress > 1) {
                    progress = 1
                }

                return progress
            },

            paused() {
                return this.video.paused
            },

            ended() {
                return this.video.ended
            },

            muted: {
                get() {
                    return this.video.muted
                },

                set(bool) {
                    this.video.$el.muted = bool
                }
            }
        },
    }
</script>
