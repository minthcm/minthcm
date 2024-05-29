export { default as date } from './date'
export { default as bool } from './bool'
export { default as enum } from './enum'
export { default as multienum } from './multienum'
export { default as text } from './text'
export { default as numeric } from './numeric'
export { default as relate } from './relate'

export const defaultOperator = 'text'

export const typeMap = {
    datetime: 'date',
    datetimecombo: 'date',
    boolean: 'bool',
    int: 'numeric',
    float: 'numeric',
    decimal: 'numeric',
    currency: 'numeric',
    ColoredActivityStatus: 'enum',
    ColoredEnum: 'enum',
}

export function getAllTypesMatchingTo(baseType: string) {
    const matchingTypes = Object.entries(typeMap)
        .filter(([_, type]) => type === baseType)
        .map(([type, _]) => type)

    return [...matchingTypes, baseType]
}
