import { defineStore } from 'pinia'
import { reactive, ref, shallowRef, watch } from 'vue'
import axios from 'axios'
import router from '@/router'
import type User from '@/types/user'
import { useForm } from 'laravel-precognition-vue'

const LS_USER_KEY = 'circuitforge-user'

const BACKEND_URL = import.meta.env.BASE_URL.replace(/frontend.*/g, 'api/public/')

export const useApi = defineStore('api', () => {
    const api = shallowRef(
        axios.create({
            // baseURL: BACKEND_URL,
            baseURL:
                'http://localhost:8080/2425-sommerprojekt-3ahitm-JakobHuemer/project/api/public/',
            withCredentials: true,
            withXSRFToken: true,
        }),
    )

    // fetch from localstorage

    const localUserString = sessionStorage.getItem(LS_USER_KEY)
    let localUser: User | null = null

    if (localUserString) {
        localUser = JSON.parse(localUserString)
    }

    // Setup

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
        sessionStorage.setItem(LS_USER_KEY, JSON.stringify(newState.user))
    })

    const runLater: Function[] = []

    // interceptor to always have a session if there is none
    api.value.interceptors.request.use(
        async (request) => {
            if (request.url == '/sanctum/csrf-cookie' || state.hasSession) {
                return request
            }

            await refreshSession()

            return request
        },
        (error) => {
            // TODO: display to the user, that the api is unavailable
            if (
                error.response &&
                (error.response.status === 401 || error.response.status === 419)
            ) {
                router.push({ path: '/login' })
            }
            return Promise.reject(error)
        },
    )

    async function refreshSession() {
        state.hasSession = false

        try {
            const res = await api.value.get('/sanctum/csrf-cookie')

            state.hasSession = true
        } catch (e) {
            console.error('Failed to create sessions')
        }
    }

    async function login(
        login: string,
        password: string,
        remember: boolean = false,
    ): Promise<boolean> {
        try {
            const res = await api.value.post<User>('/login', {
                login,
                password,
                remember,
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

    function useRegisterForm() {
        console.info('USING REGISTER FORM')
        return useForm(
            'post',
            BACKEND_URL + 'register',
            {
                username: '',
                email: '',
                password: '',
                password_confirmation: '',
                remember: '',
            },
            {
                adapter: api.value.defaults.adapter,
            },
        )
    }

    return {
        login,
        logout,
        register,
        api,
        state,
        runWhenFinished,
        useRegisterForm,
    }
})
