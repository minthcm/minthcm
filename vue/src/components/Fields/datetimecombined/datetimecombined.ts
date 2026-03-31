import { FieldInterface } from '../Field.model'
import { MintDate } from '@/composables/useMintDate'

const field: FieldInterface = {
    userFormatter: (value: MintDate) => {
        if (value.isValid) {
            return value.formatted.user_datetime
        }
        return ''
    },
    serverFormatter: (value: MintDate) => {
        if (value.isValid) {
            return value.formatted.iso
        }
        return ''
    },
}

export default field
