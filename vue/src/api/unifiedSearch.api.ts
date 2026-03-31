import { mintApi } from './api'
interface GlobalSearchParams {
    query: string
    itemsPerPage?: string
    page?: number
}

class UnifiedSearchApi {
    public async globalSearch(params: GlobalSearchParams) {
        return await mintApi.get('/global_search', { params })
    }
}

export const unifiedSearchApi = new UnifiedSearchApi()