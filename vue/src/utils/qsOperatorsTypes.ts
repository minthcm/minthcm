export type Operator = 'term' | 'range' | 'terms' | 'wildcard' | 'match'

export interface BaseOperatorFilter {
    op: Operator
    value: any
    use_keyword_subfield?: boolean
}

export interface BaseOperatorInput {
    type: 'date' | 'numeric' | 'select' | 'multiselect' | 'relate' | 'text'
    label: string
}

export interface BaseOperator {
    label: string
    not?: boolean
    inputs?: BaseOperatorInput[]
    filters: BaseOperatorFilter[]
}

export type BoolOperators = {
    yes: BaseOperator
    no: BaseOperator
}

export type DateOperators = {
    equal: BaseOperator
    not_equal: BaseOperator
    previous_week: BaseOperator
    current_week: BaseOperator
    next_week: BaseOperator
    previous_month: BaseOperator
    current_month: BaseOperator
    next_month: BaseOperator
    previous_year: BaseOperator
    current_year: BaseOperator
    next_year: BaseOperator
    last_7_days: BaseOperator
    next_7_days: BaseOperator
    last_30_days: BaseOperator
    next_30_days: BaseOperator
    above_n_month_ago: BaseOperator
    in_the_past: BaseOperator
    in_the_future: BaseOperator
    after: BaseOperator
    before: BaseOperator
    between: BaseOperator
}

export type EnumOperators = {
    equal: BaseOperator
    not_equal: BaseOperator
    contain: BaseOperator
    not_contain: BaseOperator
}

export type MultiEnumOperators = {
    contain: BaseOperator
    not_contain: BaseOperator
}

export type NumericOperators = {
    equal: BaseOperator
    not_equal: BaseOperator
    gte: BaseOperator
    lte: BaseOperator
    gt: BaseOperator
    lt: BaseOperator
    between: BaseOperator
}

export type RelateOperators = {
    search: BaseOperator
    search_not: BaseOperator
}

export type TextOperators = {
    search: BaseOperator
    search_not: BaseOperator
    contain_word: BaseOperator
    not_contain_word: BaseOperator
}

export type TypeMap = {
    [key: string]: string
}

export interface OperatorsConfig {
    bool: BoolOperators
    date: DateOperators
    enum: EnumOperators
    multienum: MultiEnumOperators
    numeric: NumericOperators
    relate: RelateOperators
    text: TextOperators
    defaultOperator: string
    typeMap: TypeMap
}

export interface Filter {
    [key: string]: {
        [field: string]: string | number | boolean | any[] | Record<string, any>
    }
}

export interface Input {
    type: string
    label: string
    value: string
    modifiers?: string | string[]
}
export interface filterDef {
    field: string
    operator: string
    value?: string
    editable?: boolean
    inputs: []
}