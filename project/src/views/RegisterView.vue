<script setup lang="ts">
import { computed, reactive, ref, shallowRef, watch } from 'vue'
import { useApi } from '@/store/useApi.ts'
import { watchDebounced } from '@vueuse/core'

const api = useApi()

const username = ref<string>('')

const email = ref<string>('')

const password = ref<string>('')

const confirmPassword = ref<string>('')

const rememberMe = ref<boolean>(false)

const checked = ref<boolean>(false)

const touchedFields = shallowRef<Set<string>>(new Set([]))

type ErrorsType = {
    username: string
    email: string
    password: string
}

const noErrors: ErrorsType = {
    username: '',
    email: '',
    password: '',
}

const displayErrors = reactive<ErrorsType>(Object.create(noErrors))
const allErrors = reactive<ErrorsType>(Object.create(noErrors))

const doPasswordsMatch = computed<boolean>(() => {
    return password.value == confirmPassword.value
})

watchDebounced(
    [username, email, password],
    async (value, oldValue, onCleanup) => {
        console.log(api)
        console.log(value)
        console.log(oldValue)
        console.log(onCleanup)
        api.api
            .post('/dry-register', {
                username: username.value,
                email: email.value,
                password: password.value,
            })
            .then(() => {
                let t: keyof ErrorsType
                for (t in displayErrors) {
                    displayErrors[t] = ''
                }
            })
            .catch((e) => {
                console.log(JSON.stringify(e.response.data.errors, null, 2))
                const errorsResponse = e.response.data.errors ?? {}

                let t: keyof ErrorsType

                for (t in displayErrors) {
                    if (errorsResponse[t]) {
                        allErrors[t] = errorsResponse[t][0]
                        if (touchedFields.value.has(t)) {
                            console.log('HAS: ', t)
                            displayErrors[t] = errorsResponse[t][0]
                        }
                    } else {
                        displayErrors[t] = ''
                    }
                }
            })
            .finally(() => {
                checked.value = true

                console.log('TOUCHED:', touchedFields.value)
                console.log('ALL:', JSON.stringify(allErrors, null, 2))
                console.log('DISPLAY:', JSON.stringify(displayErrors, null, 2))
                console.log('----------------')
            })
    },
    {
        debounce: 500,
        maxWait: 10_000_000,
    },
)

function doRegister() {
    console.debug('Signup Triggered')

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
                    <span
                        class="auth-error username-error"
                        v-if="!!displayErrors.username && touchedFields.has('username')"
                        >{{ displayErrors.username }}</span
                    >
                </label>
                <input
                    @input="touchedFields.add('username')"
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
                    <span
                        class="auth-error email-error"
                        v-if="!!displayErrors.email && touchedFields.has('email')"
                        >{{ displayErrors.email }}</span
                    >
                </label>
                <input
                    @input="touchedFields.add('email')"
                    type="email"
                    name="email"
                    id="email"
                    v-model="email"
                    autocomplete="email"
                />
            </div>

            <div class="input input-password">
                <label for="password">
                    <span>password</span>
                    <span
                        class="auth-error password-error"
                        v-if="!!displayErrors.password && touchedFields.has('password')"
                        >{{ displayErrors.password }}</span
                    >
                </label>
                <input
                    @input="touchedFields.add('password')"
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
                    <span
                        class="auth-error confirm-password-error"
                        v-if="!doPasswordsMatch && touchedFields.has('confirm-password')"
                        >passwords do not match</span
                    >
                </label>
                <input
                    @input="touchedFields.add('confirm-password')"
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
            :disabled="
                !doPasswordsMatch || Object.values(displayErrors).join('') !== '' || !checked
            "
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
