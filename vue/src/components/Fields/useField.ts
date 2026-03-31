import { FieldVardef } from '@/store/modules'
import { computed, ref, UnwrapRef, watch } from 'vue'
import { fieldConfig } from './Field.config'
import { FieldInterface, FieldValidator } from './Field.model'

export interface FieldOptions {
    module?: string
}

export type MintField<T> = UnwrapRef<ReturnType<typeof useField<T>>>

export const useField = <T>(defs: FieldVardef, initialValue: T, opts: FieldOptions = {}) => {
    const resolvedFieldType = fieldConfig.typeMap[defs.type] || defs.type
    const fieldInterface: FieldInterface = fieldConfig.fields[resolvedFieldType] || {}

    const model = ref<T>(fieldInterface.toAppFormat ? fieldInterface.toAppFormat(initialValue) : initialValue)

    const isDirty = ref(false)

    const formatted = computed(() => {
        return {
            user: fieldInterface.userFormatter ? fieldInterface.userFormatter(model.value, defs) : model.value,
            server: fieldInterface.serverFormatter ? fieldInterface.serverFormatter(model.value, defs) : model.value,
        }
    })

    function validate(): ReturnType<FieldValidator> {
        if (typeof fieldInterface.validator === 'function') {
            return fieldInterface.validator(model.value)
        }
    }

    watch(
        model,
        () => {
            isDirty.value = true
        },
        { deep: true, once: true },
    )

    return {
        initialValue,
        model,
        isDirty,
        validate,
        formatted,
    }
}
