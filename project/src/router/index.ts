import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '@/views/HomeView.vue'
import CreatePostView from '@/views/CreatePostView.vue'

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
            path: '/post/create',
            name: 'create post',
            component: CreatePostView,
        },
    ],
})

export default router
