import { ref } from 'vue'
import { defineStore } from 'pinia'

export interface StatusBoxOptions {
    type: 'error' | 'success' | 'info'
    message?: string,
    autoClose?: boolean
    autoCloseDelay?: number
    closeable?: boolean
    onClose?: () => void
}

export const useStatusBoxesStore = defineStore('statusBoxes', () => {
    const statusBoxes = ref<Map<string, StatusBoxOptions>>(new Map())

    function showStatus(key: string, options: StatusBoxOptions) {
        if (options.autoClose === undefined) options.autoClose = false
        if (options.autoClose && !options.autoCloseDelay) options.autoCloseDelay = 3600
        if (options.closeable === undefined) options.closeable = true
        statusBoxes.value.set(key, options)
    }

    function close(key: string) {
        const box = statusBoxes.value.get(key)
        if (typeof box?.onClose === 'function') box.onClose()
        statusBoxes.value.delete(key)
    }

    function closeAll() {
        statusBoxes.value.clear()
    }

    return {
        statusBoxes,
        showStatus,
        close,
        closeAll,
    }
})
