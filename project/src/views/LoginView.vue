<script setup lang="ts">
import { ref } from 'vue'
import { useApi } from '@/store/useApi.ts'

import InputField from '@/components/InputField.vue'
import ButtonComponent from '@/components/ButtonComponent.vue'
import Notice from '@/components/Notice.vue'
import LoginProviders from '@/components/Auth/LoginProviders.vue'

const api = useApi()

const login = ref<string>('')
const password = ref<string>('')
const rememberMe = ref<boolean>(false)

const loginFailed = ref<boolean>(false)

async function doLogin() {
    loginFailed.value = false
    console.log(login.value, password.value, rememberMe.value)
    const loginResult = await api.login(login.value, password.value, rememberMe.value)
    loginFailed.value = !loginResult
}
</script>

<template>
    <div class="auth-container login-container full-height" role="form">
        <h1 class="title">Login to <RouterLink to="/">CircuitForge</RouterLink></h1>

        <LoginProviders class="login-providers-container" />

        <div class="login-divider">or</div>

        <div class="form-section">
            <Notice v-if="loginFailed" type="error">Wrong login or password!</Notice>

            <InputField
                class="input input-login"
                v-model="login"
                label="login"
                autocomplete="username email"
            />

            <InputField
                class="input input-login"
                v-model="password"
                autocomplete="current-password password"
                label="password"
                type="password"
            />

            <div class="remember-me">
                <input type="checkbox" name="remember-me" id="remember-me" v-model="rememberMe" />
                <label for="remember-me" class="checkbox"></label>
                <label for="remember-me">remember me</label>
            </div>
        </div>

        <ButtonComponent
            :big="true"
            button-type="primary"
            size="medium"
            type="submit"
            class="signup"
            @click="doLogin"
            data-form-type="login"
            >Login</ButtonComponent
        >

        <div class="footnote">
            <span class="footnote-text">Don't have an Account? </span>
            <RouterLink to="/register" class="footnote-link">Sign Up</RouterLink>
        </div>
    </div>
</template>

<style scoped>
@import '../assets/auth-views.css';
</style>
