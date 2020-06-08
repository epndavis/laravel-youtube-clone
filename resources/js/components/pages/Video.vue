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
export default {
    data: () => {
        return {
            video: {}
        }
    },

    methods: {
        fetch() {
            return axios.get(`/api/videos/${this.$route.params.id}`)
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
