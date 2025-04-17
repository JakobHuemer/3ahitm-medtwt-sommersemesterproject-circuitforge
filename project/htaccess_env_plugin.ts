import type { Plugin } from 'vite'
import fs from 'fs'
import path from 'path'

import { setup } from './setup'

export function generateHtaccessPlugin(): Plugin {
    return {
        name: 'generate-htaccess',
        apply: 'build',
        generateBundle(options, bundle) {
            setup()
        }
    }
}
