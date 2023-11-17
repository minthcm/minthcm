import { ref } from 'vue'
import { defineStore } from 'pinia'
import { useRoute, useRouter } from 'vue-router'
import { useApi } from '@/composables/useApi'
import { useAuthStore, User } from './auth'
import { useAlertsStore } from './alerts'
import { useFavoritesStore } from './favorites'
import { useRecentsStore } from './recents'
import { useLanguagesStore, Languages } from './languages'
import axios, { AxiosError } from 'axios'
import { useModulesStore, ModulesDefs } from './modules'
import { usePreferencesStore } from './preferences'

interface QuickCreate {
    module: string
    name: string
}

interface InitResponse {
    user: User
    languages: Languages
    modules: ModulesDefs
    menu_modules: string[]
    quick_create: QuickCreate[]
    global: any
}
export const useBackendStore = defineStore('backend', () => {
    const route = useRoute()
    const router = useRouter()
    const alerts = useAlertsStore()
    const favorites = useFavoritesStore()
    const recents = useRecentsStore()
    const languages = useLanguagesStore()
    const modules = useModulesStore()
    const preferences = usePreferencesStore()

    const initData = ref<InitResponse | null>(null)
    const isInit = ref(false)
    const initialLoading = ref(true)
    const isInstalled = ref(true)

    async function init() {
        const auth = useAuthStore()
        const api = useApi()
        try {
            const initResponse = await axios.get<InitResponse>('api/init')
            initData.value = initResponse.data
            auth.user = initResponse.data?.user ?? {}
            languages.languages = {
                app_strings: initResponse.data.languages?.app_strings ?? {},
                app_list_strings: initResponse.data.languages?.app_list_strings ?? {},
                modules: {},
            }
            languages.currentLanguage = initResponse.data.global?.default_language ?? 'en_us'
            modules.modulesDefs = initResponse.data?.modules ?? {}
            alerts.init()
            favorites.fetch()
            recents.fetch()
        } catch (err) {
            if ((err as AxiosError).response?.status === 401) {
                const loginData = (await api.get('api/login')).data
                languages.languages = {
                    app_strings: loginData.languages?.app_strings ?? {},
                    app_list_strings: loginData.languages?.app_list_strings ?? {},
                    modules: {
                        Users: loginData.languages?.Users ?? {},
                    },
                }
                preferences.global = loginData.global
                languages.currentLanguage = loginData.global?.default_language ?? 'en_us'
                if (router.currentRoute.value.meta?.auth !== false) {
                    router.push({ name: 'auth-login' })
                }
            } else if ((err as AxiosError).response?.status === 307) {
                isInstalled.value = false
                router.push({ name: 'install' })
            }
        } finally {
            isInit.value = true
            initialLoading.value = false
        }
    }

    return {
        init,
        initialLoading,
        isInit,
        initData,
        isInstalled,
    }
})
