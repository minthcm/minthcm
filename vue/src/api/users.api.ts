import { mintApi } from './api'

class UsersApi {
    public async isLoginUnique(username: string) {
        return await mintApi.post('Users/unique', {
            username: username,
        })
    }
}

export const usersApi = new UsersApi()
