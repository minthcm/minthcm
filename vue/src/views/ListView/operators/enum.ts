import selectInput from '../inputs/select'
import multiSelectInput from '../inputs/multiselect'

export default {
    equal: {
        label: 'LBL_ESLIST_EQUAL',
        inputs: [selectInput],
        filters: [{ op: 'term', value: '{0}' }],
    },
    not_equal: {
        label: 'LBL_ESLIST_NOT_EQUAL',
        not: true,
        inputs: [selectInput],
        filters: [{ op: 'term', value: '{0}' }],
    },
    contain: {
        label: 'LBL_ESLIST_CONTAIN',
        inputs: [multiSelectInput],
        filters: [{ op: 'terms', value: '{0}' }],
    },
    not_contain: {
        label: 'LBL_ESLIST_NOT_CONTAIN',
        not: true,
        inputs: [multiSelectInput],
        filters: [{ op: 'terms', value: '{0}' }],
    },
}
