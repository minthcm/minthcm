import axios, { AxiosInstance } from 'axios'
import { useRouter } from 'vue-router'

export abstract class MintApi {
    protected instance: AxiosInstance

    constructor() {
        this.instance = axios.create({
            baseURL: '/api',
            headers: {
                'Content-Type': 'application/json',
            },
        })

        this.instance.interceptors.response.use(
            (response) => response,
            (error) => {
                if (error.response.status === 401) {
                    this.redirectToLogin()
                }
            },
        )
    }

    protected async redirectToLogin() {
        const router = useRouter()
        await router.push({
            name: 'auth-login',
        })
        router.go(0) //refresh
    }
}
