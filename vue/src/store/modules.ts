import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { useBackendStore } from './backend'
import { useUrlStore } from './url'
import { useLanguagesStore } from './languages'

/** backend defs */
export interface ModulesDefs {
    [name: string]: {
        name: string
        icon: string
        actions: ModuleAction[]
        acl: { [view: string]: number }
    }
}

export interface Modules {
    [name: string]: Module
}

export interface Module {
    name: string
    label: string
    icon: string
    actions: ModuleAction[]
    acl: { [view: string]: number }
}

export interface ModuleAction {
    name: string
    url: string
    action: string
    icon: string
}

export interface FieldVardef {
    name: string
    type: string
    label: string
    options?: string
    options_colors?: string
    default?: string
    readonly?: boolean
}

export const useModulesStore = defineStore('modules', () => {
    const backend = useBackendStore()
    const url = useUrlStore()
    const languages = useLanguagesStore()

    const modulesDefs = ref<ModulesDefs | null>(null)

    const defaultIcon = 'mdi-star'

    const modules = computed<Modules>(() => {
        const modules: Modules = {}
        if (!modulesDefs.value) {
            return modules
        }
        Object.values(modulesDefs.value).forEach((m) => {
            const label = languages.languages.app_list_strings['moduleList']?.[m.name] ?? m.name
            let icon = m.icon || defaultIcon
            if (m.icon?.slice(0, 4) !== 'mdi-') {
                icon = `mdi-${m.icon}`
            }
            modules[m.name] = {
                ...m,
                icon,
                label,
            }
        })
        return modules
    })

    const activeModule = computed(() => {
        return modules.value[url.module]
    })

    const visibleModules = computed(() => {
        return backend.initData?.menu_modules.map((moduleName) => modules.value[moduleName]) ?? []
    })

    return {
        modules,
        modulesDefs,
        defaultIcon,
        visibleModules,
        activeModule,
    }
})
