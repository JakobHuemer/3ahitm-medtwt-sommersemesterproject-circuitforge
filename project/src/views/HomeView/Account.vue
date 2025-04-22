<script setup lang="ts">
import InputField from '@/components/InputField.vue'
import { computed, ref } from 'vue'
import { useApi } from '@/store/useApi.ts'
import ButtonComponent from '@/components/ButtonComponent.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCircleXmark, faImage } from '@fortawesome/free-solid-svg-icons'
import SocialConnection from '@/components/Settings/SocialConnection.vue'

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

                <SocialConnection
                    provider="google"
                    :active="true"
                    email="jakki@gmail.com"
                    name="Jakob Huemer"
                />

                <SocialConnection
                    provider="github"
                    :active="false"
                    email="jakob.fistelberger@gmail.com"
                    name="JakobFistelberger"
                />
                <SocialConnection
                    provider="github"
                    :active="true"
                    email="j.huemer-fistelberger@htblaleonding.onmicrosoft.com"
                    name="J.H.F"
                />

                <SocialConnection
                    provider="discord"
                    :active="true"
                    email="jakkidummy@gmail.com"
                    name="jakki_"
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
                content: "";
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

</style>
