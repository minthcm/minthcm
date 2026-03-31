import numericInput from '../inputs/numeric'

export default {
    equal: {
        label: 'LBL_ESLIST_EQUAL',
        inputs: [numericInput],
        filters: [{ op: 'term', value: '{0}' }],
    },
    not_equal: {
        label: 'LBL_ESLIST_NOT_EQUAL',
        not: true,
        inputs: [numericInput],
        filters: [{ op: 'term', value: '{0}' }],
    },
    gte: {
        label: 'LBL_ESLIST_GREATER_OR_EQUAL',
        inputs: [numericInput],
        filters: [{ op: 'range', value: { gte: '{0}' } }],
    },
    lte: {
        label: 'LBL_ESLIST_LESS_OR_EQUAL',
        inputs: [numericInput],
        filters: [{ op: 'range', value: { lte: '{0}' } }],
    },
    gt: {
        label: 'LBL_ESLIST_GREATER_THAN',
        inputs: [numericInput],
        filters: [{ op: 'range', value: { gt: '{0}' } }],
    },
    lt: {
        label: 'LBL_ESLIST_LESS_THAN',
        inputs: [numericInput],
        filters: [{ op: 'range', value: { lt: '{0}' } }],
    },
    between: {
        label: 'LBL_ESLIST_BETWEEN',
        inputs: [
            { ...numericInput, label: 'LBL_ESLIST_VALUE_FROM' },
            { ...numericInput, label: 'LBL_ESLIST_VALUE_TO' },
        ],
        filters: [{ op: 'range', value: { gte: '{0}', lte: '{1}' } }],
    },
}
