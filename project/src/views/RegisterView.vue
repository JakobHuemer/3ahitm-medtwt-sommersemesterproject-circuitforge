<script setup lang="ts">
import { computed, reactive, ref, watch } from 'vue'
import { useApi } from '@/store/useApi.ts'
import { watchDebounced } from '@vueuse/core'

const api = useApi()

const username = ref<string>('')

const email = ref<string>('')

const password = ref<string>('')

const confirmPassword = ref<string>('')

const rememberMe = ref<boolean>(false)

type errorsType = {
    username: string,
    email: string,
    password: string
}

const noErrors: errorsType = {
    username: '',
    email: '',
    password: ''
}

const errors = reactive<errorsType>(noErrors)

const doPasswordsMatch = computed<boolean>(() => {
    return password.value == confirmPassword.value
        && password.value.length > 0
})

watchDebounced(
    [ username, email, password ],
    async () => {

        console.log(api)

        api.api.post('/dry-register', {
            username: username.value,
            email: email.value,
            password: password.value
        })
            .then(() => {
                let t: keyof errorsType
                for ( t in errors ) {
                    errors[t] = ''
                }
            })
            .catch((e) => {
                console.log(JSON.stringify(e.response.data.errors, null, 2))
                const errorsResponse = e.response.data.errors ?? {}

                let t: keyof errorsType

                for ( t in errors ) {

                    if ( errorsResponse[t] ) {
                        errors[t] = errorsResponse[t][0]
                    } else {
                        errors[t] = ''
                    }
                }

            })

    },
    {
        debounce: 500,
        maxWait: 10_000_000
    }
)

function doRegister() {
    console.log('Signup Triggered')

    //TODO: implement error handling before making request

    const result = api.register(username.value, email.value, password.value)
}
</script>

<template>
    <div class="auth-container register-container full-height" role="form">
        <h1 class="title">Register on CircuitForge</h1>
        <div class="form-section">
            <div class="input input-username">
                <label for="username">
                    <span>username</span>
                    <span class="auth-error username-error" v-if="!!errors.username">{{
                            errors.username
                        }}</span>
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
                    <span class="auth-error email-error" v-if="!!errors.email">{{
                            errors.email
                        }}</span>
                </label>
                <input type="email" name="email" id="email" v-model="email" autocomplete="email" />
            </div>

            <div class="input input-password">
                <label for="password">
                    <span>password</span>
                    <span class="auth-error password-error" v-if="!!errors.password">{{
                            errors.password
                        }}</span>
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

            <div class="remember-me">
                <input type="checkbox" name="remember-me" id="remember-me" v-model="rememberMe" />
                <label for="remember-me" class="checkbox"></label>
                <label for="remember-me">remember me</label>
            </div>
        </div>

        <button
            class="signup"
            data-form-type="register"
            :disabled="!doPasswordsMatch || Object.values(errors).join('') !== ''"
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
