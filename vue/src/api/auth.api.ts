import { MintApi } from './api'

class AuthApi extends MintApi {
    public async login(username: string, password: string) {
        return await this.instance.post('/auth/login', {
            data: {
                username,
                password,
            },
        })
    }

    public async logout() {
        return await this.instance.post('/auth/logout')
    }

    public async forgetPassword(username: string, email: string) {
        return await this.instance.post('api/forget_password', {
            data: {
                username,
                email,
            },
        })
    }
}

export const authApi = new AuthApi()
