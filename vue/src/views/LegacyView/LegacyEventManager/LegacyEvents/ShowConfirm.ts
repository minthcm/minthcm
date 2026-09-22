import { LegacyEvent } from '../LegacyEvent'
import { usePopupsStore } from '@/store/popups'

export class ShowConfirm extends LegacyEvent {
    protected execute() {
        return usePopupsStore().confirm(this.data.text, { closable: true })
    }
}
