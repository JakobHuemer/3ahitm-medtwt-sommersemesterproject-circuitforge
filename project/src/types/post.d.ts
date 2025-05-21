import type { Version } from '@/types/version-types'
import type { Asset } from '@/types/asset'
import type User from '@/types/user'

export interface Post {
    id: number
    title: string
    content: any
    created_at: string
    updated_at: string
    rating: number
    versions: Version[]
    assets: Asset[]
    author: User
}
