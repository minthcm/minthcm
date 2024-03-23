import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios'

export interface Languages {
    app_strings: { [key: string]: string }
    app_list_strings: { [key: string]: { [key: string]: string } }
    modules: { [key: string]: { [key: string]: string } }
}

export const useLanguagesStore = defineStore('languages', () => {
    const currentLanguage = 'pl_PL'
    const languages = ref<Languages>({
        app_strings: {},
        app_list_strings: {},
        modules: {},
    })

    const label = computed(() => {
        return (lbl: string, module?: string) => {
            let label = ''
            if (module) {
                label = languages.value.modules?.[module]?.[lbl]
            }
            if (!label) {
                label = languages.value.app_strings?.[lbl]
            }
            return label || lbl
        }
    })

    async function fetchModuleLanguage(module: string) {
        if (languages.value.modules[module] && Object.keys(languages.value.modules[module]).length) {
            return languages.value.modules[module]
        }
        const response = await axios.get('api/languages', {
            params: {
                modules: module,
            },
        })
        if (response.data?.[module] && Object.keys(response.data[module]).length) {
            languages.value.modules[module] = response.data[module]
            return languages.value.modules[module]
        }
        return null
    }

    function getList(listKey?: string) {
        return Object.entries(languages.value?.app_list_strings?.[listKey ?? ''] ?? {}).map(
            (x: [string, string] | [object]) => ({ key: x[0], value: x[1] }),
        )
    }

    function translateListValue(value?: string, listKey?: string) {
        if (!value || !listKey) {
            return ''
        }
        return languages.value?.app_list_strings?.[listKey]?.[value]
            ? languages.value?.app_list_strings?.[listKey]?.[value]
            : value
    }

    return {
        currentLanguage,
        languages,
        label,
        fetchModuleLanguage,
        getList,
        translateListValue,
    }
})
