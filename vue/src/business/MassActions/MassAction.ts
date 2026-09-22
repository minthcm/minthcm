import { mintApi } from '@/api/api'
import { AxiosRequestConfig, AxiosResponse } from 'axios'

export abstract class MassAction {
    // Must equal the backend PHP class name (e.g. 'MassAcceptance').
    // Production builds minify class names, so `this.constructor.name` cannot be used for routing.
    protected static readonly actionName: string

    protected module = ''
    protected ids: string[] = []
    protected filters: any

    public constructor(module: string, ids: string[], filters: any = {}) {
        this.module = module
        this.ids = ids
        this.filters = filters
    }

    public abstract execute(): Promise<boolean>

    protected async sendRequest(additional_data = {}, axiosConfig: AxiosRequestConfig = {}): Promise<AxiosResponse> {
        const actionName = (this.constructor as typeof MassAction).actionName
        return await mintApi.post(`${this.module}/MassActions/${actionName}`, {
            ids: this.ids,
            ...additional_data,
            filter: this.filters
        })
    }
}
