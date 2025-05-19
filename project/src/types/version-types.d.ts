export type VersionType = 'release' | 'snapshot' | 'old_alpha' | 'old_beta'

export const versionOptions: VersionType[] = ['release', 'snapshot', 'old_alpha', 'old_beta']

export interface Version {
    version: string
    type: VersionType
    released: Date
    is_latest: boolean
}
