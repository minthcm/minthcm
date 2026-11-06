import { mintApi } from './api'

class SubpanelsApi {
    public async fetchSubpanelsData(module: string | string[], subpanelKey: string, recordId: string | string[], paginateBy = -1, page = 0, sortBy = '', sortOrder = '', signal?: AbortSignal) {
        return await mintApi.post(`${module}/subpanel/${subpanelKey}/${recordId}`, {
            sortBy: sortBy,
            sortOrder: sortOrder
        }, {
            validateStatus: () => true,
            params: {
                paginate_by: paginateBy,
                page: page || 0,
            },
            signal,
        });
    }
}

export const subpanelsApi = new SubpanelsApi()
