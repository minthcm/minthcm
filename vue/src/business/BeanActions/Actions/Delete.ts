import { BeanAction } from '../BeanAction'
import router from '@/router'
import { usePopupsStore } from '@/store/popups'
import { useLanguagesStore } from '@/store/languages'

export class Delete extends BeanAction {
    public static readonly TITLE = 'LBL_DELETE_BUTTON_LABEL'
    public static readonly ICON = 'mdi-delete'
    public static readonly ACL = ['delete']

    public async execute() {
        if (!(await usePopupsStore().confirm(useLanguagesStore().label('LBL_MINT4_BEAN_DELETE_CONFIRM')))) {
            return false
        }
        await this.bean.markDeleted()
        router.push({
            name: 'list',
            params: {
                module: this.bean.module,
                action: 'ESListView',
            },
        })
        return true
    }
}
