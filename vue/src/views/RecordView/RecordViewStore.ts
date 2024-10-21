import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { useRoute } from 'vue-router'
import { useModulesStore } from '@/store/modules'
import axios from 'axios'
import { useLanguagesStore } from '@/store/languages'

interface Panel {
    component: string
    key?: string
    data?: unknown
}

interface RecordViewDefs {
    order: string[]
    panels: { [key: string]: Panel }
}

export interface Bean {
    id: string
    module_name: string
    acl_access: { [key: string]: boolean }
    attributes: { [key: string]: any }
    syncAttributes: { [key: string]: any }
    dirtyFields: Set<string>
}

export const useRecordViewStore = defineStore('recordview', () => {
    const route = useRoute()

    const defs = computed<RecordViewDefs | []>(() => {
        const modules = useModulesStore()
        const module = modules.currentModule
        if (!module) {
            return []
        }
        const recordViewDefs = module.metadata.RecordView
        if (!recordViewDefs || typeof recordViewDefs !== 'object') {
            return []
        }
        return recordViewDefs
    })

    const bean = ref<Bean>({
        id: '',
        module_name: '',
        acl_access: {},
        attributes: {},
        syncAttributes: {},
        dirtyFields: new Set(),
    })

    const isBeanChanged = computed(() => {
        return Array.from(bean.value.dirtyFields).some((f) => bean.value.attributes[f] !== bean.value.syncAttributes[f])
    })

    function resetBean() {
        bean.value = {
            id: '',
            module_name: '',
            acl_access: {},
            attributes: {},
            syncAttributes: {},
            dirtyFields: new Set(),
        }
        subpanelsData.value = null
    }

    async function fetchBean() {
        const response = await axios.get(`api/${route.params.module}/Get/${route.params.id}`)
        if (response.status === 200 && response.data) {
            bean.value = {
                id: response.data.id,
                module_name: response.data.module_name,
                acl_access: response.data.acl_access,
                attributes: { ...response.data },
                syncAttributes: { ...response.data },
                dirtyFields: new Set(),
            }
        }
    }

    async function saveBean() {
        const response = await axios.patch(`api/${bean.value.module_name}/Update/${bean.value.id}`, {
            record_data: Object.fromEntries(
                Array.from(bean.value.dirtyFields)
                    .filter((f) => bean.value.attributes[f] !== bean.value.syncAttributes[f])
                    .map((f) => [f, bean.value.attributes[f]]),
            ),
        })

        if ([200, 201].includes(response.status) && response.data) {
            bean.value = {
                id: response.data.id,
                module_name: response.data.module_name,
                acl_access: response.data.acl_access,
                attributes: { ...response.data },
                syncAttributes: { ...response.data },
                dirtyFields: new Set(),
            }
        }
        return response
    }

    async function deleteBean() {
        return await axios.delete(`api/${bean.value.module_name}/${bean.value.id}`)
    }

    const view = ref<'detail' | 'edit' | 'list'>('detail')
    const inlineEditField = ref<string>('')
    const inlineEditFieldSaving = ref<string>('')

    const panels = computed(() => {
        let panels: Panel[] = []
        if (!defs.value?.panels || typeof defs.value.panels !== 'object') {
            return panels
        }
        if (!defs.value?.order || !defs.value.order.length) {
            panels = Object.values(defs.value?.panels ?? {})
        } else {
            panels = defs.value.order.map((key) => ({
                key,
                ...defs.value.panels[key],
            }))
        }
        return panels
    })

    //DEV: defs - (await axios.get('api/init')).data.modules.Candidates.metadata.Subpanels
    //DEV: data - (await axios.get('api/Candidates/subpanel/meetings/5ad6d7fd-e141-0a2c-4944-6449a8e50ad3')).data
    const subpanels = computed(() => {
        const modules = useModulesStore()
        const languages = useLanguagesStore()
        const module = modules.currentModule
        if (!module) {
            return []
        }
        const subpanelDefs = module.metadata.Subpanels
        if (!subpanelDefs || typeof subpanelDefs !== 'object') {
            return []
        }
        return Object.keys(subpanelDefs).map((key) => ({
            properties: subpanelDefs[key].properties,
            key,
            module: subpanelDefs[key].properties?.module?.toString() || '',
            label: subpanelDefs[key].properties?.title_key || '',
            columns: Object.entries(subpanelDefs[key].columns ?? {})
                .filter(([col, props]) => props.usage !== 'query_only')
                .map(([col, props]) => ({
                    ...(props || {}),
                    name: col,
                    label:
                        languages.label(props.label || '', subpanelDefs[key].properties?.module?.toString() || '') ||
                        '',
                    type: props.type || '',
                })),
            records: Object.keys(subpanelsData.value?.[key] ?? {}).map((id) => ({
                ...(subpanelsData.value?.[key][id] || {}),
                id,
                parent_module: subpanelDefs[key].properties?.module?.toString() || '',
            })),
        }))
    })

    async function fetchSubpanelsData() {
        const route = useRoute()
        const data = await Promise.all(
            subpanels.value.map((subpanel) =>
                axios.get(`api/${route.params.module}/subpanel/${subpanel.key}/${route.params.id}`, {
                    validateStatus: () => true,
                }),
            ),
        )
        subpanelsData.value = subpanels.value.reduce((prev, curr, index) => {
            prev[curr.key] = data[index]?.data
            return prev
        }, {} as SubpanelsData)
    }

    interface SubpanelsData {
        [key: string]: {
            [id: string]: {
                [property: string]: any
            }
        }
    }

    const subpanelsData = ref<SubpanelsData | null>(null)
    const columns = ref<number>(3)

    async function fetchLanguagesForSubpanels() {
        const languages = useLanguagesStore()
        subpanels.value.forEach((subpanel) => {
            languages.fetchModuleLanguage(subpanel.module)
        })
    }

    function updateField(field: string, additionalFields: string[]) {
        bean.value.dirtyFields.add(field)
        if (Array.isArray(additionalFields)) {
            additionalFields.forEach((field) => bean.value.dirtyFields.add(field))
        }
    }

    return {
        defs,
        view,
        inlineEditField,
        inlineEditFieldSaving,
        bean,
        isBeanChanged,
        resetBean,
        fetchBean,
        saveBean,
        deleteBean,
        panels,
        subpanels,
        fetchSubpanelsData,
        fetchLanguagesForSubpanels,
        columns,
        updateField,
    }
})
