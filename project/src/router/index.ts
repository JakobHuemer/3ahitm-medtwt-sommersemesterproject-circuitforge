import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '@/views/HomeView.vue'
import SubView from '@/views/SubView.vue'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'home',
            component: HomeView,
            alias: '/index.html',
        },
        {
            path: '/sub/view',
            name: 'subview',
            component: SubView,
        },
    ],
})

export default router
