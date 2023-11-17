export default {
    equal: {
        label: 'LBL_ESLIST_EQUAL',
        inputs: [
            { type: 'numeric', label: 'LBL_ESLIST_VALUE' }
        ],
        filters: [
            { op: 'term', value: '{0}' }
        ]
    },
    not_equal: {
        label: 'LBL_ESLIST_NOT_EQUAL',
        not: true,
        inputs: [
            { type: 'numeric', label: 'LBL_ESLIST_VALUE' }
        ],
        filters: [
            { op: 'term', value: '{0}' }
        ]
    },
    gte: {
        label: 'LBL_ESLIST_GREATER_OR_EQUAL',
        inputs: [
            { type: 'numeric', label: 'LBL_ESLIST_VALUE' }
        ],
        filters: [
            { op: 'range', value: { gte: '{0}' }}
        ]
    },
    lte: {
        label: 'LBL_ESLIST_LESS_OR_EQUAL',
        inputs: [
            { type: 'numeric', label: 'LBL_ESLIST_VALUE' }
        ],
        filters: [
            { op: 'range', value: { lte: '{0}' }}
        ]
    },
    gt: {
        label: 'LBL_ESLIST_GREATER_THAN',
        inputs: [
            { type: 'numeric', label: 'LBL_ESLIST_VALUE' }
        ],
        filters: [
            { op: 'range', value: { gt: '{0}' }}
        ]
    },
    lt: {
        label: 'LBL_ESLIST_LESS_THAN',
        inputs: [
            { type: 'numeric', label: 'LBL_ESLIST_VALUE' }
        ],
        filters: [
            { op: 'range', value: { lt: '{0}' }}
        ]
    },
    between: {
        label: 'LBL_ESLIST_BETWEEN',
        inputs: [
            { type: 'numeric', label: 'LBL_ESLIST_VALUE_FROM' },
            { type: 'numeric', label: 'LBL_ESLIST_VALUE_TO' },
        ],
        filters: [
            { op: 'range', value: { gte: '{0}', lte: '{1}' }}
        ]
    },
}
