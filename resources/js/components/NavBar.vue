<template>
    <nav class="nav-bar">
        <router-link :to="{ name: 'home' }">
        <h2>
            Youtube Clone
        </h2>
        </router-link>

        <router-link :to="{ name: 'upload' }">Upload</router-link>
    </nav>
</template>

<script>
export default {
    name: 'Nav-Bar'
}
</script>
