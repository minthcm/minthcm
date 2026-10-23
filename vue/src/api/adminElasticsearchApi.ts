import { mintApi } from './api'

export interface EsCredentials {
    host: string
    user?: string
    pass?: string
}

export interface EsConnectionResult {
    status: 'success' | 'fail'
    ping?: number
    info?: any
    error?: string
    errorType?: string
}

export interface EsModule {
    module: string
    label: string
    enabled: boolean
    total: number
    unindexed: number
    has_es_list_view: boolean
    has_list_view: boolean
}

export interface ModuleTogglePayload {
    enabled: boolean
    enable_legacy_view?: boolean
}

export interface ModuleToggleResult {
    status: string
    module: string
    enabled: boolean
    indexed: number | null
    indexError: string | null
}
export const testElasticsearchConnection = (data: EsCredentials) => {
    return mintApi.post<EsConnectionResult>('/admin/elasticsearch/test-connection', data)
}

export const scheduleElasticsearchIndex = () => {
    return mintApi.post('/admin/elasticsearch/schedule-index')
}

export const getElasticsearchModules = () => {
    return mintApi.get<EsModule[]>('/admin/elasticsearch/modules')
}

export const indexElasticsearchModule = (module: string) => {
    return mintApi.post<{ indexed: number }>(`/admin/elasticsearch/index-module/${module}`)
}

export const toggleElasticsearchModule = (module: string, data: ModuleTogglePayload) => {
    return mintApi.put<ModuleToggleResult>(`/admin/elasticsearch/module-toggle/${module}`, data)
}
