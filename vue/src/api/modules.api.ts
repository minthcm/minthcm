import { mintApi } from './api' 

class ModulesApi {
    public async getListInit(module_name: string) {
        return await mintApi.get(`${module_name}`, { rawError: true })
    }

    public async getListData(
        module_name: string,
        searchPhrase = '',
        filters = {},
        page = 0,
        itemsPerPage = 100,
        myObjects = false,
        sortBy: string | null = null,
        sortOrder = 'asc',
        activeFilter = null,
        onlyFavorites = false,
    ) {
        return await mintApi.post(module_name, {
            page: page,
            items: itemsPerPage,
            myObjects: myObjects,
            searchPhrase: searchPhrase,
            filters: filters,
            sortBy: sortBy,
            sortOrder: sortOrder,
            activeFilter: activeFilter,
            onlyFavorites: onlyFavorites,
        }, { rawError: true })
    }

    public async forgetPassword(username: string, email: string) {
        return await mintApi.post('forget_password', {
            data: {
                username,
                email,
            },
        })
    }

    public async saveListPreferences(module_name: string, preferences: any) {
        return await mintApi.post(module_name + '/list/preferences', {
            preferences: preferences,
        })
    }

    public async getChecklistItems(module: string, recordId: string) {
        return await mintApi.get(`${module}/checklist/${recordId}`)
    }
}

export const modulesApi = new ModulesApi()
