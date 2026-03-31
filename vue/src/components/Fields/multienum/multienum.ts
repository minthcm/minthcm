import { useLanguagesStore } from '@/store/languages'
import { FieldInterface } from '../Field.model'
import { FieldVardef } from '@/store/modules'

const field: FieldInterface = {
    toAppFormat: (value: string | string[]): string[] => {
        if (value == null || value === '') {
            return []
        }
        if (typeof value === 'string') {
            value = value.replaceAll('^', '').split(',')
        }
        return value
    },
    userFormatter: (value: string | string[], defs: FieldVardef) => {
        if (!value) {
            return ''
        }
        const language = useLanguagesStore()
        if (typeof value === 'string') {
            value = value.replaceAll('^', '').split(',')
        }
        return value.map((v) => language.translateListValue(v, defs.options) || v).join(', ')
    },
    serverFormatter: (value: string | string[]) => {
        if (typeof value === 'string') {
            value = value.replaceAll('^', '').split(',')
        }
        return value
    },
}

export default field
