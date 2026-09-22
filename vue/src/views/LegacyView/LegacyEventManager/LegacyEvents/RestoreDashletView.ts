import { LegacyEvent } from '../LegacyEvent'
import { useReturnLocationStore } from '@/store/returnLocation'

/**
 * A calendar dashlet asks for the date range it was showing before the user opened a record. #191870
 *
 * The dashboard renders its dashlets over AJAX after the page itself has loaded, so there is no
 * moment at which the shell could reliably push this state into the frame - the dashlet may not
 * exist yet. Letting the dashlet ask once it is ready removes the timing problem entirely.
 *
 * Answers at most once: the state is consumed here, so the re-render that follows the restore asks
 * again and gets nothing, which is what stops it from looping.
 */
export class RestoreDashletView extends LegacyEvent {
    protected async execute() {
        const returnLocation = useReturnLocationStore()
        const viewState = returnLocation.peek()?.viewState

        if (
            !viewState ||
            viewState.source !== 'calendar-dashlet' ||
            !viewState.dashletId ||
            viewState.dashletId !== this.data?.dashletId
        ) {
            return null
        }

        returnLocation.consume()

        return {
            view: viewState.view,
            year: viewState.year,
            month: viewState.month,
            day: viewState.day,
        }
    }
}
