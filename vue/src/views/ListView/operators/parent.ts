import parentInput from '../inputs/parent'

export default {
    search: {
        label: 'LBL_ESLIST_EQUAL',
        inputs: [...Object.values(parentInput)],
        filters: [{ op: 'match', value: { query: '{1}', operator: 'and' } }],
    },
    search_not: {
        label: 'LBL_ESLIST_NOT_EQUAL',
        not: true,
        inputs: [...Object.values(parentInput)],
        filters: [{ op: 'match', value: { query: '{1}', operator: 'and' } }],
    }
}
