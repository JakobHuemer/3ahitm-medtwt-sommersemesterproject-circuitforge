<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
import Nav from '@/components/Nav.vue'

let url = window.location.href

// fetch /api/posts with vue
</script>

<template>
    <Nav />
    <div class="main-wrapper">
        <main>
            <RouterView />
        </main>
    </div>
</template>

<style scoped>
.main-wrapper {
    display: grid;
}

main {
    width: var(--main-width);
    display: block;
    margin: var(--gap-32);
}
</style>
