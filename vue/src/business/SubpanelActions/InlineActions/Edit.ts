import { useBean } from '@/composables/useBean'
import { SubpanelAction } from '../SubpanelAction'
import router from '@/router'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'
import { ref } from 'vue'

export class Edit extends SubpanelAction {
    public static readonly TITLE = 'LBL_EDIT_BUTTON_LABEL'
    public static readonly ICON = 'mdi-pencil'
    public static readonly ACL = ['edit']

    public async execute() {
        const store = useRecordViewStore()

        const relateBean = ref<ReturnType<typeof useBean>>(useBean(this.subpanel.module, this.options?.recordId))
        const relationshipName = store.bean.fieldDefs?.[this.subpanel.properties.get_subpanel_data]?.relationship
        const link = relateBean.value.loadRelationship(relationshipName)

        let query: { [key: string]: string } = {
            return_action: 'DetailView',
            return_id: store.bean.id,
            return_module: store.bean.module,
            return_relationship: relationshipName,
            parent_type: store.bean.module,
            parent_id: store.bean.id,
            parent_name: store.bean.attributes.name,
        }

        if (link) {
            if (link.relateFieldName && store.bean.attributes.name) {
                query[link.relateFieldName] = store.bean.attributes.name
            }
            if (link.idFieldName) {
                query[link.idFieldName] = store.bean.id
            }
        }

        router.push({
            path: `/modules/${this.subpanel.module}/EditView/${relateBean.value.id}`,
            query: query
        })
        return true
    }
}
