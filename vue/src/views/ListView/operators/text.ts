export default {
    search: {
        label: 'LBL_ESLIST_EQUAL',
        inputs: [
            { type: 'text', label: 'LBL_ESLIST_TEXT' }
        ],
        filters: [
            { op: 'term', value: '{0}', use_keyword_subfield: true }
        ]
    },
    search_not: {
        label: 'LBL_ESLIST_NOT_EQUAL',
        not: true,
        inputs: [
            { type: 'text', label: 'LBL_ESLIST_TEXT' }
        ],
        filters: [
            { op: 'term', value: '{0}', use_keyword_subfield: true }
        ]
    },
    contain_word: {
        label: 'LBL_ESLIST_CONTAIN_WORD',
        inputs: [
            { type: 'text', label: 'LBL_ESLIST_TEXT' }
        ],
        filters: [
            { op: 'match', value: { query: '{0}', operator: 'and' } }
        ]
    },
    not_contain_word: {
        label: 'LBL_ESLIST_NOT_CONTAIN_WORD',
        not: true,
        inputs: [
            { type: 'text', label: 'LBL_ESLIST_TEXT' }
        ],
        filters: [
            { op: 'match', value: { query: '{0}', operator: 'and' } }
        ]
    }
}
