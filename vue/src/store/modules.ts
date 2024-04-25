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


interface CategoryDetails {
    [subcategory: string]: string[];
}

interface Categories {
    [category: string]: string[] | CategoryDetails;
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

    const categories: Categories = {
        "Tools": {
            "Calendar": ["Calendar", "Resource Calendar", "Reservations Calendar"],
            "Calls": ["Calls"],
            "Meetings": ["Meetings"],
            "Tasks": ["Tasks"],
            "Notes": ["Notes"],
            "Events": ["Events"],
            "Reservations": ["Reservations"],
        },
        "Recruitment": ["Candidates", "Candidatures", "Positions", "Recruitments", "Applications"],
        "Onboarding": ["Onboarding Templates", "Offboarding Templates", "Onboardings", "Offboardings"],
        "Employee Database": ["Documents", "Certificates", "Employee Certificates", "Skills", "Knowledge", "Contracts", "Periods of Employment", "Roles", "Responsibilities", "Competencies"],
        "Time & Attendance": ["Work Schedule", "Working Months", "Non-Working Days Registry"],
        "Benefits": ["Benefits"],
        "Performance Management": ["Goals", "Problems", "Improvements"],
        "Learning & Development": ["PDF Templates", "Advanced PDF Templates", "Knowledge Base", "KB Categories", "Training"],
        "Analytics & Reporting": ["Reports", "Advanced Reports", "Advanced Reports PDF Templates"],
        "Compliance": ["Terms of Employment", "Email Templates"],
        "News": ["News"],
        "Employee Relations": ["Surveys", "Exit Interviews"],
        "Operations": ["Workplaces", "Rooms", "Projects", "Project Templates", "Workflow", "Delegations", "Campaigns", "Locations", "Resources"]
    };

    const groupedModules = computed(() => {
        const grouped: { [key: string]: any } = {};
        Object.keys(categories).forEach(category => {
            const subcategories = categories[category];
            if (Array.isArray(subcategories)) {
                grouped[category] = subcategories.map(moduleName => {
                    const module = modules.value[moduleName];
                    return module ? module : { name: moduleName, label: moduleName, icon: defaultIcon, actions: [] };
                }).filter(module => module.name in modules.value);
            } else {
                grouped[category] = {};
                Object.keys(subcategories).forEach(subcategory => {
                    grouped[category][subcategory] = subcategories[subcategory].map(moduleName => {
                        const module = modules.value[moduleName];
                        return module ? module : { name: moduleName, label: moduleName, icon: defaultIcon, actions: [] };
                    }).filter(module => module.name in modules.value); 
                });
            }
        });
        return grouped;
    });


    return {
        modules,
        modulesDefs,
        defaultIcon,
        visibleModules,
        activeModule,
        groupedModules
    }
})
