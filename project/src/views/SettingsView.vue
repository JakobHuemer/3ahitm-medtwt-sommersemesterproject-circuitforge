<script setup lang="ts"></script>

<template>
    <h2 class="header">Settings</h2>
    <div class="container">
        <div class="side-bar">
            <RouterLink to="preferences">Preferences</RouterLink>
            <RouterLink to="account">Account</RouterLink>
        </div>
        <div class="main-content">
            <RouterView />
        </div>
    </div>
</template>

<style scoped>
.container {
    padding: 0;
    display: flex;
    overflow: hidden;
    border-radius: var(--border-radius);
}

.main-content,
.side-bar {
    padding: var(--gap-16);


    a {
        position: relative;
        outline: none;

        &.router-link-exact-active::before {
            content: "";
            height: 100%;
            width: 5px;
            left: -10px;
            position: absolute;

            background: var(--col-accent);
        }
    }
}

h2 {
    font-family: var(--font-title);
    font-size: var(--font-size-body);
    padding: var(--gap-16);
    text-align: center;
}

:deep(h3) {
    font-family: var(--font-title);
    font-size: var(--font-size-nav-item);
    //margin-block-end: var(--gap-8);
}

:deep(h4) {
    font-size: calc((var(--font-size-body) + var(--font-title)) / 2);
    margin-block-end: var(--gap-8);
}

.side-bar {
    display: flex;
    flex-direction: column;
    gap: var(--gap-16);

    background: var(--col-content);

    a {
        font-size: var(--font-size-body);
        font-family: var(--font-title);
    }
}

.main-content {
    flex: 1;
}
</style>
