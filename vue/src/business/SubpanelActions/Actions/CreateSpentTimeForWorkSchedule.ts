import { useBean } from '@/composables/useBean'
import { SubpanelAction } from '../SubpanelAction'
import router from '@/router'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'
import { useACL } from '@/composables/useACL'
import { ref } from 'vue'

export class CreateSpentTimeForWorkSchedule extends SubpanelAction {
    public static readonly TITLE = 'LBL_CREATE_BUTTON_LABEL'
    public static readonly ICON = 'mdi-plus'
    public static readonly ACL = ['edit']

    public async execute() {
        const store = useRecordViewStore()

        const relateBean = ref<ReturnType<typeof useBean>>(useBean(this.subpanel.module, ''))
        const relationshipName = store.bean.fieldDefs?.[this.subpanel.properties.get_subpanel_data]?.relationship
        const link = relateBean.value.loadRelationship(relationshipName)

        const query: { [key: string]: string } = {
            return_action: 'DetailView',
            return_id: store.bean.id,
            return_module: store.bean.module,
            return_relationship: relationshipName,
            workschedule_id: store.bean.id,
            workschedule_name: store.bean.attributes.name,
        }

        if (link) {
            if (link.relateFieldName.value && store.bean.attributes.name) {
                query[link.relateFieldName.value] = store.bean.attributes.name
            }
            if (link.idFieldName.value) {
                query[link.idFieldName.value] = store.bean.id
            }
        }

        router.push({
            path: `/modules/${this.subpanel.module}/EditView`,
            query,
        })
        return true
    }

    public isAvailable(): boolean {
        const store = useRecordViewStore()
        if (store.bean.attributes.status === 'closed') {
            return false
        }
        return useACL().hasAccess(this.bean.module, 'edit', true) && super.isAvailable()
    }
}
