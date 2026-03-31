import { mintApi } from './api'

class FavoritesApi {
    public async add(module: string, id: string) {
        return await mintApi.post('/favorites/add', {
            module: module,
            id: id,
        })
    }

    public async remove(module: string, id: string) {
        return await mintApi.post('/favorites/remove', {
            module: module,
            id: id,
        })
    }

    public async getList() {
        return await mintApi.get('/Favorites')
    }
}

export const favoritesApi = new FavoritesApi()
