import { usePreferencesStore } from '@/store/preferences'
import { FieldInterface } from '../Field.model'
import { useLanguagesStore } from '@/store/languages'

const field: FieldInterface = {
    validator: (value: any) => {
        if (!value || !(value instanceof File)) {
            return
        }
        const maxSize = usePreferencesStore().global?.upload_maxsize
        if (maxSize && value && value.size > maxSize) {
            const mb = Math.round(maxSize / 1000 / 1000)
            return useLanguagesStore().label('LBL_UPLOAD_MAXSIZE_EXCEEDED', null, { size_mb: mb })
        }
    },
}

export default field
