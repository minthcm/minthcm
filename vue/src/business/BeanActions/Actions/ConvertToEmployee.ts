import { BeanAction } from '../BeanAction'
import { usePopupsStore } from '@/store/popups'
import { useLanguagesStore } from '@/store/languages'
import ConvertToEmployeePopup from '@/components/Popups/ConvertToEmployeePopup.vue'

export class ConvertToEmployee extends BeanAction {
    public static readonly TITLE = 'LBL_CONVERT_TO_EMPLOYEE'
    public static readonly ICON = 'mdi-account-convert'
    public static readonly ACL = ['edit']

    public isAvailable(): boolean {
        return super.isAvailable() && this.bean.attributes.parent_type === 'Candidates'
    }

    public async execute() {
        usePopupsStore().showPopup({
            title: useLanguagesStore().label('LBL_CONVERT_TO_EMPLOYEE', this.bean.module),
            icon: 'mdi-account-convert',
            component: ConvertToEmployeePopup,
            data: {
                candidature_id: this.bean.id,
                module_name: this.bean.module,
            },
        })
        return true
    }
}
