import { defineStore } from 'pinia';
import { useStorage } from '@vueuse/core';

interface ExpandedPanels {
    modules: {
        [module: string]: {
            [panel: string]: {
                [sections: string]: []
            }
        }
    }
}

interface DescriptionFieldExpanded {
    modules: {
        [module: string]: {
            [field: string]: boolean
        }
    }
}

export const useLocalStorageStore = defineStore('localStorage', () => {
    const expandedPanels = useStorage<ExpandedPanels>('app.panels.expanded', { modules: {} })
    const sideMenuShrinked = useStorage<boolean>('app.sidebar.shrinked', false)
    const descriptionFieldExpanded = useStorage<DescriptionFieldExpanded>('app.description.field.expanded', { modules: {}})

    function getPanelSections(module: string, panel: string): Array<number | string> {
        if (!expandedPanels.value.modules[module]) {
            expandedPanels.value.modules[module] = {}
        }
        if (!expandedPanels.value.modules[module][panel]) {
            expandedPanels.value.modules[module][panel] = { sections: [] }
        }
        return expandedPanels.value.modules[module][panel].sections
    }

    function setPanelSections(module: string, panel: string, sections: Array<number | string>) {
        if (!expandedPanels.value.modules[module]) {
            expandedPanels.value.modules[module] = {}
        }
        expandedPanels.value.modules[module][panel] = { sections }
    }

    function getDescriptionFieldExpanded(module: string, field: string): boolean {
        if (!descriptionFieldExpanded.value.modules[module]) {
            descriptionFieldExpanded.value.modules[module] = {}
        }
        if (!descriptionFieldExpanded.value.modules[module][field]) {
            descriptionFieldExpanded.value.modules[module][field] = false
        }
        return descriptionFieldExpanded.value.modules[module][field]
    }

    function setDescriptionFieldExpanded(module: string, field: string, expanded: boolean) {
        if (!descriptionFieldExpanded.value.modules[module]) {
            descriptionFieldExpanded.value.modules[module] = {}
        }
        descriptionFieldExpanded.value.modules[module][field] = expanded
    }

    function hasPanelSections(module: string, panel: string): boolean {
        return !!(expandedPanels.value.modules[module] && expandedPanels.value.modules[module][panel])
    }

    return {
        getPanelSections,
        setPanelSections,
        hasPanelSections,
        sideMenuShrinked,
        getDescriptionFieldExpanded,
        setDescriptionFieldExpanded
    }
})
