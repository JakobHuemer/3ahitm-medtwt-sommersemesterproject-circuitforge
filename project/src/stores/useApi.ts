import { defineStore } from 'pinia'
import { reactive, ref, shallowRef } from 'vue'
import axios from 'axios'
import router from '@/router'


export const useApi = defineStore('auth', () => {

    const api = axios.create({
        baseURL: import.meta.env.baseURL,
        withCredentials: true,
        withXSRFToken: true
    })

    const state = reactive<{
        isAuthenticated: boolean,
        // TODO: change from any to actual User
        user: null | any
    }>({
        isAuthenticated: false,
        user: null
    })

    api.interceptors.response.use(
        response => response,
        error => {
            if ( error.response &&
                (error.response.status === 401 || error.response.status === 419) ) {
                router.push({ path: '/login' })
            }
            return Promise.reject(error)
        }
    )

    function login(login: string, password: string) {
        // TODO: implement


    }

    function register(username: string, email: string, password: string) {
        // TODO: implement
    }

    function fetch(url: string) {
        // TODO: implement and extend params
    }


})
