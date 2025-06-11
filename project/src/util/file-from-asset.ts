import type { Asset } from '@/types/asset'
import { useApi } from '@/store/useApi.ts'

export default async function (asset: Asset): Promise<File> {
    const res = await useApi().api.get('/assets/' + asset.id, {
        responseType: 'blob',
    })

    return new File([res.data], asset.file_name, {
        type: res.data.type || asset.mime_type,
    })
}
