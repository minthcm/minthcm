import relateInput from '../inputs/relate'

export default {
    search: {
        label: 'LBL_ESLIST_EQUAL',
        inputs: [
            relateInput,
        ],
        filters: [
            { op: 'match', value: { query: '{0}', operator: 'and' } }
        ]
    },
    search_not: {
        label: 'LBL_ESLIST_NOT_EQUAL',
        not: true,
        inputs: [
            relateInput,
        ],
        filters: [
            { op: 'match', value: { query: '{0}', operator: 'and' } }
        ]
    },
    one_of: {
        label: 'LBL_ESLIST_ONE_OF',
        inputs: [
            { type: 'multirelate', label: 'LBL_ESLIST_VALUE' }
        ],
        filters: [
            { op: 'terms', value: '{0}' }
        ]
    },
    none_of: {
        label: 'LBL_ESLIST_NONE_OF',
        not: true,
        inputs: [
            { type: 'multirelate', label: 'LBL_ESLIST_VALUE' }
        ],
        filters: [
            { op: 'terms', value: '{0}' }
        ]
    }
}
