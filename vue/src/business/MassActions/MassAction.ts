import { mintApi } from '@/api/api'
import { AxiosResponse } from 'axios'

export abstract class MassAction {
    protected module = ''
    protected ids: string[] = []
    protected filters: any

    public constructor(module: string, ids: string[], filters: any = {}) {
        this.module = module
        this.ids = ids
        this.filters = filters
    }

    public abstract execute(): Promise<boolean>

    protected async sendRequest(additional_data = {}): Promise<AxiosResponse> {
        const className = this.constructor.name
        return await mintApi.post(`${this.module}/MassActions/${className}`, {
            ids: this.ids,
            ...additional_data,
            filter: this.filters
        })
    }
}
