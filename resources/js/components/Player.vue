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
                <a class="controls-action" @click="toggleTheaterMode()">
                    <span>
                        <svg width="18" height="10" :class="{ 'expand': !theaterMode }">
                            <rect x="0" y="0" width="18" height="10" style="stroke:white;stroke-width:3;" /> 
                        </svg>
                    </span>
                </a>
                <a class="controls-action" @click="video.fullscreen()">
                    <span>
                        <svg class="control-icon-fill" height="100%" width="100%" viewBox="0 0 36 36">
                            <path d="M10 10 L16 10 L16 12 L12 12 L12 16 L10 16z M26 10 L26 16 L24 16 L24 12 L20 12 L20 10z M10 26 L10 20 L12 20 L12 24 L16 24 L16 26z M26 26 L26 20 L24 20 L24 24 L20 24 L20 26z" />
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
                seeking: 0,
                dragging: false,
                volumeDragging: false,
                theaterModeBool: false
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
            },

            mousePosition(el, e) {
                let rect = el.getBoundingClientRect();
                return (e.screenX - rect.left) / el.offsetWidth;
            },

            playSVG() {
                if (this.paused) {
                    return 'M11 10 L25 18 L11 26z'
                }

                return 'M11 10 L16 10 L16 26 L11 26z M20 10 L25 10 L25 26 L20 26z'
            },

            volumeSVG() {
                if (this.muted || this.volume <= 0) {
                    return 'M30 19.348v2.652h-2.652l-3.348-3.348-3.348 3.348h-2.652v-2.652l3.348-3.348-3.348-3.348v-2.652h2.652l3.348 3.348 3.348-3.348h2.652v2.652l-3.348 3.348 3.348 3.348z M13 30c-0.26 0-0.516-0.102-0.707-0.293l-7.707-7.707h-3.586c-0.552 0-1-0.448-1-1v-10c0-0.552 0.448-1 1-1h3.586l7.707-7.707c0.286-0.286 0.716-0.372 1.090-0.217s0.617 0.519 0.617 0.924v26c0 0.404-0.244 0.769-0.617 0.924-0.124 0.051-0.254 0.076-0.383 0.076z'
                }

                if (this.volume <= 0.5) {
                    return 'M13 30c-0.26 0-0.516-0.102-0.707-0.293l-7.707-7.707h-3.586c-0.552 0-1-0.448-1-1v-10c0-0.552 0.448-1 1-1h3.586l7.707-7.707c0.286-0.286 0.716-0.372 1.090-0.217s0.617 0.519 0.617 0.924v26c0 0.404-0.244 0.769-0.617 0.924-0.124 0.051-0.254 0.076-0.383 0.076z M17.157 23.157c-0.384 0-0.768-0.146-1.061-0.439-0.586-0.586-0.586-1.535 0-2.121 2.534-2.534 2.534-6.658 0-9.192-0.586-0.586-0.586-1.536 0-2.121s1.535-0.586 2.121 0c3.704 3.704 3.704 9.731 0 13.435-0.293 0.293-0.677 0.439-1.061 0.439z'
                }

                return 'M13 30c-0.26 0-0.516-0.102-0.707-0.293l-7.707-7.707h-3.586c-0.552 0-1-0.448-1-1v-10c0-0.552 0.448-1 1-1h3.586l7.707-7.707c0.286-0.286 0.716-0.372 1.090-0.217s0.617 0.519 0.617 0.924v26c0 0.404-0.244 0.769-0.617 0.924-0.124 0.051-0.254 0.076-0.383 0.076z M22.485 25.985c-0.384 0-0.768-0.146-1.061-0.439-0.586-0.586-0.586-1.535 0-2.121 4.094-4.094 4.094-10.755 0-14.849-0.586-0.586-0.586-1.536 0-2.121s1.536-0.586 2.121 0c2.55 2.55 3.954 5.94 3.954 9.546s-1.404 6.996-3.954 9.546c-0.293 0.293-0.677 0.439-1.061 0.439v0zM17.157 23.157c-0.384 0-0.768-0.146-1.061-0.439-0.586-0.586-0.586-1.535 0-2.121 2.534-2.534 2.534-6.658 0-9.192-0.586-0.586-0.586-1.536 0-2.121s1.535-0.586 2.121 0c3.704 3.704 3.704 9.731 0 13.435-0.293 0.293-0.677 0.439-1.061 0.439z'
            }
        },

        mounted() {
            this.video = new Video(document.getElementById('video_player'))

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
