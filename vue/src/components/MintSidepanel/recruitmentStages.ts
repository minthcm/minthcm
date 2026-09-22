export interface RecruitmentStage {
    key: string
    statuses: string[]
    label: string
}

// Single source of truth for the recruitment-flow order/grouping of Candidatures.status values,
// shared by MintWidgetRecruitmentStage.vue (stepper) and MintWidgetCandidatureStatuses.vue (pie
// chart) so both always agree on stage boundaries and status ordering.
export const RECRUITMENT_STAGES: RecruitmentStage[] = [
    {
        key: 'new',
        statuses: ['New'],
        label: 'LBL_WIDGET_STAGE_NEW',
    },
    {
        key: 'screening',
        statuses: ['Preselection', 'Scored', 'Scored2', 'InProgress'],
        label: 'LBL_WIDGET_STAGE_SCREENING',
    },
    {
        key: 'interviews',
        statuses: ['MeetingPrimary', 'MeetingAdditional', 'PracticalTask', 'EntryInterview', 'AfterEntryInterview'],
        label: 'LBL_WIDGET_STAGE_INTERVIEWS',
    },
    {
        key: 'offer',
        statuses: ['Negotation', 'Acceptance', 'Offer'],
        label: 'LBL_WIDGET_STAGE_OFFER',
    },
    {
        key: 'result',
        statuses: ['Hired', 'CandidateResignation', 'Rejected'],
        label: 'LBL_WIDGET_STAGE_RESULT',
    },
]

export const RECRUITMENT_FLOW_ORDER: string[] = RECRUITMENT_STAGES.flatMap(stage => stage.statuses)
