import { mintApi } from './api'

class CandidaturesApi {
    public async convert(userType: string, candidatureId: string, userName: string | null) { // CR: camelCase userType/CandidatureId
        return await mintApi.post('Candidatures/convert', {
            usertype: userType,
            candidature_id: candidatureId,
            username: userName,
        })
    }
}

export const candidaturesApi = new CandidaturesApi()
