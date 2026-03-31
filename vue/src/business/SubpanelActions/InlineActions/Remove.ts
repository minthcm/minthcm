import { useBean } from '@/composables/useBean'
import { SubpanelAction } from '../SubpanelAction'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'
import { ref } from 'vue'
import { usePopupsStore } from '@/store/popups'
import { useLanguagesStore } from '@/store/languages'
import { useACL } from '@/composables/useACL'

export class Remove extends SubpanelAction {
    public static readonly TITLE = 'LBL_DELETE_BUTTON_LABEL'
    public static readonly ICON = 'mdi-trash-can-outline'
    public static readonly ACL = ['delete', 'edit']

    public async execute() {
        if (!(await usePopupsStore().confirm(useLanguagesStore().label('NTC_REMOVE_CONFIRMATION')))) {
            return false
        }
        const store = useRecordViewStore()

        const relateBean = ref<ReturnType<typeof useBean>>(useBean(this.subpanel.module, this.options?.recordId))
        const link = store.bean.loadRelationship(this.subpanel.properties.get_subpanel_data)
        link?.remove(relateBean.value.id);
        await link?.unlink(store.bean, this.subpanel.properties.get_subpanel_data)
        store.fetchSubpanelRecords(this.subpanel.key, this.subpanel.paginateBy, 0)
        return true
    }

    public isAvailable(): boolean {
        const store = useRecordViewStore()
        const self = this.constructor as typeof SubpanelAction
        const requiredACL = this.options.acl || self.ACL
        const aclHelper = useACL()
        return requiredACL.every((acl) => aclHelper.hasAccess(this.subpanel.module, acl, true)) && requiredACL.every((acl) => aclHelper.hasAccess(store.bean.module, acl, true))
    }
}
