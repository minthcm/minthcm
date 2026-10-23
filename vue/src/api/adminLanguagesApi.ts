import { mintApi } from './api'

export interface LanguagePack {
    key: string
    label: string
    enabled: boolean
    default: boolean
    locked: boolean
}

export const getLanguages = () => {
    return mintApi.get<LanguagePack[]>('/admin/languages')
}

export const saveLanguages = (disabled: string[], defaultLanguage: string) => {
    return mintApi.put('/admin/languages', { disabled, default_language: defaultLanguage })
}
