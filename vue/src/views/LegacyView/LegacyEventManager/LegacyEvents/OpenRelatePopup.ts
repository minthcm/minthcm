import MintPopupRelate from '@/components/MintPopups/MintPopupRelate.vue'
import { LegacyEvent } from '../LegacyEvent'
import { usePopupsStore } from '@/store/popups'
import { useLanguagesStore } from '@/store/languages'
import { useBackendStore } from '@/store/backend'

export class OpenRelatePopup extends LegacyEvent {
    protected execute() {
        const popupsStore = usePopupsStore()
        const backendStore = useBackendStore()
        return new Promise((resolve) => {
            if (popupsStore.popups.find((popup) => popup.component === MintPopupRelate)) {
                resolve(false)
                return
            }
            if (
                ['Audit'].includes(this.data.moduleName) ||
                !backendStore.initData ||
                backendStore.initData.legacy_views[this.data.moduleName]?.popup === true ||
                (backendStore.initData.legacy_views[this.data.moduleName]?.popup !== false &&
                    backendStore.initData.legacy_views[this.data.moduleName]?.list === true)
            ) {
                resolve(null)
                return
            }
            popupsStore.showPopup({
                component: MintPopupRelate,
                title: useLanguagesStore().translateListValue(this.data.moduleName, 'moduleList'),
                icon: 'mdi-view-list',
                data: {
                    ...this.data,
                    onConfirm: (data: string | string[]) => resolve(data),
                    onClose: () => resolve(false),
                },
            })
        })
    }
}
