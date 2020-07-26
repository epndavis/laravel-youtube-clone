<template>
    <div class="video-player" :class="{ 'player-paused': paused, 'player-dragging': dragging, 'player-hover': volumeDragging }">
        <div class="video-container" @click="togglePlay()">
            <video id="video_player" :src="src" width="100%" controlsList="nodownload"></video>  
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
                    <span v-show="!ended">
                        <svg class="control-icon-fill" height="100%" width="100%" viewBox="0 0 36 36">
                            <path :d="playSVG()" />
                        </svg>
                    </span>
                    <span v-show="ended">
                        <svg class="control-icon-fill" width="100%" height="100%" viewBox="-6 -6 36 36">
                            <path d="M12 5.016q3.328 0 5.672 2.344t2.344 5.625q0 3.328-2.367 5.672t-5.648 2.344-5.648-2.344-2.367-5.672h2.016q0 2.484 1.758 4.242t4.242 1.758 4.242-1.758 1.758-4.242-1.758-4.242-4.242-1.758v4.031l-5.016-5.016 5.016-5.016v4.031z"/>
                        </svg>
                    </span>
                </a>
                <span class="volume-controls" :class="{ 'volume-dragging': volumeDragging }">
                    <a class="controls-action" @click="toggleMute()">
                        <span>
                            <svg class="control-icon-fill" width="100%" height="100%" viewBox="-11.5 -11.5 56 56">
                                <path :d="volumeSVG()"/>
                            </svg>
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
                <a v-show="!fullscreen" class="controls-action" @click="toggleTheaterMode()">
                    <span>
                        <svg width="18" height="10" :class="{ 'expand': !theaterMode }">
                            <rect x="0" y="0" width="18" height="10" style="stroke:white;stroke-width:3;" /> 
                        </svg>
                    </span>
                </a>
                <a class="controls-action" @click="toggleFullscreen()">
                    <span>
                        <svg class="control-icon-fill" height="100%" width="100%" viewBox="0 0 36 36">
                            <path :d="fullscreenSVG()" />
                        </svg>
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
    import { playIcons, volumeIcons, fullscreenIcons } from '../player/icons/all.icon'
    import * as fullscreenCtrl from '../player/controls/fullscreen.control'

    export default {
        components: {
            'slider-bar-list': SliderBarList,
            'progress-bar': ProgressBar
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
                this.volume = this.mousePosition(context.element, context.event)
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
                
                fullscreenCtrl.onFullscreenChange(() => {
                    if (!document.fullscreenElement) {
                        this.fullscreen = false
                    } else {
                        window.scrollTo(0, 0)
                    }     
                })
            },

            mousePosition(el, e) {
                let rect = el.getBoundingClientRect();
                return (e.screenX - rect.left) / el.offsetWidth;
            },

            playSVG() {
                if (this.paused) {
                    return playIcons.play
                }

                return playIcons.pause
            },

            volumeSVG() {
                if (this.muted || this.volume <= 0) {
                    return volumeIcons.mute
                }

                if (this.volume <= 0.5) {
                    return volumeIcons.low
                }

                return volumeIcons.high
            },

            fullscreenSVG() {
                if (this.fullscreen) {
                    return fullscreenIcons.on
                }

                return fullscreenIcons.off
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
