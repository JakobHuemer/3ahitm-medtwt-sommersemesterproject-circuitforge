<script setup lang="ts">
import { useRoute } from 'vue-router'
import { type ApiResponse, ApiResponseType } from '@/types/ApiResponse.ts'
import { ref } from 'vue'
import ButtonComponent from '@/components/ButtonComponent.vue'
import { faHome } from '@fortawesome/free-solid-svg-icons'
import router from '@/router'
import { useNotice } from '@/store/useNotice.ts'

const hash = useRoute().hash.substring(1)
const error = ref<boolean>(false)

const notice = useNotice()

let response: ApiResponse | undefined

try {
    response = JSON.parse(atob(hash))
} catch (e) {
    error.value = true
}

console.log(response)

switch (response?.responseType) {
    case ApiResponseType.AUTH_ADD:
        if (response.data.success) {
            notice.success(response.responseType, response.data.message)
        } else {
            notice.error(response.responseType, response.data.message)
        }
        router.push('/settings/account')
        break
    case ApiResponseType.AUTH_LOGIN:
        if (!response.data.success) {
            notice.error('auth_login', response.data.error ?? 'Login failed!')
            router.push('/login')
            break
        }
        router.push('/')
        break
    default:
        error.value = true
}
</script>

<template>
    <div class="wrapper">
        <div class="fail-message" v-if="error">
            <h1 class="message">
                Ops! Something<br />
                went wrong!
            </h1>
            <ButtonComponent
                :icon="faHome"
                button-type="primary"
                size="medium"
                @click="router.push('/')"
                >Go Home
            </ButtonComponent>
        </div>
    </div>
</template>

<style scoped>
.wrapper {
    min-height: 100svh;
    display: grid;
    place-items: center;

    .fail-message {
        background: var(--col-container);
        display: grid;
        place-items: center;

        padding: var(--gap-32);
        gap: var(--gap-32);
        border-radius: var(--border-radius);

        font-family: var(--font-title);

        h1 {
            font-family: inherit;
        }
    }
}
</style>
