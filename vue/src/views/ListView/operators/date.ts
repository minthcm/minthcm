export default {
    equal: {
        label: 'LBL_ESLIST_EQUAL',
        inputs: [
            { type: 'date', label: 'LBL_ESLIST_DATE' }
        ],
        filters: [
            { op: 'range', value: { gte: '{0}', lte: '{0}' } }
        ]
    },
    not_equal: {
        label: 'LBL_ESLIST_NOT_EQUAL',
        not: true,
        inputs: [{ type: 'date', label: 'LBL_ESLIST_DATE' }],
        filters: [
            { op: 'range', value: { gte: '{0}', lte: '{0}' } }
        ]
    },

    previous_week: {
        label: 'LBL_ESLIST_PREVIOUS_WEEK',
        filters: [
            { op: 'range', value: { gte: 'now-1w/w', lt: 'now/w' }},
        ]
    },
    current_week: {
        label: 'LBL_ESLIST_CURRENT_WEEK',
        filters: [
            { op: 'range', value: { gte: 'now/w', lte: 'now/w' }},
        ],
    },
    next_week: {
        label: 'LBL_ESLIST_NEXT_WEEK',
        filters: [
            { op: 'range', value: { gt: 'now/w', lte: 'now+1w/w' }}
        ]
    },

    previous_month: {
        label: 'LBL_ESLIST_PREVIOUS_MONTH',
        filters: [
            { op: 'range', value: { gte: 'now-1M/M', lt: 'now/M' }},
        ]
    },
    current_month: {
        label: 'LBL_ESLIST_CURRENT_MONTH',
        filters: [
            { op: 'range', value: { gte: 'now/M', lte: 'now/M' }},
        ],
    },
    next_month: {
        label: 'LBL_ESLIST_NEXT_MONTH',
        filters: [
            { op: 'range', value: { gt: 'now/M', lte: 'now+1M/M' }}
        ]
    },
    previous_year: {
        label: 'LBL_ESLIST_PREVIOUS_YEAR',
        filters: [
            { op: 'range', value: { gte: 'now-1y/y', lt: 'now/y' }},
        ]
    },
    current_year: {
        label: 'LBL_ESLIST_CURRENT_YEAR',
        filters: [
            { op: 'range', value: { gte: 'now/y', lte: 'now/y' }},
        ],
    },
    next_year: {
        label: 'LBL_ESLIST_NEXT_YEAR',
        filters: [
            { op: 'range', value: { gt: 'now/y', lte: 'now+1y/y' }}
        ]
    },
    last_7_days: {
        label: 'LBL_ESLIST_LAST_7_DAYS',
        filters: [
            { op: 'range', value: { gte: 'now-7d/d', lte: 'now/d' }}
        ]
    },
    next_7_days: {
        label: 'LBL_ESLIST_NEXT_7_DAYS',
        filters: [
            { op: 'range', value: { gte: 'now/d', lte: 'now+7d/d' }}
        ]
    },
    last_30_days: {
        label: 'LBL_ESLIST_LAST_30_DAYS',
        filters: [
            { op: 'range', value: { gte: 'now-30d/d', lte: 'now/d' }}
        ]
    },
    next_30_days: {
        label: 'LBL_ESLIST_NEXT_30_DAYS',
        filters: [
            { op: 'range', value: { gte: 'now/d', lte: 'now+30d/d' }}
        ]
    },
    above_n_month_ago: {
        label: 'LBL_ESLIST_ABOVE_N_MONTHS_AGO',
        inputs: [
            { type: 'numeric', label: 'LBL_ESLIST_N_MONTHS' }
        ],
        filters: [
            { op: 'range', value: { lt: 'now-{0}M/M' }}
        ]
    },
    in_the_past: {
        label: 'LBL_ESLIST_IN_THE_PAST',
        filters: [
            { op: 'range', value: { lt: 'now' }},
        ],
    },
    after: {
        label: 'LBL_ESLIST_AFTER',
        inputs: [
            { type: 'date',label: 'LBL_ESLIST_DATE' }
        ],
        filters: [
            { op: 'range', value: { gte: '{0}' }}
        ]
    },
    before: {
        label: 'LBL_ESLIST_BEFORE',
        inputs: [
            { type: 'date',label: 'LBL_ESLIST_DATE' }
        ],
        filters: [
            { op: 'range', value: { lte: '{0}' }}
        ]
    },
    between: {
        label: 'LBL_ESLIST_BETWEEN',
        inputs: [
            { type: 'date', label: 'LBL_ESLIST_FROM' },
            { type: 'date', label: 'LBL_ESLIST_TO' }
        ],
        filters: [
            { op: 'range', value: { gte: '{0}', lte: '{1}' }}
        ]
    },
}
