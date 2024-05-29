export default {
    search: {
        label: 'LBL_ESLIST_EQUAL',
        inputs: [
            { type: 'relate', label: 'LBL_ESLIST_VALUE' }
        ],
        filters: [
            { op: 'match', value: { query: '{0}', operator: 'and' } }
        ]
    },
    search_not: {
        label: 'LBL_ESLIST_NOT_EQUAL',
        not: true,
        inputs: [
            { type: 'relate', label: 'LBL_ESLIST_VALUE' }
        ],
        filters: [
            { op: 'match', value: { query: '{0}', operator: 'and' } }
        ]
    }
}
