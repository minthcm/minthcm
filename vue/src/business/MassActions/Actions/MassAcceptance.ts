import { usePopupsStore } from '@/store/popups'
import { useLanguagesStore } from '@/store/languages'
import { MassAction } from '../MassAction'
import { markRaw, reactive } from 'vue'
import MintPopupMassAcceptance from '@/components/MintPopups/MintPopupMassAcceptance.vue'

const BATCH_SIZE = 10

export class MassAcceptance extends MassAction {
    protected static readonly actionName = 'MassAcceptance'

    public async execute(): Promise<boolean> {
        const popupsStore = usePopupsStore()
        const languages = useLanguagesStore()

        const total = this.ids.length
        if (total === 0) {
            return false
        }

        const state = reactive({
            processed: 0,
            total,
            accepted: 0,
            skipped: 0,
            done: false,
            module: this.module,
            onConfirm: () => {},
        })

        popupsStore.showPopup({
            title: languages.label('LBL_MASS_ACCEPTANCE', this.module),
            icon: 'mdi-check-all',
            unclosable: true,
            component: markRaw(MintPopupMassAcceptance),
            data: state,
        })

        const closePopup = () => {
            const storedPopup = popupsStore.popups.find((p) => p.data === state)
            if (storedPopup) {
                popupsStore.closePopup(storedPopup)
            }
        }

        try {
            for (let i = 0; i < this.ids.length; i += BATCH_SIZE) {
                const batch = this.ids.slice(i, i + BATCH_SIZE)

                const response = await this.sendRequest({
                    ids: batch,
                })

                state.accepted += response.data?.accepted ?? 0
                state.skipped += response.data?.skipped ?? 0
                state.processed = Math.min(i + BATCH_SIZE, total)
            }

            state.processed = total
            state.done = true

            await new Promise<void>((resolve) => {
                state.onConfirm = () => {
                    closePopup()
                    resolve()
                }
            })

            return true
        } catch {
            closePopup()
            const errorText = languages
                .label('LBL_MASSACCEPTANCE_POPUP_ERROR', this.module)
                .replace('{accepted}', String(state.accepted))
                .replace('{skipped}', String(state.skipped))
            await popupsStore.alert(errorText)
            return state.accepted > 0
        }
    }
}
