<template>
    <div class="video-player" :class="{ 'player-paused': paused, 'player-dragging': dragging, 'player-hover': volumeDragging }">
        <div class="video-container" @click="togglePlay()">
            <video id="video_player" :src="src" width="100%" controlsList="nodownload"></video>  
        </div> 

        <div class="player-gradient"></div>
        <div v-if="video.$el" class="player-controls">
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
                <play-pause-action :paused="paused" :ended="ended" @action="togglePlay()" />

                <volume-action :volume="volume" :muted="muted" @action="toggleMute()" @setVolume="volumeChange"/>

                <div class="player-time-display">
                    <span>{{ currentTime }} / {{ videoDuration() }}</span>
                </div>  
            </div>
            <div class="secondary-controls">   
                <theater-action :theater-mode="theaterMode" :display="!fullscreen" @action="toggleTheaterMode()" />  

                <fullscreen-action :fullscreen="fullscreen" @action="toggleFullscreen()" />
            </div>
        </div>  
    </div>
</template>

<script>
    import Video from '../video/video'
    import SliderBarList from './Player/SliderBarList'
    import ProgressBar from './Player/ProgressBar'
    import { formatTime } from '../video/timer'
    import * as fullscreenCtrl from '@player/controls/fullscreen.control'
    import PlayPauseAction from './Player/Controls/Actions/PlayPause'
    import VolumeAction from './Player/Controls/Actions/Volume'
    import TheaterAction from './Player/Controls/Actions/Theater'
    import FullscreenAction from './Player/Controls/Actions/Fullscreen'
    import { mousePosition } from '../mouse'

    export default {
        components: {
            'slider-bar-list': SliderBarList,
            'progress-bar': ProgressBar,
            'play-pause-action': PlayPauseAction,
            'volume-action': VolumeAction,
            'fullscreen-action': FullscreenAction,
            'theater-action': TheaterAction
        },

        props: {
            src: {
                type: String,
                required: true,
            },

            time: {
                type: Number,
                default: 0,
            }
        },

        data() {
            return {
                video: {},
                seeking: 0,
                dragging: false,
                volumeDragging: false,
                theaterModeBool: false,
                fullscreen: false,
            }
        },

        methods: {
            togglePlay() {
                if (this.video.paused) {
                    return this.video.play()
                }

                return this.video.pause()
            },

            toggleTheaterMode() {
                this.theaterMode = !this.theaterMode
            },

            enterTheaterMode() {
                this.theaterMode = true
            },

            exitTheaterMode() {
                this.theaterMode = false
            },

            videoDuration() {
                return formatTime(this.video.duration)
            },

            toggleMute() {
                this.muted = !this.muted
            },

            setSeeking(context) {
                this.seeking = mousePosition(context.element, context.event)
            },

            endSeeking() {
                this.seeking = 0
            },

            draggingTime(context) {
                this.dragging = true
                this.currentTime =  mousePosition(context.element, context.event) * this.video.duration
            },

            volumeChange(volume) {
                this.volume = volume
            },

            applyListeners() {
                document.addEventListener('mousemove', (e) => {
                    if (this.dragging) {
                        e.preventDefault();
                        this.currentTime = mousePosition(this.$refs['video-slider'].$el, e) * this.video.duration
                    }
                })

                document.addEventListener('mouseup', (e) => {
                    this.dragging = false
                })   
                
                fullscreenCtrl.onFullscreenChange(() => {
                    if (!document.fullscreenElement) {
                        this.fullscreen = false
                    } else {
                        window.scrollTo(0, 0)
                    }     
                })
            },

            toggleFullscreen() {
                this.fullscreen = !this.fullscreen
            }
        },

        watch: {
            fullscreen(setfull) {
                if (setfull) {
                    this.enterTheaterMode();

                    fullscreenCtrl.enterFullscreen(document.documentElement);

                    document.body.classList.add('fullscreen-mode')
                } else {
                    document.body.classList.remove('fullscreen-mode')

                    fullscreenCtrl.exitFullscreen();
                }
            }
        },

        mounted() {
            this.video = new Video(document.getElementById('video_player'))

            this.video.jumpTo(this.time)

            this.video.play().catch(function(er) {
                console.log(er, "Cannot play video right now!")
            }) 

            let oldVolume = localStorage.getItem('volume');
            if (oldVolume !== 'null') {
                this.volume = oldVolume
            }

            let oldTheater = localStorage.getItem('theaterMode');
            if (oldTheater !== 'null') {
                this.theaterMode = Boolean(oldTheater == 'true')
            }

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
                    if (val > 1) {
                        val = 1
                    }

                    if (val < 0) {
                        val = 0
                    }

                    localStorage.setItem('volume', val);
                    this.video.$el.volume = val
                }
            },

            theaterMode: {
                get() {
                    return this.theaterModeBool
                },

                set(val) {
                    localStorage.setItem('theaterMode', val);
                    this.$emit('theaterMode', val)
                    this.theaterModeBool = val
                }
            }
        },
    }
</script>
