import { LegacyEvent } from '../LegacyEvent'
import { useReturnLocationStore, type ReturnViewState } from '@/store/returnLocation'

/**
 * A legacy view reports the state it is about to be navigated away from.
 *
 * Sent by the calendar just before it hands a record URL to the shell, because only the calendar
 * knows which view type and date range are on screen - and the dashboard's calendar dashlet has no
 * URL of its own that could carry them.
 *
 * Only the reported state is stored here. Turning it into a return location is LegacyView's job -
 * it is the one that knows which route the user is being sent to.
 */
export class CaptureReturnLocation extends LegacyEvent {
    protected async execute() {
        useReturnLocationStore().reportViewState(this.data as ReturnViewState)

        return true
    }
}
