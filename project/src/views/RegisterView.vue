<script setup lang="ts">
import { computed, reactive, ref, shallowRef, watch } from 'vue'
import { useApi } from '@/store/useApi.ts'
import { watchDebounced } from '@vueuse/core'
import InputField from '@/components/InputField.vue'

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
            <!--            Username -->
            <InputField
                label="username"
                color="error"
                :show-notice="!!displayErrors.username && touchedFields.has('username')"
                :show-outline="!!displayErrors.username && touchedFields.has('username')"
                :notice-text="displayErrors.username"
                v-model="username"
                autocomplete="username"
                @input="touchedFields.add('username')"
            />

            <!--            Email -->
            <InputField
                label="email"
                type="email"
                :show-notice="!!displayErrors.email && touchedFields.has('email')"
                :show-outline="!!displayErrors.email && touchedFields.has('email')"
                :notice-text="displayErrors.email"
                v-model="email"
                autocomplete="email"
                @input="touchedFields.add('email')"
            />

            <!--            Password -->
            <InputField
                label="password"
                type="password"
                v-model="password"
                :show-outline="!!displayErrors.password && touchedFields.has('password')"
                :show-notice="!!displayErrors.password && touchedFields.has('password')"
                :notice-text="displayErrors.password"
                autocomplete="new-password"
                @input="touchedFields.add('password')"
            />

            <!--            Confirm Password -->
            <InputField
                label="confirm password"
                type="password"
                v-model="confirmPassword"
                :show-outline="!doPasswordsMatch && touchedFields.has('confirm-password')"
                :show-notice="!doPasswordsMatch && touchedFields.has('confirm-password')"
                notice-text="passwords do not match"
                autocomplete="new-password"
                @input="touchedFields.add('confirm-password')"
            />

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

<style scoped>
@import '../assets/auth-views.css';
</style>
