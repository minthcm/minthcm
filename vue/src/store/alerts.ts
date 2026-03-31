import { ref, computed, watch } from 'vue'
import { defineStore } from 'pinia'
import Favico from 'favico.js'
import { mintApi } from '@/api/api'

export interface Alert {
    id: string
    name: string
    description: string
    date_entered: string
    is_read: boolean
    parent_id: string
    parent_type: string
    url_redirect: string
}

export const useAlertsStore = defineStore('alerts', () => {
    const FETCH_INTERVAL_MS = 1000 * 60
    const CLOSE_ALL_DELAY_MS = 5000
    let closeAllTimeout: number | null = null

    const alerts = ref<Alert[]>([])
    const isFetching = ref(false)
    const isClosingAll = ref(false)
    const favico = new Favico()
    const moreResults = ref(false)
    const excludedOverallAlerts = ['Kudos', 'News', 'UsersNews']

    function init() {
        fetchAlerts()
        setInterval(fetchAlerts, FETCH_INTERVAL_MS)
    }

    async function fetchAlerts() {
        if (isFetching.value || isClosingAll.value) {
            return
        }
        isFetching.value = true
        const response = await mintApi.get('Alerts')
        alerts.value = response.data?.alerts ?? []
        moreResults.value = response.data?.moreResults ?? false
        isFetching.value = false
    }

    async function markRead(id: string, shouldFetch: boolean = false) {
        const response = await mintApi.patch(`Alerts/${id}`, {
            is_read: true,
            fetch: shouldFetch,
        })
        if (shouldFetch) {
            alerts.value = response.data?.alerts ?? []
    }
        return response.status
    }

    async function close(id: string, shouldFetch: boolean = false) {
        const response = await mintApi.patch(`Alerts/${id}`, {
            is_closed: true,
            fetch: shouldFetch,
        })
        if (shouldFetch) {
            alerts.value = response.data?.alerts ?? []
        }
        return response.status
    }

    const filteredAlerts = computed(() => {
        return alerts.value.filter((alert) => !excludedOverallAlerts.includes(alert.parent_type))
    })

    const unreadAlertsCount = computed(() => {
        return alerts.value.filter((alert) => !alert.is_read).length
    })

    const unreadFilteredAlertsCount = computed(() => {
        return filteredAlerts.value.filter((alert) => !alert.is_read).length
    })

    const unreadFilteredAlertsCountText = computed(() => {
        return moreResults.value && unreadFilteredAlertsCount.value >= 50
            ? unreadFilteredAlertsCount.value + '+'
            : unreadFilteredAlertsCount.value
    })

    const sortedFilteredAlerts = computed(() => {
        return [...filteredAlerts.value].sort((a, b) => (a.date_entered < b.date_entered ? 1 : -1))
    })

    async function markAllAsRead() {
        const records = alerts.value.flatMap((alert) => (!alert.is_read ? alert.id : []))
        alerts.value = alerts.value.map((alert) => ({ ...alert, is_read: true }))
        const response = await mintApi.patch('Alerts/update/ReadAlerts', { records })
        alerts.value = response.data ?? []
    }

    async function closeAll() {
        const records = alerts.value.map((alert) => alert.id)
        if (!records.length) {
            return
        }
        isClosingAll.value = true
        closeAllTimeout = setTimeout(async () => {
            alerts.value = []
            try {
                const response = await mintApi.patch('Alerts/update/CloseAlerts', { records })
                alerts.value = response.data ?? []
                isClosingAll.value = false
            } catch {
                isClosingAll.value = false
                fetchAlerts()
            }
        }, CLOSE_ALL_DELAY_MS)
    }

    function cancelCloseAll() {
        if (closeAllTimeout) {
            clearTimeout(closeAllTimeout)
        }
        isClosingAll.value = false
        fetchAlerts()
    }

    watch(unreadAlertsCount, (newCount) => {
        if (newCount) {
            favico.badge(newCount)
        } else {
            favico.reset()
        }
    })

    return {
        init,
        markRead,
        close,
        alerts,
        filteredAlerts,
        unreadAlertsCount,
        sortedFilteredAlerts,
        markAllAsRead,
        closeAll,
        isClosingAll,
        cancelCloseAll,
        unreadFilteredAlertsCountText,
        unreadFilteredAlertsCount,
        moreResults,
    }
})
