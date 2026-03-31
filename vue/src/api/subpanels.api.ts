import { mintApi } from './api'

class SubpanelsApi {
    public async fetchSubpanelsData(module: string | string[], subpanelKey: string, recordId: string | string[], paginateBy: number = -1, page: number = 0, sortBy: string = '', sortOrder: string = '') {
        return await mintApi.post(`${module}/subpanel/${subpanelKey}/${recordId}`, {
            sortBy: sortBy,
            sortOrder: sortOrder
        }, {
            validateStatus: () => true,
            params: {
                paginate_by: paginateBy,
                page: page || 0,
            }
        });
    }
}

export const subpanelsApi = new SubpanelsApi()
