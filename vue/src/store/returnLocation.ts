import { ref } from 'vue'
import { defineStore } from 'pinia'
import { StorageSerializers, useStorage } from '@vueuse/core'

/**
 * State needed to rebuild the view a record was opened from.
 *
 * Opaque to this store on purpose - it is produced by the legacy calendar and handed straight back
 * to it on the way out, so the shell never has to know what a calendar view type or date range is.
 */
export interface ReturnViewState {
    source: string
    dashletId: string | null
    view: string
    year: number | string
    month: number | string
    day: number | string
}

export interface ReturnLocation {
    /** Route path to go back to, e.g. '/Calendar'. Compared against the current route. */
    path: string
    /**
     * Router location to navigate back to, ready to hand to router.push. Carries whatever legacy
     * view state the address held, so consumers never parse the address themselves.
     */
    routerLocation: string
    /** Route path the user was sent to. Used to drop the location once they navigate elsewhere. */
    recordPath: string
    /** Extra state for places whose URL cannot carry it - currently the dashboard's dashlet. */
    viewState: ReturnViewState | null
}

/**
 * Remembers where to return to after a record is closed, and which view state to restore there.
 *
 * Backed by sessionStorage rather than localStorage: the location has to survive a page refresh on
 * the record, but must not outlive the tab. A remembered view leaking into a later visit would
 * break the rule that entering the calendar from scratch opens the current week.
 */
export const useReturnLocationStore = defineStore('returnLocation', () => {
    const returnLocation = useStorage<ReturnLocation | null>('app.return.location', null, sessionStorage, {
        serializer: StorageSerializers.object,
    })

    /**
     * View state reported by a legacy view just before it asks the shell to open a record.
     * Kept in memory only - it is picked up by the very next capture(), milliseconds later.
     */
    const reportedViewState = ref<ReturnViewState | null>(null)

    function reportViewState(viewState: ReturnViewState) {
        reportedViewState.value = viewState
    }

    function capture(location: Omit<ReturnLocation, 'viewState'>) {
        returnLocation.value = { ...location, viewState: reportedViewState.value }
        reportedViewState.value = null
    }

    function peek(): ReturnLocation | null {
        return returnLocation.value
    }

    function consume(): ReturnLocation | null {
        const location = returnLocation.value
        returnLocation.value = null
        return location
    }

    function clear() {
        returnLocation.value = null
        reportedViewState.value = null
    }

    /**
     * Points the journey at a new address for the same record. Saving a new record swaps the
     * temporary '.../EditView' address for the saved record's own one - the same journey
     * continuing, which must not be mistaken for the user walking away.
     */
    function followRecord(recordPath: string) {
        if (!returnLocation.value) {
            return
        }
        returnLocation.value = { ...returnLocation.value, recordPath }
    }

    /**
     * True while the user is still on the journey the location was captured for - either on the
     * record itself or back at its origin. Anywhere else means they left by another route, which
     * makes the location stale.
     */
    function isOnJourney(path: string): boolean {
        const location = returnLocation.value
        if (!location) {
            return false
        }
        return path === location.recordPath || path === location.path
    }

    return {
        reportViewState,
        capture,
        peek,
        consume,
        clear,
        followRecord,
        isOnJourney,
    }
})
