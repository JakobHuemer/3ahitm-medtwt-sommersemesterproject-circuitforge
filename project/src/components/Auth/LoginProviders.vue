<script setup lang="ts">
import { ref } from 'vue'
import { faDiscord, faGithub, faGoogle } from '@fortawesome/free-brands-svg-icons'
import ButtonComponent from '@/components/ButtonComponent.vue'
import { useRouter } from 'vue-router'

const router = ref(useRouter())
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
                    basePath + '/../../api/public/auth/' + prov.provider.toLowerCase() + '/redirect'
                "
            >
                <ButtonComponent
                    :icon="prov.icon"
                    size="medium"
                    :style="'background: ' + prov.bgColor + ';color: ' + prov.logoColor"
                />
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
