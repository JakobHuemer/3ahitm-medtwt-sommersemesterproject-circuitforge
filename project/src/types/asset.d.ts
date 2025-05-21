export interface Asset {
    id: number
    post_id: number
    mime_type: string
    file_name: string
    asset_type: AssetType
    downloads: number
    created_at: string
    updated_at: string
}

export type AssetType = 'image' | 'asset'
