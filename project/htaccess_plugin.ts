import type { Plugin } from 'vite'
import fs from 'fs'
import path from 'path'

export function generateHtaccessPlugin(): Plugin {
    return {
        name: 'generate-htaccess',
        apply: 'build',
        generateBundle(options, bundle) {
            const baseUrl = (() => {
                const projectPath = process.cwd()
                const htdocsIndex = projectPath.split(path.sep).findIndex(segment => segment === 'htdocs')

                if (htdocsIndex === -1) {
                    throw new Error('Project must be located in an htdocs directory')
                }

                return '/' + projectPath.split(path.sep).slice(htdocsIndex + 1).join('/') + '/frontend/'
            })()

            const distDir = options.dir || 'dist'

            // First .htaccess for frontend (HTML)
            const frontendHtaccessPath = path.resolve(distDir, '.htaccess')
            const frontendHtaccessContent = `FallbackResource ${baseUrl}index.html`
            fs.writeFileSync(frontendHtaccessPath, frontendHtaccessContent)

            // Second .htaccess for API (PHP)
            const apiHtaccessPath = path.resolve(distDir, '..', 'api', '.htaccess')
            const apiBaseUrl = baseUrl.replace('/frontend/', '/api/')
            const apiHtaccessContent = `FallbackResource ${apiBaseUrl}public/index.php`

            // Ensure the directory exists before writing
            fs.mkdirSync(path.dirname(apiHtaccessPath), { recursive: true })
            fs.writeFileSync(apiHtaccessPath, apiHtaccessContent)
        }
    }
}
