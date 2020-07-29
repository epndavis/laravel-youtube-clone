<template>
    <div class="item-thumbnail-preview" :class="{ 'display-preview': displayPreview }">
        <img ref="image" :src="src"/>
    </div>
</template>

<script>
export default {
    props: {
        src: {
            type: String,
            required: true
        }
    },

    data() {
        return {
            displayPreview: false,
            previewTimer: null
        }
    },

    methods: {
        showPreview() {
            this.displayPreview = true
            this.previewTimer = setTimeout(() => {
                this.displayPreview = false
            }, 2500)
        }
    },

    mounted() {
        this.$refs.image.addEventListener('load', this.showPreview())
    },

    beforeDestroy() {
        this.$refs.image.removeEventListener('load', this.showPreview())
        clearTimeout(this.previewTimer)
    }
}
</script>
