import axios, { AxiosInstance, AxiosResponse } from 'axios'
import { responseHandler } from './interceptors/response-handler'
import { responseErrorHandler } from './interceptors/response-error-handler'
declare module 'axios' {
    export interface AxiosRequestConfig {
        rawError?: boolean
        skipRefreshToken?: boolean
    }
}

function createMintApi() {
    const instance = axios.create({
        baseURL: 'api/',
        headers: {
            'Content-Type': 'application/json',
        },
    })

    instance.interceptors.response.use(
        responseHandler,
        async (error: any) => await responseErrorHandler(error)
    )

    return instance
}

export const mintApi: AxiosInstance = createMintApi()
