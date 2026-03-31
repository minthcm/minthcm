import * as operatorDefs from '../views/ListView/operators'

import { BaseOperator, OperatorsConfig, Filter, filterDef } from './qsOperatorsTypes'

const typedOperatorDefs = operatorDefs as unknown as OperatorsConfig

export default function getQuery(vardefs: any, filterDefs: filterDef[] | [] = []) {
    const query: { must: Filter[]; must_not: Filter[] } = { must: [], must_not: [] }
    filterDefs.forEach((filter) => {
        const type = vardefs[filter.field].type as string
        const operators = (typedOperatorDefs[type as keyof OperatorsConfig] ??
            typedOperatorDefs[typedOperatorDefs.typeMap[type] as keyof OperatorsConfig]) as Record<string, BaseOperator>
        const operator = operators[filter.operator]
        const filterType = operator.not ? 'must_not' : 'must'
        const filterObject: Filter = {
            [operator.filters[0].op]: {
                [filter.field]: replacePlaceholders(operator.filters[0].value, filter.value),
            },
        }
        query[filterType].push(filterObject)
    })
    return query
}

function replacePlaceholders(
    placeholder: string | boolean | Record<string, any>,
    filter: string | string[] | Record<string, any> | undefined,
): string | boolean | any[] | Record<string, any> {
    if (!filter) {
        return placeholder
    }
    if (typeof placeholder === 'string' || typeof placeholder === 'boolean') {
        return replaceValue(placeholder, filter)
    } else if (isObject(placeholder)) {
        const result = { ...placeholder }
        for (const key in result) {
            if (typeof result[key] === 'string' && (result[key].includes('{0}') || result[key].includes('{1}'))) {
                result[key] = isObject(filter) && key in filter ? (filter as Record<string, any>)[key] : filter
            }
        }
        return result
    }
    return placeholder
}

function replaceValue(
    placeholder: string | boolean,
    value: string | string[] | Record<string, any>,
): string | boolean | any[] {
    if (typeof placeholder === 'string') {
        if (Array.isArray(value) && placeholder === '{0}') {
            return value
        }
        return placeholder.replace(/\{0\}/g, value as string)
    }
    return placeholder
}

function isObject(value: any): value is Record<string, any> {
    return value && typeof value === 'object' && value.constructor === Object
}
