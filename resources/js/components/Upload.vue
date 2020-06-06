<template>
    <div>
        <div v-if="errors.title">
            {{ errors.title[0] }}
        </div>
        <label>
            Title <input type="text" v-model="title">
        </label>
        <div v-if="errors.description">
            {{ errors.description[0] }}
        </div>
        <label>
            Description <input type="text" v-model="description">
        </label>
        <div v-if="errors.video">
            {{ errors.video[0] }}
        </div>
        <label> 
            Video <input type="file" @change="handle"/>
        </label>

        <button @click="submit()">Upload</button>
    </div>
</template>

<script>
export default {
    data: () => {
        return {
            title: '',
            description: '',
            video: null,
            errors: []
        }
    },

    methods: {
        handle(e) {
            this.video = e.target.files[0]
        },

        submit() {
            let formData = new FormData()

            formData.append('title', this.title)
            formData.append('description', this.description)
            formData.append('video', this.video)

            axios.post('/api/videos', formData)
                .then(function(response) {
                    this.errors = []
                }).catch((error) => {
                    this.errors = error.response.data.errors
                })
        }
    }
}
</script>
