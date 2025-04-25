<script setup lang="ts">
import { ref } from 'vue'
import { useApi } from '@/store/useApi.ts'
import InputField from '@/components/InputField.vue'
import ButtonComponent from '@/components/ButtonComponent.vue'
import LoginProviders from '@/components/Auth/LoginProviders.vue'
import router from '@/router'
import Notice from '@/components/Notice.vue'

const DEBOUNCE_TIME = 500

const registerError = ref(false)
const api = useApi()

const form = api.useRegisterForm()

function submit() {
    registerError.value = false
    form.submit()
        .then((res) => {
            console.log('SUCC: ', res)
            // router.push('/').then(() => router.go(0))
        })
        .catch((err) => {
            if (err.status != 422) {
                // not just a check -> something went wrong
                registerError.value = true
            }
        })
}

const validateTimeouts = new Map<string, number>()

function validate(validate: any) {
    const prevTimeout = validateTimeouts.get(validate)
    if (prevTimeout) {
        clearInterval(prevTimeout)
    }

    validateTimeouts.set(
        validate,
        setTimeout(() => form.validate(validate), DEBOUNCE_TIME),
    )
}

const rememberMe = ref<boolean>(false)
</script>

<template>
    <div class="auth-container register-container full-height" role="form">
        <h1 class="title">Register on CircuitForge</h1>

        <LoginProviders class="login-providers-container" />

        <div class="login-divider">or</div>

        <div class="form-section">
            <Notice type="error" v-if="registerError"
                >Ops! An error occurred while creating user
            </Notice>

            <InputField
                label="username"
                :show-notice="form.invalid('username')"
                :show-outline="form.invalid('username')"
                :notice-text="form.errors.username"
                v-model="form.username"
                autocomplete="username"
                @input="validate('username')"
            />

            <InputField
                label="email"
                type="email"
                :show-notice="form.invalid('email')"
                :show-outline="form.invalid('email')"
                :notice-text="form.errors.email"
                v-model="form.email"
                autocomplete="email"
                @input="validate('email')"
            />

            <InputField
                label="password"
                type="password"
                :show-outline="form.invalid('password')"
                :show-notice="form.invalid('password')"
                :notice-text="form.errors.password"
                autocomplete="new-password"
                v-model="form.password"
                @input="validate('password')"
            />

            <InputField
                label="confirm password"
                type="password"
                :show-outline="form.invalid('password_confirmation')"
                :show-notice="form.invalid('password_confirmation')"
                :notice-text="form.errors.password_confirmation"
                autocomplete="new-password"
                v-model="form.password_confirmation"
                @input="validate('password_confirmation')"
            />

            <div class="remember-me">
                <input type="checkbox" name="remember-me" id="remember-me" v-model="rememberMe" />
                <label for="remember-me" class="checkbox"></label>
                <label for="remember-me">remember me</label>
            </div>
        </div>

        <ButtonComponent
            class="signup"
            data-form-type="register"
            :disabled="form.processing"
            button-type="primary"
            size="medium"
            @click="submit"
            type="submit"
            >Sign Up
        </ButtonComponent>

        <div class="footnote">
            <span class="footnote-text">Already have an Account? </span>
            <RouterLink to="/login" class="footnote-link">Login</RouterLink>
        </div>
    </div>
</template>

<style scoped>
@import '../assets/auth-views.css';
</style>
