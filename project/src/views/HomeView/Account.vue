<script setup lang="ts">
import InputField from '@/components/InputField.vue'
import { computed, ref } from 'vue'
import { useApi } from '@/store/useApi.ts'
import ButtonComponent from '@/components/ButtonComponent.vue'

const api = useApi()

const user = computed(() => {
    return api.state.user
})

const username = ref<string>('')
const name = ref<string>('')

api.runWhenFinished(() => {
    username.value = api.state.user?.username ?? ''
    name.value = api.state.user?.name ?? ''
})
</script>

<template>
    <h3>Account</h3>

    <!--    Basic -->
    <section class="basic">
        <div class="left">
            <img :src="user?.avatar" alt="PP" class="profile-picture" />
        </div>
        <div class="right">
            <InputField v-model="username" label="username" />
            <InputField v-model="name" label="name" />
            <ButtonComponent button-type="primary">Update</ButtonComponent>
        </div>
    </section>
</template>

<style scoped></style>
