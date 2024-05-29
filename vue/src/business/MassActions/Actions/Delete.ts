import { MassAction } from '../MassAction'
import { usePopupsStore } from '@/store/popups'
import { useLanguagesStore } from '@/store/languages'

export class Delete extends MassAction {
    public async execute() {
        if (!(await usePopupsStore().confirm(useLanguagesStore().label('LBL_MINT4_MASS_DELETE_CONFIRM')))) {
            return false
        }
        const result = await this.sendRequest()
        if (!result.data?.success) {
            await usePopupsStore().alert(useLanguagesStore().label('LBL_MINT4_MASS_DELETE_ERROR'))
            return false
        }
        return true
    }
}
