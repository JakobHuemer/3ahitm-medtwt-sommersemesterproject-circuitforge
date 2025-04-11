import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '@/views/HomeView.vue'
import CreatePostView from '@/views/CreatePostView.vue'
import LoginView from '@/views/LoginView.vue'
import RegisterView from '@/views/RegisterView.vue'

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
        {
            path: '/login',
            name: 'login',
            component: LoginView,
            meta: {
                hideNav: true,
            },
        },
        {
            path: '/register',
            name: 'register',
            component: RegisterView,
            meta: {
                hideNav: true,
            },
        },
    ],
})

export default router
