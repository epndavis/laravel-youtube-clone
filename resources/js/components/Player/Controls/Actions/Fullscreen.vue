<template>
    <a class="controls-action" @click="clickAction()">
        <span>
            <svg class="control-icon-fill" height="100%" width="100%" viewBox="0 0 36 36">
                <path :d="fullscreenSVG()" />
            </svg>
        </span>
    </a>
</template>

<script>
import fullscreenIcons from '@player/icons/fullscreen.icon'

export default {
    name: 'FullscreenAction',

    props: {  
        fullscreen: {
            type: Boolean,
            required: true
        }
    },

    methods: {
        clickAction() {
            this.$emit('action', true)
        },

        fullscreenSVG() {
            if (this.fullscreen) {
                return fullscreenIcons.on
            }

            return fullscreenIcons.off
        },
    }
}
</script>
