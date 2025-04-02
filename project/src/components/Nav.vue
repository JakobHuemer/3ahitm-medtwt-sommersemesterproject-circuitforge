<script setup lang="ts"></script>

<template>
    <div class="wrapper">
        <div class="container">
            <h1><RouterLink to="/">CircuitForge</RouterLink></h1>
            <input type="text" name="search" id="search" class="search" placeholder="Search" />
            <div class="nav-items">
                <RouterLink to="/posts">Posts</RouterLink>
                <RouterLink to="/discussions">Discussions</RouterLink>
                <RouterLink to="/register">Register</RouterLink>
                <RouterLink to="/login">Login</RouterLink>
                <RouterLink to="/sub/view">SubView</RouterLink>
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

    h1 {
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
        gap: var(--gap-16);
        font-family: var(--font-title);
    }
}
</style>
