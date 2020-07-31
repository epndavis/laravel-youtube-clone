<template>
    <a class="controls-action" @click="clickAction()">
        <span v-show="!ended">
            <svg class="control-icon-fill" height="100%" width="100%" viewBox="0 0 36 36">
                <path :d="playSVG()" />
            </svg>
        </span>
        <span v-show="ended">
            <svg class="control-icon-fill" width="100%" height="100%" viewBox="-6 -6 36 36">
                <path :d="replaySVG()"/>
            </svg>
        </span>
    </a>
</template>

<script>
import playIcons from '@player/icons/play.icon';

export default {
    name: 'PlayPauseAction',

    props: {
        paused: {
            type: Boolean,
            required: true
        },

        ended: {
            type: Boolean,
            required: true
        }
    },

    methods: {
        clickAction() {
            this.$emit('action', true)
        },

        playSVG() {
            if (this.paused) {
                return playIcons.play
            }

            return playIcons.pause
        },

        replaySVG() {
            return playIcons.replay
        }
    }
}
</script>
