import { BeanAction } from '../BeanAction'
import { usePopupsStore } from '@/store/popups'
import { useLanguagesStore } from '@/store/languages'
import { mintApi } from '@/api/api'

export class CloseWorkSchedule extends BeanAction {
    public static readonly TITLE = 'LBL_CLOSE_PLAN'
    public static readonly ICON = 'mdi-close-circle'
    public static readonly ACL = ['edit']
    public static readonly DONT_CHECK_TYPES = [
        'holiday',
        'sick',
        'occasional_leave',
        'overtime',
        'excused_absence',
        'leave_at_request',
        'child_care',
    ]
    public static readonly VALIDATION_MESSAGES = {
        '2': 'ERR_SPENT_TIMES_DO_NOT_OVERLAP_WITH_WORK_SCHEDULE',
        '3': 'ERR_WORKPLACE_IS_REQUIRED',
        '4': 'ERR_WORKPLACE_IS_NOT_ACTIVE',
    }

    public isAvailable(): boolean {
        return (
            super.isAvailable() &&
            ['WorkSchedules'].includes(this.bean.module) &&
            !['closed'].includes(this.bean.attributes.status)
        )
    }

    public async execute() {
        if (!(await usePopupsStore().confirm(useLanguagesStore().label('LBL_CLOSE_PLAN_CONFIRM')))) {
            return false
        }
        if (CloseWorkSchedule.DONT_CHECK_TYPES.includes(this.bean.attributes.type) || (await this.canBeClosed())) {
            this.bean.updateFields({
                status: 'closed',
            })
            const successfulSave = await this.bean.save()
            if (!successfulSave.status) {
                console.error(this.bean.validationError.value)
                return false
            }
            window.location.reload()
        }
        return true
    }

    async canBeClosed() {
        const response = await mintApi.post('WorkSchedules/checkIfCanBeClosed', {
            record: this.bean.id,
        })
        if (response?.data?.result != '1') {
            await usePopupsStore().alert(
                useLanguagesStore().label(
                    CloseWorkSchedule.VALIDATION_MESSAGES[response?.data?.result],
                    'WorkSchedules',
                    { name: this.bean.name },
                ),
            )
            return false
        }
        return true
    }
}
