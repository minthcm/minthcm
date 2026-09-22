export interface VisibilityCondition {
    field: string
    operator: '=' | '!=' | 'in' | 'not_in' | 'empty' | 'not_empty'
    value?: unknown
}

export interface CustomVisibilityConfig {
    operator?: 'AND' | 'OR'
    conditions: VisibilityCondition[]
}

export function evaluateCustomVisibility(
    attributes: Record<string, unknown>,
    config?: CustomVisibilityConfig,
): boolean {
    if (!config?.conditions?.length) return true
    const results = config.conditions.map((condition) => evaluateCondition(attributes[condition.field], condition))
    return config.operator === 'OR' ? results.some(Boolean) : results.every(Boolean)
}

function evaluateCondition(value: unknown, condition: VisibilityCondition): boolean {
    switch (condition.operator) {
        case '=':
            return value === condition.value
        case '!=':
            return value !== condition.value
        case 'in':
            return Array.isArray(condition.value) && condition.value.includes(value)
        case 'not_in':
            return Array.isArray(condition.value) && !condition.value.includes(value)
        case 'empty':
            return value === undefined || value === null || value === ''
        case 'not_empty':
            return !(value === undefined || value === null || value === '')
        default:
            return true
    }
}
