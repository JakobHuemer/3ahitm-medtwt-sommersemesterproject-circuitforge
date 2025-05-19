import type { Plugin } from 'vite'

import { setup } from './setup'

export function generateHtaccessPlugin(): Plugin {
    return {
        name: 'generate-htaccess',
        apply: 'build',
        generateBundle(options, bundle) {
            setup()
        },
    }
}
