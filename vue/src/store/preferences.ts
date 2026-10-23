import { computed, ref } from 'vue'
import { defineStore } from 'pinia'

interface GlobalPreferences {
    [key: string]: any
}

interface UserPreferences {
    [key: string]: any
}

export const usePreferencesStore = defineStore('preferences', () => {
    const global = ref<GlobalPreferences | null>(null)
    const user = ref<UserPreferences | null>(null)

    function getFirstNameFieldByPreference(): 'first_name' | 'last_name' {
        const first = user.value?.name_format
            .toLowerCase()
            .split('')
            .find((c) => c === 'f' || c === 'l')
        if (first === 'f') return 'first_name'
        if (first === 'l') return 'last_name'
        return 'first_name'
    }

    const userDateFormat = computed<string>(() => {
        return user.value?.date_format || 'yyyy-MM-dd'
    })

    const userTimeFormat = computed(() => {
        return user.value?.time_format || 'HH:mm:ss'
    })

    const userDatetimeFormat = computed(() => {
        return `${userDateFormat.value} ${userTimeFormat.value}`
    })

    const userTimezone = computed(() => {
        return user.value?.timezone || 'utc'
    })

    // Default system language when it is enabled, otherwise the first enabled one
    const fallbackLanguage = computed<string>(() => {
        const enabledLanguages = Object.keys(global.value?.languages ?? {})
        const defaultLanguage = global.value?.default_language
        if (defaultLanguage && enabledLanguages.includes(defaultLanguage)) {
            return defaultLanguage
        }
        return enabledLanguages[0] ?? 'en_us'
    })

    return {
        global,
        user,
        getFirstNameFieldByPreference,
        fallbackLanguage,
        userDateFormat,
        userTimeFormat,
        userDatetimeFormat,
        userTimezone,
    }
})
