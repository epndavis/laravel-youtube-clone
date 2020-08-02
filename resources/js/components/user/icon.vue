<template>
    <div class="channel-icon">
        {{ username }}
    </div>
</template>

<script>
export default {
    props: {
        name: {
            type: String,
            default: 'Elliot'
        }
    },

    data() {
        return {
            username: this.name[0]
        }
    }
}
</script>
