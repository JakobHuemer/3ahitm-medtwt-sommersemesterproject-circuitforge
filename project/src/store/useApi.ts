import { defineStore } from 'pinia'
import { reactive, ref, shallowRef } from 'vue'
import axios from 'axios'
import router from '@/router'
import type User from '@/types/user'

export const useApi = defineStore('api', () => {
    const api = axios.create({
        baseURL: import.meta.env.BASE_URL.replace(/frontend.*/g, 'api/public/'),
        withCredentials: true,
        withXSRFToken: true,
    })

    const state = reactive<{
        isAuthenticated: boolean
        user: null | User
        hasSession: boolean
        isLoading: boolean
    }>({
        isAuthenticated: false,
        user: null,
        hasSession: false,
        isLoading: false,
    })

    async function createSession() {
        state.hasSession = false
        state.isLoading = true

        try {
            const res = await api.get('/sanctum/csrf-cookie')

            if (res.status == 200) {
                state.hasSession = true
            }
        } catch (e) {
            console.error('Failed to create sessions')
        }
    }

    api.interceptors.request.use(
        async (request) => {
            if (request.url == '/sanctum/csrf-cookie') {
                return request
            }

            if (!state.hasSession) {
                await createSession()
            }

            return request
        },
        (error) => {
            if (
                error.response &&
                (error.response.status === 401 || error.response.status === 419)
            ) {
                router.push({ path: '/login' })
            }
            return Promise.reject(error)
        },
    )

    async function login(login: string, password: string, rememberMe: boolean) {
        // TODO: Implement remember me!

        try {
            const res = await api.post<User>('/login', {
                login,
                password,
            })

            state.user = res.data

            router.push('/')
        } catch (e) {
            console.error('Failed to login user')
        }
    }

    async function register(username: string, email: string, password: string) {}

    async function fetch(url: string) {
        // TODO: implement and extend params
    }

    return {
        login,
        register,
        fetch,
        state,
    }
})
