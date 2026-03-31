import { useBean } from '@/composables/useBean'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'
import { ref } from 'vue'
import { usePopupsStore } from '@/store/popups'
import { useLanguagesStore } from '@/store/languages'
import { SubpanelAction } from '../SubpanelAction'

export class Delete extends SubpanelAction {
    public static readonly TITLE = 'LBL_DELETE_BUTTON_LABEL'
    public static readonly ICON = 'mdi-trash-can-outline'
    public static readonly ACL = ['delete', 'edit']

    public async execute() {
        if (!(await usePopupsStore().confirm(useLanguagesStore().label('NTC_DELETE_CONFIRMATION')))) {
            return false
        }
        const store = useRecordViewStore()

        const relateBean = ref<ReturnType<typeof useBean>>(useBean(this.subpanel.module, this.options?.recordId))
        relateBean.value.markDeleted()
        store.fetchSubpanelRecords(this.subpanel.key, this.subpanel.paginateBy, 0)
        return true
    }
}
