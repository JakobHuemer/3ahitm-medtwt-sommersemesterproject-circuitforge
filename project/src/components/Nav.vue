<script setup lang="ts">
import { useApi } from '@/store/useApi.ts'

const api = useApi()
</script>

<template>
    <div class="wrapper">
        <div class="container">
            <h1>
                <RouterLink to="/">CircuitForge</RouterLink>
            </h1>
            <!--            <input type="text" name="search" id="search" class="search" placeholder="Search" />-->
            <div class="nav-items">
                <RouterLink to="/posts">Posts</RouterLink>
                <RouterLink to="/discussions">Discussions</RouterLink>
                <template v-if="api.state.isAuthenticated">
                    <div class="nav-user">
                        <div class="nav-part">
                            <div class="nav-pb">
                                <img
                                    :src="`https://www.mc-heads.net/avatar/${api.state.user?.username}`"
                                    alt="PB"
                                />
                            </div>
                            <span class="username">
                                {{ api.state.user?.username ?? api.state.user?.name }}
                            </span>
                        </div>

                        <div class="nav-user-dropdown">
                            <div class="nav-user-dropdown-container">
                                <button @click="api.logout()">Logout</button>
                                <RouterLink to="/settings/preferences">Preferences</RouterLink>
                            </div>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <RouterLink to="/register">Register</RouterLink>
                    <RouterLink to="/login">Login</RouterLink>
                </template>
            </div>
        </div>
    </div>
</template>

<style scoped>
.wrapper {
    width: 100%;
    padding: var(--gap-16);
    background: var(--col-container);
}

.container {
    margin-inline: auto;
    max-width: var(--main-width);
    display: flex;
    gap: var(--gap-32);
    justify-content: space-between;
    align-items: end;

    h1 a {
        font-family: var(--font-title);
    }

    input {
        padding: var(--gap-8);
        line-height: 1;
        background: var(--col-content);
        color: var(--col-text-primary);
        border-radius: var(--border-radius);
        border: none;
        width: 100%;

        &:hover {
            background: var(--col-content-hover);
        }
    }

    .nav-items {
        display: flex;
        align-items: end;
        gap: var(--gap-16);
        font-family: var(--font-title);

        & * {
            font-family: inherit;
        }
    }
}

.nav-user {
    position: relative;

    .nav-part {
        display: flex;
        gap: var(--gap-4);
        align-items: center;

        .nav-pb {
            height: calc(var(--font-size-nav-item) - 4px);

            img {
                height: 100%;
                border-radius: 100vw;
            }
        }
    }

    &:hover .nav-user-dropdown,
    .nav-user-dropdown:hover {
        display: block;
    }
}

.nav-user-dropdown {
    z-index: 10;
    position: absolute;
    top: 100%;
    display: none;

    .nav-user-dropdown-container {
        margin-block-start: 0.5rem;

        border-radius: var(--border-radius-s);
        overflow: hidden;

        background: var(--col-content);
        display: grid;

        button,
        a {
            outline: none;
            border: 0;
            color: white;
            background: inherit;
            width: 100%;
            text-align: left;
            font-family: var(--font-title);
            font-size: 80%;

            padding: var(--gap-4) var(--gap-8);

            transition: background 0.2s;

            &:hover {
                background: var(--col-surface);
            }
        }
    }
}
</style>
