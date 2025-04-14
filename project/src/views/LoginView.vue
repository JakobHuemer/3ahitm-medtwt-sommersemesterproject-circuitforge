<script setup lang="ts">
import { ref } from 'vue'
import { useApi } from '@/store/useApi.ts'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

import { faXmark } from '@fortawesome/free-solid-svg-icons'


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
        <h1 class="title">Login to CircuitForge</h1>
        <div class="form-section">

            <div class="auth-error" v-if="loginFailed">
                <FontAwesomeIcon :icon="faXmark" />
                <span>Wrong login or password!</span>
            </div>

            <div class="input input-login">
                <label for="login">username or email</label>
                <input
                    type="text"
                    name="login"
                    id="login"
                    v-model="login"
                    autocomplete="username email"
                />
            </div>

            <div class="input input-password">
                <label for="password">password</label>
                <input type="password" name="password" id="password" v-model="password"
                       autocomplete="current-password password" />
            </div>

            <div class="remember-me">
                <input type="checkbox" name="remember-me" id="remember-me" v-model="rememberMe" />
                <label for="remember-me" class="checkbox"></label>
                <label for="remember-me">remember me</label>
            </div>
        </div>

        <button type="submit" class="signup" @click="doLogin" data-form-type="login">Login</button>

        <div class="footnote">
            <span class="footnote-text">Don't have an Account? </span>
            <RouterLink to="/register" class="footnote-link">Sign Up</RouterLink>
        </div>
    </div>
</template>

<style scoped>

.auth-error {
    border: 1px solid var(--col-error);
    background: color-mix(in srgb, var(--col-error) 10%, transparent);
    border-radius: var(--border-radius-s);

    display: flex;
    align-items: center;
    gap: var(--gap-4);

    padding: var(--gap-8);

    svg {
        color: var(--col-error);
    }

}

</style>
