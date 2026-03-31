import { mintApi } from './api'

class PositionsApi {
    public async getCompetencies(module: string, record_id: string) {
        return await mintApi.get(`positionsPanel/competencies/${module}/${record_id}`)
    }

    public async getResponsibilities(module: string, record_id: string) {
        return await mintApi.get(`positionsPanel/responsibilities/${module}/${record_id}`)
    }
}

export const positionsApi = new PositionsApi()
