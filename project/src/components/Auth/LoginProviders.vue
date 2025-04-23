<script setup lang="ts">
import { ref } from 'vue'
import { faDiscord, faGithub, faGoogle } from '@fortawesome/free-brands-svg-icons'
import ButtonComponent from '@/components/ButtonComponent.vue'

const props = defineProps<{
    authAdd?: boolean
}>()

const basePath = ref(import.meta.env.BASE_URL)

const providers = ref([
    {
        provider: 'Google',
        icon: faGoogle,
        bgColor: '#4285F4',
        logoColor: 'white',
    },
    {
        provider: 'Discord',
        icon: faDiscord,
        bgColor: '#5865F2',
        logoColor: 'white',
    },
    {
        provider: 'GitHub',
        icon: faGithub,
        bgColor: '#24292e',
        logoColor: 'white',
    },
])
</script>

<template>
    <div class="login-providers">
        <div v-for="prov of providers" class="provider">
            <a
                :href="
                    basePath +
                    '/../../api/public/auth' +
                    (authAdd ? '-add/' : '/') +
                    prov.provider.toLowerCase() +
                    '/redirect'
                "
            >
                <ButtonComponent
                    :icon="prov.icon"
                    size="normal"
                    :style="'background: ' + prov.bgColor + ';color: ' + prov.logoColor"
                    >{{ authAdd ? 'add ' + prov.provider : '' }}</ButtonComponent
                >
            </a>
        </div>
    </div>
</template>

<style scoped>
.login-providers {
    display: flex;
    gap: var(--gap-16);
    justify-content: center;
}
</style>
