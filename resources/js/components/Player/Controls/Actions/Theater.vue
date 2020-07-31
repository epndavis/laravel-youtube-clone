<template>
    <a v-show="display" class="controls-action" @click="clickAction()">
        <span>
            <svg width="18" height="10" :class="{ 'expand': !theaterMode }">
                <rect x="0" y="0" width="18" height="10" style="stroke:white;stroke-width:3;" /> 
            </svg>
        </span>
    </a>
</template>

<script>
export default {
    props: {
        theaterMode: {
            type: Boolean,
            required: true
        },  

        display: {
            type: Boolean,
            default: true
        }
    },

    methods: {
        clickAction() {
            this.$emit('action', true)
        }
    }
}
</script>
