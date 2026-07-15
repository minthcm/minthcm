import { usePopupsStore } from '@/store/popups'
import { MassAction } from '../MassAction'
import MintPopupAlert from '@/components/MintPopups/MintPopupAlert.vue'
import { useLanguagesStore } from '@/store/languages'
export class MassConfirmation extends MassAction {
    public async execute() {
        const popupsStore = usePopupsStore()
        const languages = useLanguagesStore()

        try {
            await this.sendRequest()
            popupsStore.showPopup({
                component: MintPopupAlert,
                title: languages.label('LBL_ALT_INFO'),
                icon: 'mdi-information-outline',
                data: {
                    text: languages.label('LBL_MASSCONFIRMATION_POPUP_TEXT', this.module),
                    onConfirm: () => {},
                },
            })
        } catch {
            popupsStore.showPopup({
                component: MintPopupAlert,
                title: languages.label('LBL_ERROR'),
                icon: 'mdi-alert',
                data: {
                    text: languages.label('LBL_MASSCONFIRMATION_POPUP_ERROR', this.module),
                    onConfirm: () => {},
                },
            })
        }

        return true
    }
}
