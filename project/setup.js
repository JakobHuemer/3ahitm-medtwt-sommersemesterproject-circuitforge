import path from 'path'
import fs from 'fs'

export function setup() {

    const baseUrl = (() => {
        const projectPath = process.cwd()
        const htdocsIndex = projectPath.split(path.sep).findIndex(segment => segment === 'htdocs')

        if ( htdocsIndex === -1 ) {
            throw new Error('Project must be located in an htdocs directory')
        }

        return '/' + projectPath.split(path.sep).slice(htdocsIndex + 1).join('/')
    })()

    const frontendDir = baseUrl + "/frontend/"
    const backendDir = baseUrl + "/api/"
    const backendPublicDir = baseUrl + "/api/public/"

    const basePath = path.dirname(new URL(import.meta.url).pathname)

    const frontendPath = path.resolve(basePath, 'frontend')
    const backendPath = path.resolve(basePath, 'api')
    const backendPublicPath = path.resolve(basePath, 'api/public')

    // First .htaccess for frontend (HTML)
    const frontendHtaccessPath = path.resolve(frontendPath, '.htaccess')
    const frontendHtaccessContent = `FallbackResource ${ frontendDir }index.html`
    fs.writeFileSync(frontendHtaccessPath, frontendHtaccessContent)

    // Second .htaccess for API (PHP)
    const apiHtaccessPath = path.resolve(backendPublicPath,'.htaccess')
    const apiHtaccessContent = `FallbackResource ${ backendPublicDir }index.php`

    // .env.generated file in api/ dir
    const envPath = path.resolve(backendPath, '.env.generated')
    const envContent = `FRONTEND_URL=${ frontendDir }\nAPI_URL=${ backendPublicDir }`
    fs.writeFileSync(envPath, envContent)

    // Ensure the directory exists before writing
    fs.mkdirSync(path.dirname(apiHtaccessPath), { recursive: true })
    fs.writeFileSync(apiHtaccessPath, apiHtaccessContent)
}

setup()
