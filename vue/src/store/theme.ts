import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { mintApi } from '@/api/api'

/**
 * ThemePreference — value stored by the user.
 * 'system' means "follow the OS setting".
 * Any additional theme name (e.g. 'high-contrast') can be appended here
 * and registered in vuetify.ts to activate it as an option.
 */
export type ThemePreference = 'system' | string

/**
 * ActiveTheme — the resolved theme that is actually applied.
 * It is always one of the themes registered in Vuetify (never 'system').
 * When adding a new Vuetify theme, no change to this type is needed.
 */
export type ActiveTheme = string

/** Themes that can be resolved as the active (applied) theme. */
const SYSTEM_THEME_DEFAULT = 'light' satisfies ActiveTheme
const RESOLVED_THEMES: ActiveTheme[] = ['light', 'dark']

const STORAGE_KEY = 'mint.theme.preference'

export const useThemeStore = defineStore('theme', () => {
    const preference = ref<ThemePreference>(localStorage.getItem(STORAGE_KEY) ?? 'system')
    const systemTheme = ref<ActiveTheme>(detectSystemTheme())

    function detectSystemTheme(): ActiveTheme {
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : SYSTEM_THEME_DEFAULT
    }

    const activeTheme = computed<ActiveTheme>(() => {
        if (preference.value === 'system') {
            return systemTheme.value
        }
        // Resolved themes are those registered in Vuetify — fall back to system if unknown
        if (RESOLVED_THEMES.includes(preference.value)) {
            return preference.value
        }
        return systemTheme.value
    })

    function init(value: string) {
        // Accept any non-empty string — the allowlist is enforced server-side
        preference.value = value && value.length > 0 ? value : 'system'
        localStorage.setItem(STORAGE_KEY, preference.value)
    }

    async function setPreference(value: ThemePreference) {
        preference.value = value
        localStorage.setItem(STORAGE_KEY, value)
        await mintApi.post('user/theme', { theme: value })
    }

    // Listen for OS-level theme changes
    const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)')
    mediaQuery.addEventListener('change', (e) => {
        systemTheme.value = e.matches ? 'dark' : SYSTEM_THEME_DEFAULT
    })

    return {
        preference,
        activeTheme,
        init,
        setPreference,
    }
})
