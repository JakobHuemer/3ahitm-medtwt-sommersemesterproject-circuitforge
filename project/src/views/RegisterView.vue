<script setup lang="ts">
import { computed, ref } from 'vue'
import { useApi } from '@/store/useApi.ts'

const api = useApi()

const username = ref<string>('')
const email = ref<string>('')
const password = ref<string>('')
const confirmPassword = ref<string>('')
const rememberMe = ref<boolean>(false)

const doPasswordsMatch = computed(() => {
    return password.value == confirmPassword.value
})

function doRegister() {
    console.log('Signup Triggered')
}
</script>

<template>
    <div class="auth-container register-container full-height" role="form">
        <h1 class="title">Register on CircuitForge</h1>
        <div class="form-section">
            <div class="input input-username">
                <label for="username">
                    <span>username</span>
                    <span class="auth-error username-error">username already exists</span>
                </label>
                <input
                    type="text"
                    name="username"
                    id="username"
                    v-model="username"
                    autocomplete="username"
                />
            </div>

            <div class="input input-email">
                <label for="email">
                    <span>email</span>
                    <span class="auth-error email-error">email is already used</span>
                </label>
                <input type="email" name="email" id="email" v-model="email" autocomplete="email" />
            </div>

            <div class="input input-password">
                <label for="password">
                    <span>password</span>
                    <span
                        class="auth-error password-error auth-error-hidden"
                        v-if="!doPasswordsMatch"
                    ></span>
                </label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    v-model="password"
                    autocomplete="new-password"
                />
            </div>

            <div class="input input-confirm-password">
                <label for="confirm-password">
                    <span>confirm password</span>
                    <span class="auth-error confirm-password-error" v-if="!doPasswordsMatch"
                        >passwords do not match</span
                    >
                </label>
                <input
                    type="password"
                    name="confirm-password"
                    id="confirm-password"
                    v-model="confirmPassword"
                    autocomplete="new-password"
                />
            </div>

<!--            <div class="auth-warning passwords-match-warning" v-if="!doPasswordsMatch">-->
<!--                <span>passwords do not match!</span>-->
<!--            </div>-->

            <div class="remember-me">
                <input type="checkbox" name="remember-me" id="remember-me" v-model="rememberMe" />
                <label for="remember-me" class="checkbox"></label>
                <label for="remember-me">remember me</label>
            </div>
        </div>

        <button
            class="signup"
            data-form-type="register"
            :disabled="!doPasswordsMatch"
            @click="doRegister"
            type="submit"
        >
            Sign Up
        </button>

        <div class="footnote">
            <span class="footnote-text">Already have an Account? </span>
            <RouterLink to="/login" class="footnote-link">Login</RouterLink>
        </div>
    </div>
</template>

<style scoped></style>
