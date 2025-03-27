<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
import Nav from '@/components/Nav.vue'

let url = window.location.href
</script>

<template>
    <Nav />
    <main>
        <RouterView />
    </main>
</template>

<style scoped>

main {
    margin: var(--gap-32);
}

</style>
