import { FieldInterface } from '../Field.model'
import { MintDate, useMintDate } from '@/composables/useMintDate'

const field: FieldInterface = {
    toAppFormat: (value: unknown): MintDate => {
        return useMintDate(value)
    },
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
