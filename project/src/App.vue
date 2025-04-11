<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
import Nav from '@/components/Nav.vue'

let url = window.location.href

// fetch /api/posts with vue
</script>

<template>
    <Nav v-if="!$route.meta.hideNav" />
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
    margin-inline: auto;
}

.main-wrapper:has(.full-height),
main:has(.full-height) {
    min-height: 100svh;

    &:is(main) {
        display: grid;
        place-items: center;
    }
}
</style>
