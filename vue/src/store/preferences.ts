import { ref } from 'vue'
import { defineStore } from 'pinia'

interface GlobalPreferences {
    [key: string]: any
}

export const usePreferencesStore = defineStore('preferences', () => {
    const global = ref<GlobalPreferences | null>(null)

    return {
        global,
    }
})
