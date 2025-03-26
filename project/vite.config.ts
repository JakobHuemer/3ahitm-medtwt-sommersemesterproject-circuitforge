import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'
import path from 'node:path'

export default defineConfig({
    plugins: [
        vue(),
        vueDevTools()
    ],
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./src', import.meta.url))
        }
    },
    build: {
        outDir: 'frontend'
    },
    base: (() => {
        const projectPath = process.cwd()
        const htdocsIndex = projectPath.split(path.sep).findIndex(segment => segment === 'htdocs')

        if ( htdocsIndex === -1 ) {
            throw new Error('Project must be located in an htdocs directory')
        }

        return projectPath.split(path.sep).slice(htdocsIndex + 1).join('/') + '/frontend/'
    })()

})
