<template>
    <div id="video" v-if="video.title">
        <h2>{{ video.title }}</h2>

        <video width="100%" controls>
            <source :src="video.video.src" type="video/mp4"/>
        </video>

        <p>
            {{ video.description }}
        </p>
    </div>
</template>

<script>
import { get } from '@services/video'

export default {
    data: () => {
        return {
            video: {}
        }
    },

    methods: {
        fetch() {
            return get(this.$route.params.id)
                .then((response) => {
                    this.video = response.data.data
                })
        }
    },

    mounted() {
        this.fetch()
    }
}
</script>
