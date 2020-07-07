<template>
    <div class="progress-bar" :style="{transform: `scaleX(${progress})`}"></div>
</template>

<script>
    export default {
        props: {
            progress: {
                type: Number,
                default: 0
            }
        }
    }
</script>
