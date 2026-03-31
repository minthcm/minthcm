import { FieldVardef } from '@/store/modules'
import { MintField } from './useField'

export interface FieldProps<T = any> {
    view: 'edit' | 'detail' | 'list'
    defs: FieldVardef
    label: string
    field: MintField<T>
    modelValue?: any
    data?: any
    options?: any
    state?: FieldState
    required?: boolean
    error?: boolean
    errorMessage?: string
    disabled?: boolean
    hidePencil?: boolean
    isDirty?: boolean
    minuteStep?: number
}

export type FieldState = 'normal' | 'error' | 'required'

export type FieldValidator = (value: any) => string | void

export type FieldFormatter<T> = (value: T, defs: FieldVardef) => string | number | boolean | string[]

export interface FieldInterface {
    validator?: FieldValidator
    toAppFormat?: (value: any) => any
    userFormatter?: FieldFormatter<any>
    serverFormatter?: FieldFormatter<any>
}
