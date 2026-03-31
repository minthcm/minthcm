export { default as date } from './date'
export { default as text } from './text'
export { default as numeric } from './numeric'
export { default as select } from './select'
export { default as multiselect } from './multiselect'
export { default as relate } from './relate'
export { default as bool } from './bool'
export { default as parent } from './parent'
export { default as datetime } from './datetime'

export const defaultInput = 'text'

export const typeMap = {
    datetime: 'datetime',
    datetimecombo: 'datetime',
    boolean: 'bool',
    int: 'numeric',
    float: 'numeric',
    decimal: 'numeric',
    currency: 'numeric',
    ColoredActivityStatus: 'select',
    ColoredEnum: 'select',
    name: 'text',
    multienum: 'multiselect',
    enum: 'select',
}