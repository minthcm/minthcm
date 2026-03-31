export interface Participant {
    id: string
    name: string
    module: string
    link: string
    meta: ParticipantMeta
    workschedules: DataWorkschedule[] | null
    activities: DataActivity[] | null
}

export interface ParticipantMeta {
    photo?: string
    description?: string
}

export interface DataWorkschedule extends DataPeriod {
    id: string
    type: string
}

export interface DataActivity extends DataPeriod {
    id: string
    name: string
    module: string
    overflowIndex?: number
}

export interface DataPeriod {
    date_start: string
    date_end: string
}
