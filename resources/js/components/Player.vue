<template>
    <div class="video-player" :class="{ 'player-paused': paused, 'player-dragging': dragging, 'player-hover': volumeDragging }">
        <div class="video-container" @click="togglePlay()">
            <video id="video_player" :src="src" width="100%"></video>  
        </div> 

        <div class="player-gradient"></div>
        <div class="player-controls">
            <slider-bar-list
                class="progress-container"
                ref="video-slider"
                @mousemove="setSeeking"
                @mouseover="setSeeking"
                @mouseout="endSeeking"
                @mousedown="draggingTime"
            >
                <progress-bar class="buffer-bar" :progress="buffered"/>
                <progress-bar class="seeker-bar" :progress="seeking"/>
                <progress-bar class="progression-bar" :progress="progress"/>
                <div class="progression-bar-indicator" :style="{ left: `${progress * 100}%` }"></div>
            </slider-bar-list>

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
                <span class="volume-controls" :class="{ 'volume-dragging': volumeDragging }">
                    <a class="controls-action" @click="toggleMute()">
                        <span v-show="!muted && volume > 0.4">
                            <i class="fas fa-volume-up"></i>
                        </span>
                        <span v-show="!muted && volume > 0 && volume <= 0.4">
                            <i class="fas fa-volume-down"></i>
                        </span>
                        <span v-show="muted || volume == 0">
                            <i class="fas fa-volume-mute"></i>
                        </span>
                    </a>

                    <slider-bar-list 
                        class="volume-scaler"
                        ref="volume-slider"
                        @mousedown="volumeChange"
                    >
                        <progress-bar class="progression-bar" :progress="volume"/>
                        <div class="progression-bar-indicator" :style="{ left: `${volume * 100}%` }"></div>
                    </slider-bar-list>
                </span>
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
    import SliderBarList from './Player/SliderBarList'
    import ProgressBar from './Player/ProgressBar'
    import { formatTime } from '../video/timer'

    export default {
        components: {
            'slider-bar-list': SliderBarList,
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
                volumeElement: null,
                seeking: 0,
                dragging: false,
                volumeDragging: false
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

            setSeeking(context) {
                this.seeking = this.mousePosition(context.element, context.event)
            },

            endSeeking() {
                this.seeking = 0
            },

            draggingTime(context) {
                this.dragging = true
                this.currentTime =  this.mousePosition(context.element, context.event) * this.video.duration
            },

            volumeChange(context) {
                this.volumeDragging = true
                let volume = this.mousePosition(context.element, context.event)

                if (volume > 1) {
                    volume = 1
                }

                if (volume < 0) {
                    volume = 0
                }

                this.volume = volume
            },

            applyListeners() {
                document.addEventListener('mousemove', (e) => {
                    if (this.dragging) {
                        e.preventDefault();
                        this.currentTime = this.mousePosition(this.$refs['video-slider'].$el, e) * this.video.duration
                    }

                    if (this.volumeDragging) {
                        e.preventDefault();

                        this.volumeChange({
                            'element': this.$refs['volume-slider'].$el,
                            'event': e
                        })
                    }
                })

                document.addEventListener('mouseup', (e) => {
                    this.dragging = false
                    this.volumeDragging = false
                })
            },

            mousePosition(el, e) {
                let rect = el.getBoundingClientRect();
                return (e.screenX - rect.left) / el.offsetWidth;
            }
        },

        mounted() {
            this.video = new Video(document.getElementById('video_player'))

            this.video.play().catch(function(er) {
                console.log(er, "Cannot play video right now!")
            }) 

            this.progressListElement = this.$refs["progress-list"]
            this.volumeElement = this.$refs["volume-slider"]

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
            },

            volume: {
                get() {
                    return this.video.volume
                },

                set(val) {
                    this.video.$el.volume = val
                }
            }
        },
    }
</script>
