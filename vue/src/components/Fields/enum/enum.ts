import { useLanguagesStore } from '@/store/languages'
import { FieldInterface } from '../Field.model'
import { FieldVardef } from '@/store/modules'

const field: FieldInterface = {
    userFormatter: (value: string, defs: FieldVardef) => {
        if (!value) {
            return ''
        }
        const language = useLanguagesStore()
        return language.translateListValue(value, defs.options) || value
    },
}

export default field
