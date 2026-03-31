import { mintApi } from './api' 

class CyclicRecordsApi {
    public async canEditRepeat(module: string, id: string) {
        return await mintApi.get(`/CanBeRepeated/${module}/${id}`)
    }
}

export const cyclicRecordsApi = new CyclicRecordsApi()
