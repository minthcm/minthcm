import { usePopupsStore } from '@/store/popups'
import { SubpanelAction } from '../SubpanelAction'
import MintPopupRelate from '@/components/MintPopups/MintPopupRelate.vue'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'
import { useACL } from '@/composables/useACL'
import { useLanguagesStore } from '@/store/languages'
import { mintApi } from '@/api/api'

interface SelectionList {
    [key: string]: {
        id: string
    }
}

export class SelectWorkScheduleForDelegation extends SubpanelAction {
    public static readonly TITLE = 'LBL_LINK_RECORD_BUTTON'
    public static readonly ICON = 'mdi-link'
    public static readonly ACTION_KEY = 'selectWorkScheduleForDelegation'
    public static readonly ACL = ['list']

    public async execute() {
        const popupsStore = usePopupsStore()
        const store = useRecordViewStore()
        const languagesStore = useLanguagesStore()
        
        popupsStore.showPopup({
            component: MintPopupRelate,
            title: languagesStore.label(this.subpanel.label),
            icon: 'mdi-view-list',
            data: {
                moduleName: this.subpanel.module,
                popupMode: 'MultiSelect',
                filterDefs: [
                    {
                        field: 'status',
                        operator: 'contain',
                        value: ["planned","worked","closed"],
                        editable: false
                    },
                    {
                        field: 'type',
                        operator: 'contain',
                        value: ["home","delegation"],
                        editable: false
                    },
                    {
                        field: 'assigned_user_name',
                        operator: 'search',
                        value: store.bean.attributes.assigned_user_id,
                        editable: false
                    },
                ],
                onConfirm: async (data: SelectionList) => {
                    const ids = data.selectionList ? Object.values(data.selectionList) : []
                    if (!ids.length) {
                        return
                    }
                    try {
                        await mintApi.post(`${this.bean.module}/Link/${this.bean.id}`, {
                            link_name: this.subpanel.properties.get_subpanel_data || this.subpanel.key,
                            ids,
                        })
                        store.fetchSubpanelRecords(this.subpanel.key, this.subpanel.paginateBy, 0)
                    } catch (error) {
                        console.error('Error linking records:', error.response.data?.errors)
                    }
                },
                onClose: () => {},
            },
        })
        return true
    }

    public isAvailable(): boolean {
        return useACL().hasAccess(this.bean.module, 'edit', true) && super.isAvailable()
    }
}
