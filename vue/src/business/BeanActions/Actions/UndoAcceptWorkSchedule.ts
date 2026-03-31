import { BeanAction } from '../BeanAction'
import { useAuthStore } from '@/store/auth'
import { useLanguagesStore } from '@/store/languages'
import { useStatusBoxesStore } from '@/store/statusBoxes'

export class UndoAcceptWorkSchedule extends BeanAction {
    public static readonly TITLE = 'LBL_UNDO_ACCEPTANCE_BTN'
    public static readonly ICON = 'mdi-arrow-u-left-top-bold'
    public static readonly ACL = ['edit']

    public isAvailable(): boolean {
        return super.isAvailable() 
            && ['WorkSchedules'].includes(this.bean.module) 
            && ['accepted'].includes(this.bean.attributes.supervisor_acceptance)
            && this.bean.attributes.assigned_user_id !== useAuthStore().user?.id
    }

    public async execute() {
        this.bean.updateFields({
            supervisor_acceptance: 'wait'
        })
        const successfulSave = await this.bean.save()
        if (!successfulSave.status) {
            useStatusBoxesStore().showStatus('undo_accept_bean_save_error', {
                type: 'error',
                message: useLanguagesStore().label('LBL_UNDO_ACCEPTANCE_ACTION_ERROR', this.bean.module),
                autoClose: true,
            })
            console.error(this.bean.validationError.value);
            return false;
        }
        return true
    }
}
