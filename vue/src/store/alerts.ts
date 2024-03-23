import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios'

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

    function init() {
        fetchAlerts()
        setInterval(fetchAlerts, FETCH_INTERVAL_MS)
    }

    async function fetchAlerts() {
        if (isFetching.value || isClosingAll.value) {
            return
        }
        isFetching.value = true
        const response = await axios.get('api/Alerts')
        alerts.value = response.data ?? []
        isFetching.value = false
    }

    async function markRead(id: string) {
        const response = await axios.patch(`api/Alerts/${id}`, {
            is_read: true,
        })
        alerts.value = response.data ?? []
    }

    async function close(id: string) {
        const response = await axios.patch(`api/Alerts/${id}`, {
            is_closed: true,
        })
        alerts.value = response.data ?? []
    }

    const unreadAlertsCount = computed(() => {
        return alerts.value.filter((alert) => !alert.is_read).length
    })

    const sortedAlerts = computed(() => {
        return [...alerts.value].sort((a, b) => (a.date_entered < b.date_entered ? 1 : -1))
    })

    async function markAllAsRead() {
        const records = alerts.value.flatMap((alert) => (!alert.is_read ? alert.id : []))
        alerts.value = alerts.value.map((alert) => ({ ...alert, is_read: true }))
        const response = await axios.patch('api/Alerts/update/ReadAlerts', { records })
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
                const response = await axios.patch('api/Alerts/update/CloseAlerts', { records })
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

    return {
        init,
        markRead,
        close,
        alerts,
        unreadAlertsCount,
        sortedAlerts,
        markAllAsRead,
        closeAll,
        isClosingAll,
        cancelCloseAll,
    }
})
