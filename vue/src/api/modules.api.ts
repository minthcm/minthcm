import { MintApi } from './api'

class ModulesApi extends MintApi {
    public async getListInit(module_name: string) {
        return await this.instance.get(module_name)
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
    ) {
        return await this.instance.post(module_name, {
            page: page,
            items: itemsPerPage,
            myObjects: myObjects,
            searchPhrase: searchPhrase,
            filters: filters,
            sortBy: sortBy,
            sortOrder: sortOrder,
            activeFilter: activeFilter,
        })
    }

    public async forgetPassword(username: string, email: string) {
        return await this.instance.post('api/forget_password', {
            data: {
                username,
                email,
            },
        })
    }

    public async saveListPreferences(module_name: string, preferences: any) {
        return await this.instance.post(module_name + '/list/preferences', {
            preferences: preferences,
        })
    }
}

export const modulesApi = new ModulesApi()
