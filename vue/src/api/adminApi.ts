import { mintApi } from './api'

export const getAdminConfig = (viewName: string) => {
    return mintApi.get<Record<string, any>>(`/admin/config/${viewName}`)
}

export const saveAdminConfig = (viewName: string, fields: Record<string, any>) => {
    return mintApi.put(`/admin/config/${viewName}`, { fields })
}
