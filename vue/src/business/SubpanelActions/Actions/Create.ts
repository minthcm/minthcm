import { useBean } from '@/composables/useBean'
import { SubpanelAction } from '../SubpanelAction'
import router from '@/router'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'
import { ref } from 'vue'

export class Create extends SubpanelAction {
    public static readonly TITLE = 'LBL_CREATE_BUTTON_LABEL'
    public static readonly ACTION_KEY = 'create'
    public static readonly ICON = 'mdi-plus'
    public static readonly ACL = ['edit']

    public async execute() {
        const store = useRecordViewStore()
        const relationship_name = store.bean.fieldDefs?.[this.subpanel.properties.get_subpanel_data]?.relationship
        const relate_bean = ref<ReturnType<typeof useBean>>(useBean(this.subpanel.module, ''))
        const link = relate_bean.value.loadRelationship(relationship_name)

        let query: { [key: string]: string } = {
            return_action: 'DetailView',
            return_id: store.bean.id,
            return_module: store.bean.module,
            return_relationship: relationship_name,
        }

        if (link) {
            if (link?.relateFieldName && store.bean.attributes.name) {
                query[link.relateFieldName] = store.bean.attributes.name
            }

            if (link?.idFieldName) {
                query[link.idFieldName] = store.bean.id
            }

            if(link?.parentFieldName && store.bean.attributes.name) {
                query[link.parentFieldName] = store.bean.attributes.name
            }

            if(link?.parentIdFieldName) {
                query[link.parentIdFieldName] = store.bean.id
            }

            if(link?.parentTypeFieldName) {
                query[link.parentTypeFieldName] = store.bean.module
            }
        }

        router.push({
            path: `/modules/${this.subpanel.module}/EditView`,
            query: query
        })
        return true
    }
}
