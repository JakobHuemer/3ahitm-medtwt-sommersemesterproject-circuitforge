import { defineStore } from 'pinia'
import { reactive, ref, shallowRef, watch } from 'vue'
import axios from 'axios'
import router from '@/router'
import type User from '@/types/user'

const LS_USER_KEY = 'circuitforge-user'

export const useApi = defineStore('api', () => {
    const api = shallowRef(
        axios.create({
            baseURL: import.meta.env.BASE_URL.replace(/frontend.*/g, 'api/public/'),
            // baseURL:
            //     'http://localhost:8080/2425-sommerprojekt-3ahitm-JakobHuemer/project/api/public/',
            withCredentials: true,
            withXSRFToken: true,
        }),
    )

    // fetch from localstorage

    const localUserString = localStorage.getItem(LS_USER_KEY)
    let localUser: User | null = null

    if (localUserString) {
        localUser = JSON.parse(localUserString)
    }

    api.value
        .get<User>('/me')
        .then((r) => {
            state.user = r.data
            state.isAuthenticated = true
        })
        .catch((e) => {
            state.isAuthenticated = false
        })
        .finally(() => {
            runLater.forEach((cb) => cb())
        })

    const state = reactive<{
        isAuthenticated: boolean
        user: null | User
        hasSession: boolean
    }>({
        isAuthenticated: !!localUser,
        user: localUser,
        hasSession: false,
    })

    watch(state, (newState) => {
        localStorage.setItem(LS_USER_KEY, JSON.stringify(newState.user))
    })

    const runLater: Function[] = []

    // interceptor to always have a session if there is none
    api.value.interceptors.request.use(
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

    async function createSession() {
        state.hasSession = false

        try {
            const res = await api.value.get('/sanctum/csrf-cookie')

            if (res.status == 200) {
                state.hasSession = true
            }
        } catch (e) {
            console.error('Failed to create sessions')
        }
    }

    async function login(login: string, password: string, rememberMe: boolean): Promise<boolean> {
        // TODO: Implement remember me!

        try {
            const res = await api.value.post<User>('/login', {
                login,
                password,
            })

            state.user = res.data
            state.isAuthenticated = true

            router.push('/')
            return true
        } catch (e) {
            state.isAuthenticated = false
            return false
        }
    }

    async function register(username: string, email: string, password: string): Promise<boolean> {
        try {
            const res = await api.value.post<User>('/register', {
                username,
                email,
                password,
            })

            state.user = res.data
            state.isAuthenticated = true

            router.push('/')
            return true
        } catch (e) {
            console.error('Failed to register user: <' + username + ',' + email + '>')
            state.isAuthenticated = false
            return false
        }
    }

    function logout() {
        try {
            api.value.post('/logout')

            state.isAuthenticated = false
            state.user = null
        } catch (e) {
            console.error('Failed to logout user:', state.user)
        }
    }

    function runWhenFinished(callback: Function) {
        runLater.push(callback)
    }

    return {
        login,
        logout,
        register,
        api,
        state,
        runWhenFinished,
    }
})
