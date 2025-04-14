<script setup lang="ts">
import { useApi } from '@/store/useApi.ts'
import { computed, ref } from 'vue'
import type User from '@/types/user'

const api = useApi()
const user = ref<null | User>(api.state.user)

const content = computed(() => {
    return JSON.stringify(api.state.user, null, 4)
})
</script>

<template>
    <div class="container">
        <pre>{{ content }}</pre>
    </div>
</template>

<style scoped></style>
