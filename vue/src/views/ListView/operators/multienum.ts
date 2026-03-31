import selectInput from '../inputs/select'

export default {
    contain: {
        label: 'LBL_ESLIST_CONTAIN',
        inputs: [{ ...selectInput, label: 'LBL_ESLIST_VALUES' }],
        filters: [{ op: 'wildcard', value: '*^{0}^*' }],
    },
    not_contain: {
        label: 'LBL_ESLIST_NOT_CONTAIN',
        not: true,
        inputs: [{ ...selectInput, label: 'LBL_ESLIST_VALUES' }],
        filters: [{ op: 'wildcard', value: '*^{0}^*' }],
    },
}
