<script setup lang="ts">
import { faDiscord, faGithub, faGoogle } from '@fortawesome/free-brands-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import ButtonComponent from '@/components/ButtonComponent.vue'
import type { IconDefinition } from '@fortawesome/fontawesome-svg-core'
import { computed } from 'vue'
import { useApi } from '@/store/useApi.ts'

const api = useApi()

const props = defineProps<{
    id: number
    provider: string
    active: boolean
    email: string
    name?: string
}>()

const emit = defineEmits(['delete'])

const providerIconList: Map<
    string,
    { icon: IconDefinition; bgColor: string; logoColor: string; display: string }
> = new Map([
    ['google', { icon: faGoogle, bgColor: '#4285F4', logoColor: 'white', display: 'Google' }],
    ['discord', { icon: faDiscord, bgColor: '#5865F2', logoColor: 'white', display: 'Discord' }],
    ['github', { icon: faGithub, bgColor: '#24292e', logoColor: 'white', display: 'GitHub' }],
])

const providerColor = computed(() => {
    return providerIconList.get(props.provider)
})

function removeSocialConnection() {
    api.api
        .delete('/socials/' + props.id)
        .then((res) => {
            console.log('RESPONSE', res)
            emit('delete')
        })
        .catch((err) => {
            console.log('ERROR', err)
        })
}
</script>

<template>
    <div class="connection-item">
        <div
            class="icon"
            :style="'fill: ' + providerColor?.logoColor + '; background: ' + providerColor?.bgColor"
        >
            <FontAwesomeIcon v-if="providerColor" :icon="providerColor.icon" />
        </div>

        <div class="main">
            <div class="main-content">
                <div class="header">
                    <span class="provider">{{ providerColor?.display ?? provider }}</span>
                    <span class="status" :data-active="active">{{
                        active ? 'active' : 'inactive'
                    }}</span>
                </div>
                <div class="footer">
                    <span class="email">{{ email }}</span>
                    <span v-if="name" class="name">{{ name }}</span>
                </div>
            </div>

            <div class="end">
                <ButtonComponent v-if="!active" button-type="secondary">reactivate</ButtonComponent>
                <ButtonComponent button-type="error" @click="removeSocialConnection"
                    >remove</ButtonComponent
                >
            </div>
        </div>
    </div>
</template>

<style scoped>
.connection-item {
    display: flex;
    align-items: center;
    height: 100%;

    border-radius: var(--border-radius);
    overflow: hidden;
    background: var(--col-content);

    .icon {
        height: 100%;
        aspect-ratio: 1;

        background: white;
        color: black;

        display: grid;
        place-items: center;

        svg {
            height: 50%;
            background: transparent;
        }
    }

    .main {
        padding: var(--gap-8);

        display: flex;
        flex: 1;
        align-items: center;
        justify-content: space-between;

        .main-content {
            .header {
                display: flex;
                gap: var(--gap-8);
                align-items: center;

                .provider {
                    font-size: var(--font-size-card-title);
                }

                .status {
                    --color: var(--col-success);

                    background: var(--col-container);
                    color: var(--color);
                    border-radius: var(--border-radius-s);
                    padding: var(--gap-4) var(--gap-8);

                    &[data-active='false'] {
                        --color: var(--col-warn);
                    }
                }
            }

            .footer {
                margin-top: var(--gap-4);
                display: flex;
                gap: var(--gap-8);

                .name::before {
                    content: '-';
                    margin-right: var(--gap-8);
                }
            }
        }

        .end {
            display: flex;
            gap: var(--gap-12);
            align-items: center;
            margin-right: var(--gap-12);
        }
    }
}
</style>
