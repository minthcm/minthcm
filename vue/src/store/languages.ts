import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { mintApi } from '@/api/api'

export interface Languages {
    app_strings: { [key: string]: string }
    app_list_strings: { [key: string]: { [key: string]: string } }
    modules: { [key: string]: { [key: string]: string } }
    [key: string]: any
}

interface Placeholders {
    [key: string]: string | number
}

export const useLanguagesStore = defineStore('languages', () => {
    const currentLanguage = 'en_us'
    const languages = ref<Languages>({
        app_strings: {},
        app_list_strings: {},
        modules: {},
    })
    const pendingRequests = ref<{ [key: string]: Promise<{ [key: string]: string } | null> | undefined }>({})

    const label = computed(() => {
        return (lbl: string, module?: string | null, placeholders?: Placeholders) => {
            let label = ''
            if (module) {
                if (!languages.value.modules?.[module]) {
                    fetchModuleLanguage(module)
                }
                label = languages.value.modules?.[module]?.[lbl]
            }
            if (!label) {
                label = languages.value.app_strings?.[lbl]
            }
            if (placeholders && label) {
                for (const [key, value] of Object.entries(placeholders)) {
                    label = label.replaceAll(`{${key}}`, value)
                }
            }
            return label || lbl
        }
    })

    function hasModule(module: string): boolean {
        return !!(languages.value.modules[module] && Object.keys(languages.value.modules[module]).length)
    }

    async function fetchModuleLanguage(module: string) {
        await fetchModulesLanguage([module])
        return languages.value.modules[module] ?? null
    }

    async function fetchModulesLanguage(modules: string[]) {
        const missing = [...new Set(modules)].filter((module) => module && !hasModule(module))
        if (!missing.length) {
            return
        }

        const alreadyPending = missing.filter((module) => pendingRequests.value[module])
        const toFetch = missing.filter((module) => !pendingRequests.value[module])

        let request: Promise<void> | null = null
        if (toFetch.length) {
            request = (async () => {
                try {
                    const response = await mintApi.get('languages', {
                        params: {
                            modules: toFetch.join(','),
                        },
                    })
                    for (const module of toFetch) {
                        if (response.data?.[module] && Object.keys(response.data[module]).length) {
                            languages.value.modules[module] = response.data[module]
                        }
                    }
                } catch {
                    // Leave modules without a cache entry so a later call retries them,
                    // instead of rejecting every pending request in this batch.
                }
            })()
            toFetch.forEach((module) => {
                pendingRequests.value[module] = request!.then(() => languages.value.modules[module] ?? null)
            })
            request.finally(() => {
                toFetch.forEach((module) => {
                    delete pendingRequests.value[module]
                })
            })
        }

        await Promise.all([
            request,
            ...alreadyPending.map((module) => pendingRequests.value[module]),
        ])
    }

    function getList(listKey?: string | Record<string, string>) {
        const list = typeof listKey === 'object' && listKey !== null
            ? listKey
            : languages.value?.app_list_strings?.[listKey ?? ''] ?? {}
        return Object.entries(list).map(
            (x: [string, string] | [object]) => ({ key: x[0], value: x[1] }),
        )
    }

    function translateListValue(value?: string, listKey?: string | Record<string, string>) {
        if (!listKey || value === undefined || value === null) {
            return ''
        }
        const list = typeof listKey === 'object' ? listKey : languages.value?.app_list_strings?.[listKey]
        return list?.[value] ? list[value] : value
    }

    return {
        currentLanguage,
        languages,
        label,
        hasModule,
        fetchModuleLanguage,
        fetchModulesLanguage,
        getList,
        translateListValue,
    }
})
