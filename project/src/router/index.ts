import { createRouter, createWebHistory } from 'vue-router'
import SettingsView from '@/views/SettingsView.vue'
import CreatePostView from '@/views/PostView/CreatePostView.vue'
import LoginView from '@/views/LoginView.vue'
import RegisterView from '@/views/RegisterView.vue'
import UserView from '@/views/UserView.vue'
import Account from '@/views/SettingsView/Account.vue'
import HomeView from '@/views/HomeView.vue'
import Preferences from '@/views/SettingsView/Preferences.vue'
import ApiCallbackHandler from '@/views/ApiCallbackHandler.vue'
import PostView from '@/views/PostView/PostView.vue'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'CircuitForge',
            component: HomeView,
            alias: '/index.html',
        },
        {
            path: '/post/create',
            name: 'Create Post',
            component: CreatePostView,
        },
        {
            path: '/post/:id',
            name: 'View Post',
            component: PostView,
        },
        {
            path: '/login',
            name: 'Login',
            component: LoginView,
            meta: {
                hideNav: true,
            },
        },
        {
            path: '/register',
            name: 'Register',
            component: RegisterView,
            meta: {
                hideNav: true,
            },
        },
        {
            path: '/me',
            name: 'user',
            component: UserView,
        },
        {
            path: '/settings',
            name: 'Settings',
            component: SettingsView,
            children: [
                {
                    path: 'account',
                    name: 'Account Settings',
                    component: Account,
                },
                {
                    path: 'preferences',
                    name: 'Preferences',
                    component: Preferences,
                },
            ],
        },
        {
            path: '/api-handler',
            name: 'Api Handler',
            component: ApiCallbackHandler,
            meta: {
                hideNav: true,
            },
        },
    ],
})

export default router
