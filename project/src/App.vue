<script setup lang="ts">
import { RouterView } from 'vue-router'
import Nav from '@/components/Nav.vue'
import { ref } from 'vue'

import creeper from '@/assets/img/background/creeper.jpg'
import rails from '@/assets/img/background/rails.jpg'
import og from '@/assets/img/background/og.jpg'
import planet from '@/assets/img/background/planet.jpg'
import forest from '@/assets/img/background/forest.png'
import plains from '@/assets/img/background/plains.jpg'

let images = [creeper, rails, og, planet, creeper, forest, plains]
const bgImage = ref(images[Math.floor(Math.random() * images.length)])
</script>

<template>
    <Nav class="root-navigation" v-if="!$route.meta.hideNav" />
    <div
        class="main-wrapper"
        :style="
            $route.meta.hideNav
                ? 'background: linear-gradient(rgba(0, 0, 0, .7), rgba(0, 0, 0, 0.7)), url(' +
                  bgImage +
                  ') center / cover no-repeat'
                : ''
        "
    >
        <main>
            <RouterView />
        </main>
    </div>
</template>

<style>
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
