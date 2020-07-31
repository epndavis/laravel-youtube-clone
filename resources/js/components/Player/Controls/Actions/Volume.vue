<template>
    <span class="volume-controls" :class="{ 'volume-dragging': volumeDragging }">
        <a class="controls-action" @click="clickAction()">
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
</template>

<script>
import volumeIcons from '@player/icons/volume.icon';
import SliderBarList from '../../../Player/SliderBarList'
import ProgressBar from '../../../Player/ProgressBar'

export default {
    name: 'VolumeAction',

    components: {
        'slider-bar-list': SliderBarList,
        'progress-bar': ProgressBar,
    },

    props: {
        volume: {
            type: Number,
            required: true,
        },

        muted: {
            type: Boolean,
            required: true
        }
    },

    data() {
        return {
            volumeDragging: false
        }
    },

    created() {
        this.applyListeners()
    },

    methods: {
        clickAction() {
            this.$emit('action', true)
        },

        adjustVolume(val) {
            this.$emit('setVolume', val)
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

        volumeChange(context) {
            this.volumeDragging = true
            this.adjustVolume(this.mousePosition(context.element, context.event))
        },

        mousePosition(el, e) {
            let rect = el.getBoundingClientRect();
            return (e.screenX - rect.left) / el.offsetWidth;
        },

        applyListeners() {
            document.addEventListener('mousemove', (e) => {
                if (this.volumeDragging) {
                    e.preventDefault();

                    this.volumeChange({
                        'element': this.$refs['volume-slider'].$el,
                        'event': e
                    })
                }
            })

            document.addEventListener('mouseup', (e) => {
                this.volumeDragging = false
            })   
        }
    }
}
</script>
