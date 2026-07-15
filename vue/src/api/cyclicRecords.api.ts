import { mintApi } from './api' 

export interface CyclicRecordDatePair {
    date_start: string
    date_end: string
}

export interface CanEditRepeatResponse {
    canEdit: boolean
    isCyclicRecord: boolean
    hasCyclicRecords: boolean
}

class CyclicRecordsApi {
    public async canEditRepeat(module: string, id: string) {
        return await mintApi.get<CanEditRepeatResponse>(`/CanBeRepeated/${module}/${id}`)
    }

    /**
     * Retrieve the IDs of all cyclic children of a parent record without
     * making any changes — used to plan a bulk update.
     */
    public async planCyclicRecordsUpdate(module: string, id: string) {
        return await mintApi.get<{ ids: string[]; total: number }>(
            `/CyclicRecords/${module}/${id}/plan-update`,
        )
    }

    /**
     * Propagate the current state of the parent record onto a batch of
     * cyclic children identified by their IDs.
     */
    public async updateCyclicRecordsBatch(module: string, id: string, ids: string[]) {
        return await mintApi.post<{ updated: number }>(`/CyclicRecords/${module}/${id}/batch-update`, {
            ids,
        })
    }

    /**
     * Retrieve the full list of cyclic record date pairs that would be created
     * for a given parent record, without actually saving anything.
     * Also returns related_ids so they can be forwarded to each batch request
     * to avoid re-fetching relationship data on every POST.
     */
    public async planCyclicRecords(module: string, id: string) {
        return await mintApi.get<{
            records: CyclicRecordDatePair[]
            total: number
            related_ids: Record<string, string[]>
        }>(
            `/CyclicRecords/${module}/${id}/plan`,
        )
    }

    /**
     * Create a batch of cyclic records from pre-calculated date pairs.
     * Returns the number of records actually created.
     * Pass related_ids (from planCyclicRecords) to avoid repeated DB lookups.
     */
    public async createCyclicRecordsBatch(
        module: string,
        id: string,
        records: CyclicRecordDatePair[],
        relatedIds: Record<string, string[]> = {},
    ) {
        return await mintApi.post<{ created: number }>(`/CyclicRecords/${module}/${id}/batch`, {
            records,
            related_ids: relatedIds,
        })
    }
}

export const cyclicRecordsApi = new CyclicRecordsApi()
