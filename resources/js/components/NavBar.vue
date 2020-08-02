<template>
    <nav class="nav-bar">
        <div class="left-nav">
            <a @click="goHome()">
                <logo />
                <h2> 
                    Yt Clone
                </h2>
            </a>
        </div>

        <div class="center-nav">
            <form class="search-container" @submit.prevent="search">
                <div class="search-bar">
                    <input v-model="query" name="q" type="text" placeholder="Search" />
                </div>
                <button class="search-button">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="24" height="24" viewBox="0 0 1024 1024">
                        <path d="M621.668 653.668c-44.476 31.692-98.895 50.332-157.668 50.332-150.221 0-272-121.779-272-272s121.779-272 272-272c150.221 0 272 121.779 272 272 0 58.773-18.641 113.192-50.332 157.668l178.714 178.714c17.606 17.606 17.46 45.778-0.006 63.244l-0.75 0.75c-17.421 17.421-45.781 17.469-63.244 0.006l-178.714-178.714zM464 640c114.875 0 208-93.125 208-208s-93.125-208-208-208c-114.875 0-208 93.125-208 208s93.125 208 208 208v0z"/>
                    </svg>
                </button>
            </form>
        </div>

        <div class="right-nav">
            <div class="upload">
                <router-link :to="{ name: 'upload' }">
                    <svg height="32" width="20" viewBox="0 0 280 180">
                        <path fill="#555" d="M0 0 L220 0 L220 180 L0 180 Z M200 90 L280 0 L280 180 Z" />
                        <path fill="white" d="M95 20 L95 160 L125 160 L125 20 Z M40 75 L40 105 L180 105 L180 75 Z" />
                    </svg>
                </router-link>
            </div>
            <div class="account">
                <div v-if="isLoggedIn" @click="logout">
                    <channel-icon :name="username" />
                </div>
                <router-link v-else :to="{ name: 'login' }">
                    Sign In
                </router-link>
            </div>
        </div>      
    </nav>
</template>

<script>
import Logo from './Logo'

export default {
    name: 'Nav-Bar',

    components: {
        Logo
    },

    data() {
        return {
            query: this.$route.query.q
        }
    },

    methods: {
        goHome() {
            this.$store.commit('list/clearLastUpdate')

            this.$router.push({ name: 'home' })
        },

        search() {
            if (this.query) {
                this.$router.push({ 
                    name: 'home',
                    query: {
                        q: this.query
                    }
                })
            }
        },

        logout() {
            this.$store.dispatch('user/logout').then(response => {
                this.goHome()
            })
        }
    },

    computed: {
        isLoggedIn() {
            return this.$store.getters['user/isLoggedIn']
        },

        username() {
            return this.$store.getters['user/getUser'].name
        }
    }
}
</script>
