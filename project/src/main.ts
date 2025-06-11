import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { Skeleton } from '@brayamvalero/vue3-skeleton'
import App from './App.vue'
import router from './router'

const pinia = createPinia()
const app = createApp(App)

app.use(router)
app.use(pinia)
// @ts-ignore
app.use(Skeleton)

app.mount('#app')
