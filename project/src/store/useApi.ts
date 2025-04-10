import { defineStore } from 'pinia'
import { reactive, ref, shallowRef } from 'vue'
import axios from 'axios'
import router from '@/router'


export const useApi = defineStore('global', () => {

    const api = axios.create({
        baseURL: import.meta.env.BASE_URL,
        withCredentials: true,
        withXSRFToken: true
    })

    const state = reactive<{
        isAuthenticated: boolean,
        // TODO: change from any to actual User
        user: null | any
        hasSession: boolean
        isLoading: boolean
    }>({
        isAuthenticated: false,
        user: null,
        hasSession: false,
        isLoading: false
    })


    async function createSession() {

        state.hasSession = false
        state.isLoading = true

        try {
            const res = await axios.get('/sanctum/csrf-cookie')

            if ( res.status == 200 ) {
                state.hasSession = true
            }
        } catch ( e ) {
            console.error('Failed to create sessions')
        }
    }


    api.interceptors.response.use(
        async response => {

            if ( !state.hasSession ) {
                await createSession()
            }

            return response
        },
        error => {
            if ( error.response &&
                (error.response.status === 401 || error.response.status === 419) ) {
                router.push({ path: '/login' })
            }
            return Promise.reject(error)
        }
    )

    async function login(login: string, password: string) {
        // TODO: implement

        try {
            await axios.get('/sanctum/csrf-cookie')

            await axios.post('/login', {
                login, password
            })

        } catch ( e ) {
            console.error('Failed to login')
        }


    }

    async function register(username: string, email: string, password: string) {
        // TODO: implement
    }

    async function fetch(url: string) {
        // TODO: implement and extend params
    }


})
