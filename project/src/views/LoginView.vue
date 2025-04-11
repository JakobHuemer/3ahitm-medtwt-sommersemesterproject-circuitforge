<script setup lang="ts">
import { ref } from 'vue'
import { useApi } from '@/store/useApi.ts'

const api = useApi()

const login = ref<string>('')
const password = ref<string>('')
const rememberMe = ref<boolean>(false)

function doLogin() {
    console.log(login.value, password.value, rememberMe.value)
    api.login(login.value, password.value, rememberMe.value)
}
</script>

<template>
    <div class="auth-container login-container full-height">
        <h1 class="title">Login to CircuitForge</h1>
        <div class="form-section">
            <div class="input input-login">
                <label for="login">username or email</label>
                <input type="text" name="login" id="login" v-model="login" />
            </div>

            <div class="input input-password">
                <label for="password">password</label>
                <input type="password" name="password" id="password" v-model="password" />
            </div>

            <div class="remember-me">
                <input type="checkbox" name="remember-me" id="remember-me" v-model="rememberMe" />
                <label for="remember-me" class="checkbox"></label>
                <label for="remember-me">remember me</label>
            </div>
        </div>

        <button class="signup" @click="doLogin">Login</button>

        <div class="footnote">
            <span class="footnote-text">Don't have an Account? </span>
            <RouterLink to="/register" class="footnote-link">Sign Up</RouterLink>
        </div>
    </div>
</template>

<style scoped></style>
