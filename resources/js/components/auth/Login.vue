<template>
    <div id="login">
        <form method="POST" @submit.prevent="login">
            <div>
                <label for="email">Email</label>

                <div>
                    <input id="email" v-model="email" type="email" name="email" required autocomplete="email" autofocus>
                </div>
            </div>

            <div>
                <label for="password">Password</label>

                <div>
                    <input id="password" v-model="password" type="password" name="password" required autocomplete="current-password">
                </div>
            </div>

            <div>
                <div>
                    <div>
                        <input id="remember" v-model="remember" type="checkbox" name="remember">

                        <label for="remember">
                            Remember Me
                        </label>
                    </div>
                </div>
            </div>

            <div>
                <div>
                    <button type="submit">
                        Login
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import { login } from '@services/auth.service'

export default {
    data() {
        return {
            email: '',
            password: '',
            remember: false
        }
    },

    methods: {
        login() {
            axios.get('/sanctum/csrf-cookie').then(response => {
                login({
                    email: this.email,
                    password: this.password,
                    remember: this.remember
                }).then(loginResponse => {
                    localStorage.setItem('isLoggedIn', 'true')

                    let next = { name: 'home' }

                    if (this.$route.query.redirect) {
                        next = { path: this.$route.query.redirect }
                    }

                    this.$router.replace(next)
                })
            })
        }
    }
}
</script>
