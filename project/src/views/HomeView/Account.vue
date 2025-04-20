<script setup lang="ts">
import InputField from '@/components/InputField.vue'
import { computed, ref } from 'vue'
import { useApi } from '@/store/useApi.ts'
import ButtonComponent from '@/components/ButtonComponent.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCircleXmark, faImage } from '@fortawesome/free-solid-svg-icons'
import Notice from '@/components/Notice.vue'
import { faDiscord, faGithub, faGoogle } from '@fortawesome/free-brands-svg-icons'

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

        <h3>Account</h3>

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
            <h4>Email</h4>

            <InputField label="email" />

            <div class="buttons">
                <ButtonComponent size="normal">Resend Verification</ButtonComponent>
                <ButtonComponent size="normal" button-type="primary">Update Email</ButtonComponent>
            </div>
        </section>

        <section class="password">
            <h4>Password</h4>

            <div class="inputs">
                <InputField label="current password" autocomplete="current-password" />
                <InputField label="new password" autocomplete="new-password" />
                <InputField label="confirm new password" autocomplete="new-password" />
            </div>

            <ButtonComponent button-type="primary">Change Password</ButtonComponent>
        </section>

        <section class="connections">
            <h4>Connections</h4>

            <div class="connections-list">

                <div class="connection-item">

                    <div class="icon">
                        <FontAwesomeIcon :icon="faGoogle" />
                    </div>

                    <div class="main">
                        <div class="main-content">
                            <div class="header">
                                <span class="provider">Google</span>
                                <span class="status status-active">active</span>
                            </div>
                            <div class="footer">
                                <span class="email">jakki@gmail.com</span>
                                <span class="name">Jakob Huemer</span>
                            </div>
                        </div>

                        <div class="end">
                            <FontAwesomeIcon :icon="faCircleXmark" />
                        </div>
                    </div>

                </div>

                <div class="connection-item">

                    <div class="icon">
                        <FontAwesomeIcon :icon="faDiscord" />
                    </div>

                    <div class="main">
                        <div class="main-content">
                            <div class="header">
                                <span class="provider">Google</span>
                                <span class="status status-inactive">inactive</span>
                            </div>
                            <div class="footer">
                                <span class="email">jakki@gmail.com</span>
                                <span class="name">Jakob Huemer</span>
                            </div>
                        </div>

                        <div class="end">
                            <ButtonComponent>reactivate</ButtonComponent>
                            <FontAwesomeIcon :icon="faCircleXmark" />
                        </div>
                    </div>

                </div>

                <div class="connection-item">

                    <div class="icon">
                        <FontAwesomeIcon :icon="faGithub" />
                    </div>

                    <div class="main">
                        <div class="main-content">
                            <div class="header">
                                <span class="provider">Google</span>
                                <span class="status status-active">active</span>
                            </div>
                            <div class="footer">
                                <span class="email">jakki@gmail.com</span>
                                <span class="name">Jakob Huemer</span>
                            </div>
                        </div>

                        <div class="end">
                            <FontAwesomeIcon :icon="faCircleXmark" />
                        </div>
                    </div>

                </div>



            </div>
        </section>


        <section class="danger">
            <h4>Danger Zone</h4>

            <ButtonComponent button-type="primary">Delete Account</ButtonComponent>
        </section>
    </div>
</template>

<style scoped>
@import "../../assets/settings-page.css";

.connections-list {
    display: flex;
    flex-direction: column;
    align-items: stretch;
    gap: var(--gap-8);
}

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

                    &.status-inactive {
                        --color: var(--col-warn);
                    }

                }

            }

            .footer {
                margin-top: var(--gap-4);
                display: flex;
                gap: var(--gap-8);

                .name::before {
                    content: "-";
                    margin-right: var(--gap-8);
                }
            }

        }

        .end {
            display: flex;
            gap: var(--gap-16);
            align-items: center;
            margin-right: var(--gap-12);
        }
    }
}


</style>
