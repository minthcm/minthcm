import textInput from '../inputs/text'

export default {
    search: {
        label: 'LBL_ESLIST_EQUAL',
        inputs: [textInput],
        filters: [{ op: 'term', value: '{0}', use_keyword_subfield: true }],
    },
    search_not: {
        label: 'LBL_ESLIST_NOT_EQUAL',
        not: true,
        inputs: [textInput],
        filters: [{ op: 'term', value: '{0}', use_keyword_subfield: true }],
    },
    contain_word: {
        label: 'LBL_ESLIST_CONTAIN_WORD',
        inputs: [textInput],
        filters: [{ op: 'match', value: { query: '{0}', operator: 'and' } }],
    },
    not_contain_word: {
        label: 'LBL_ESLIST_NOT_CONTAIN_WORD',
        not: true,
        inputs: [textInput],
        filters: [{ op: 'match', value: { query: '{0}', operator: 'and' } }],
    },
}
