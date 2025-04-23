<script setup lang="ts">
import InputField from '@/components/InputField.vue'
import { computed, onMounted, ref, shallowRef } from 'vue'
import { useApi } from '@/store/useApi.ts'
import ButtonComponent from '@/components/ButtonComponent.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faImage } from '@fortawesome/free-solid-svg-icons'
import SocialConnection from '@/components/Settings/SocialConnection.vue'
import Notice from '@/components/Notice.vue'
import LoginProviders from '@/components/Auth/LoginProviders.vue'
import { NoticeType, useNotice } from '@/store/useNotice.ts'

const api = useApi()

const user = computed(() => {
    return api.state.user
})

const username = ref<string>('')
const name = ref<string>('')

const notices = useNotice()

notices.on('notice', (notice) => {
    console.log('NOTICE')
    console.log('NOTICE')
    console.log('NOTICE')
    console.log(notice)
})

interface Connection {
    id: number
    user_id: number
    email: string
    provider: string
}

const connections = ref<Connection[]>([])
const connectionsLoaded = ref(false)
const connectionsError = ref(false)

onMounted(() => {
    username.value = api.state.user?.username ?? ''
    name.value = api.state.user?.name ?? ''

    // fetch connections

    api.api
        .get<Connection[]>('/socials')
        .then((res) => {
            for (const conn of res.data) {
                connections.value.push(conn)
            }
        })
        .catch(() => (connectionsError.value = true))
        .finally(() => {
            connectionsLoaded.value = true
        })

    console.log(notices.get(NoticeType.ERROR, 'auth_add'))
    console.log(notices.get(NoticeType.ERROR, 'auth_add'))
    console.log(notices.get(NoticeType.ERROR, 'auth_add'))
})

const noticeObj = shallowRef(
    notices.popNotices([{ noticeType: NoticeType.ERROR, name: 'auth_add' }]),
)

console.log('noticeObj', noticeObj.value)
</script>

<template>
    <div class="wrapper">
        <h3>Profile</h3>
        <!--    Basic -->
        <section class="basic">
            <div class="left">
                <img v-if="user?.avatar" :src="user?.avatar" alt="PP" class="profile-picture" />

                <div v-else class="profile-picture profile-picture-placeholder">
                    <FontAwesomeIcon :icon="faImage" />
                </div>
            </div>
            <div class="right">
                <InputField v-model="username" label="username" />
                <InputField v-model="name" label="name" />
                <ButtonComponent button-type="primary">Update</ButtonComponent>
            </div>
        </section>

        <section class="email">
            <h3>Email</h3>

            <InputField label="email" />

            <div class="buttons">
                <ButtonComponent size="normal">Resend Verification</ButtonComponent>
                <ButtonComponent size="normal" button-type="primary">Update Email</ButtonComponent>
            </div>
        </section>

        <section class="password">
            <h3>Password</h3>

            <div class="inputs">
                <InputField label="current password" autocomplete="current-password" />
                <InputField label="new password" autocomplete="new-password" />
                <InputField label="confirm new password" autocomplete="new-password" />
            </div>

            <ButtonComponent button-type="primary">Change Password</ButtonComponent>
        </section>

        <section class="connections">
            <h3>Connections</h3>

            <div class="connections-list">
                <Notice type="error" v-if="noticeObj.get('error:auth_add')"
                    >{{ noticeObj.get('error:auth_add')!.message }}
                </Notice>

                <SocialConnection
                    v-if="!connectionsError && connectionsLoaded"
                    v-for="conn of connections"
                    @delete="connections.splice(connections.indexOf(conn))"
                    :id="conn.id"
                    :provider="conn.provider"
                    :active="true"
                    :email="conn.email"
                />

                <!-- Loading -->
                <span v-if="!connectionsLoaded">Loading ...</span>

                <Notice type="error" v-if="connectionsError">Failed to get Connections!</Notice>
                <LoginProviders
                    class="login-providers-add-list"
                    style="justify-content: left"
                    :auth-add="true"
                />
            </div>
        </section>

        <section class="danger">
            <h3>Danger Zone</h3>

            <ButtonComponent button-type="primary">Delete Account</ButtonComponent>
        </section>
    </div>
</template>

<style scoped>
@import '../../assets/settings-page.css';

.basic {
    display: flex;

    align-items: center;

    .left {
        .profile-picture {
            width: 8rem;
            aspect-ratio: 1;
            border-radius: var(--border-radius-s);
            position: relative;

            &:hover::before {
                content: '';
                position: absolute;
                height: 100%;
                width: 100%;
                border-radius: var(--border-radius-s);
                background: color-mix(in srgb, var(--col-content-hover), transparent);
                cursor: pointer;
            }

            &.profile-picture-placeholder {
                display: grid;
                place-items: center;
                background: var(--col-content);

                svg {
                    height: 30%;
                }
            }
        }
    }

    .right {
        flex: 1;

        display: grid;
        gap: var(--gap-8);

        button {
            margin-block-start: var(--gap-8);
        }
    }
}

.email {
    .buttons {
        display: flex;
        gap: var(--gap-8);
    }
}

.password {
    .inputs {
        display: grid;
        gap: var(--gap-8);
    }
}

.connections-list {
    display: flex;
    flex-direction: column;
    align-items: stretch;
    gap: var(--gap-8);
}

.login-providers-add-list {
    padding-block: var(--gap-8);
}
</style>
