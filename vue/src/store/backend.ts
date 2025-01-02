import { ref } from 'vue'
import { defineStore } from 'pinia'
import { useRouter } from 'vue-router'
import { useAuthStore, User } from './auth'
import { useAlertsStore } from './alerts'
import { useFavoritesStore } from './favorites'
import { useRecentsStore } from './recents'
import { useLanguagesStore, Languages } from './languages'
import axios, { AxiosError } from 'axios'
import { useModulesStore, ModulesDefs } from './modules'
import { usePreferencesStore } from './preferences'
import { Settings } from 'luxon'

interface QuickCreate {
    module: string
    name: string
}

interface LegacyView {
    list?: boolean
    record?: boolean
    popup?: boolean
}

interface InitResponse {
    user: User
    languages: Languages
    modules: ModulesDefs
    menu_modules: string[]
    quick_create: QuickCreate[]
    global: any
    legacy_views: { [module: string]: LegacyView }
    mintRebuildID: string
    responseType: string
    systemName: string
}
export const useBackendStore = defineStore('backend', () => {
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
    const cachedConfig = ref()

    async function init() {
        const auth = useAuthStore()
        try {
            if (typeof caches === "undefined") {
                console.warn('Cache API not supported.')
            } else {
                await caches.match('api/init').then(function(response) {
                    if (!response) {
                        return;
                    }
                    return response.json()
                }).then(function(response) {
                    if(cachedConfig){
                        cachedConfig.value = response;
                    }
                })
            }
            let mintRebuildID = cachedConfig.value?.mintRebuildID ?? '';
            const current_language = cachedConfig.value?.languages?.current_language ?? '';
            if(mintRebuildID === false){
                mintRebuildID = '';
            }
            const initResponse = await axios.post<InitResponse>('api/init', {
                mintRebuildID: mintRebuildID,
                current_language: current_language,
                user_id: cachedConfig.value?.user?.id ?? ''
            })
            auth.user = initResponse.data?.user ?? {}
            if(initResponse.data.responseType === 'minified'){
                cachedConfig.value.user = initResponse.data.user
                cachedConfig.value.global = initResponse.data.global
                cachedConfig.value.preferences = initResponse.data.preferences
                cachedConfig.value.responseType = initResponse.data.responseType
                cachedConfig.value.systemName = initResponse.data.system_name
                if(initResponse.data.languages && current_language !== initResponse.data.languages?.current_language){
                    cachedConfig.value.languages = initResponse.data.languages
                }
                if(initResponse.data.menu_modules){
                    cachedConfig.value.menu_modules = initResponse.data.menu_modules
                    cachedConfig.value.modules = initResponse.data.modules
                    cachedConfig.value.quick_create = initResponse.data.quick_create
                    cachedConfig.value.legacy_views = initResponse.data.legacy_views
                }
                if(initResponse.data?.acls){
                    for(let module_name in initResponse.data.acls){
                        cachedConfig.value.modules[module_name].acl = initResponse.data.acls[module_name]
                    }
                }
                initData.value = cachedConfig.value
            } else {
                initData.value = initResponse.data
            }
            languages.languages = {
                app_strings: initData.value.languages?.app_strings ?? {},
                app_list_strings: initData.value.languages?.app_list_strings ?? {},
                modules: {},
                current_language: initData.value.languages?.current_language ?? 'en_us'
            }
            Settings.defaultLocale = initData.value.user.preferences.language.split('_')[0] ?? 'en_us'
            languages.currentLanguage =
                localStorage.getItem('currentLang') ?? initData.value.global?.default_language ?? 'en_us'
            modules.modulesDefs = initData.value?.modules ?? {}
            if (typeof caches !== "undefined") {
                caches.open('mint-rebuild').then(function(cache) {
                    cache.put('api/init', new Response(JSON.stringify(initData.value)));
                })
            }
            alerts.init()
            favorites.fetch()
            recents.fetch()
            
        } catch (err) {
            if ((err as AxiosError).response?.status === 401) {
                const loginData = (
                    await axios.get('api/login', {
                        params: {
                            lang: localStorage.getItem('currentLang') ?? 'en_us',
                        },
                    })
                ).data
                languages.languages = {
                    app_strings: loginData.languages?.app_strings ?? {},
                    app_list_strings: loginData.languages?.app_list_strings ?? {},
                    modules: {
                        Users: loginData.languages?.Users ?? {},
                    },
                }
                preferences.global = loginData.global
                languages.currentLanguage =
                    localStorage.getItem('currentLang') ?? loginData.global?.default_language ?? 'en_us'
                if(window.location.href.search('/auth/reset') !== -1){
                    const token = window.location.hash.substring(1).split('?').reduce(function (previousValue, currentParam) {
                            const parts = currentParam.split('=');
                            previousValue[parts[0]] = parts[1];
                            return previousValue;
                        }, {} as any
                    )?.token;
                    router.push({ name: 'auth-reset', query: { token: token} })
                } else if (router.currentRoute.value.meta?.auth !== false) {
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
