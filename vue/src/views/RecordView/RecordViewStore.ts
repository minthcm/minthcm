import { ref, computed, unref } from 'vue'
import { defineStore } from 'pinia'
import { useRoute } from 'vue-router'
import { useModulesStore } from '@/store/modules'
import { useLanguagesStore } from '@/store/languages'
import { useBean } from '@/composables/useBean'
import { useACL } from '@/composables/useACL'
import { useBackendStore } from '@/store/backend'
import { useLocalStorageStore } from '@/store/localStorage'
import { MintInlineButton } from '@/components/MintPanel/MintPanelSubpanels/MintSubpanelsInlineButtons.vue'

interface Panel {
    component: string
    key?: string
    data?: unknown
}

interface RecordViewDefs {
    order: string[]
    panels: { [key: string]: Panel }
    sidepanel?: boolean | string[]
}

export interface Bean {
    id: string
    module_name: string
    acl_access: { [key: string]: boolean }
    attributes: { [key: string]: any }
    syncAttributes: { [key: string]: any }
    dirtyFields: Set<string>
}

interface RouteParams {
    module: string
    id: string
}

export const useRecordViewStore = defineStore('recordview', () => {
    const modulesStore = useModulesStore()
    const backendStore = useBackendStore()
    const route = useRoute()
    const acl = useACL()

    const defs = computed<RecordViewDefs>(() => {
        if (!modulesStore.currentModule) {
            return []
        }
        const recordViewDefs = modulesStore.currentModule.metadata.RecordView
        if (!recordViewDefs || typeof recordViewDefs !== 'object') {
            return []
        }
        return recordViewDefs
    })

    const module = computed(() => {
        if (typeof route.params.module === 'string') {
            return route.params.module
        }
        return ''
    })

    const recordId = computed(() => {
        if (typeof route.params.id === 'string') {
            return route.params.id
        }
        return ''
    })

    const bean = ref<ReturnType<typeof useBean>>(useBean(module.value, recordId.value))

    function resetBean() {
        abortSubpanelsFetch()
        bean.value.abort()
        bean.value = useBean(module.value, recordId.value)
        view.value = 'detail'
        editCycles.value = false
        hasCyclicRecords.value = false
        subpanelsData.value = null
        subpanelsFullyLoaded.value = {}
        subpanelsLoading.value = {}
        subpanelsInitialFetch.value = {}
    }

    const view = ref<'detail' | 'edit' | 'list'>('detail')
    const inlineEditField = ref<string>('')
    const inlineEditFieldSaving = ref<string>('')
    const editCycles = ref<boolean>(false)
    const hasCyclicRecords = ref<boolean>(false)

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

    //DEV: defs - (await mintApi.get('init')).data.modules.Candidates.metadata.Subpanels
    //DEV: data - (await mintApi.get('Candidates/subpanel/meetings/5ad6d7fd-e141-0a2c-4944-6449a8e50ad3')).data
    const subpanels = computed(() => {
        const module = modulesStore.currentModule
        if (!module) {
            return []
        }
        const subpanelDefs = module.metadata.Subpanels
        if (!subpanelDefs || typeof subpanelDefs !== 'object') {
            return []
        }
        return Object.keys(subpanelDefs)
            .filter((key) => {
                const moduleType = subpanelDefs[key].properties?.module?.toString() || ''
                return acl.hasAccess(moduleType, 'list', true, true)
            })
            .map((key) => ({
                properties: subpanelDefs[key].properties,
                key,
                module: subpanelDefs[key].properties?.module?.toString() || '',
                label: subpanelDefs[key].properties?.title_key || '',
                inlineButtons: Object.entries(subpanelDefs[key].columns ?? {})
                    .filter(([col, props]) => props.usage !== 'query_only' && !props?.type)
                    .map(
                        ([col, props]) =>
                            ({
                                ...(props || {}),
                                name: col,
                                widget_class: props?.widget_class || '',
                            } as MintInlineButton),
                    ),
                sortBy: subpanelDefs[key].properties?.sort_by || '',
                sortOrder: subpanelDefs[key].properties?.sort_order || '',
                filters:
                    subpanelDefs[key].properties?.filters && Object.keys(subpanelDefs[key].properties?.filters).length
                        ? subpanelDefs[key].properties?.filters
                        : {},
                // label is the raw language key here, not translated — translating it eagerly
                // for every column of every subpanel while building this list would call
                // languages.label() (and its per-module fetchModuleLanguage side effect) before
                // fetchLanguagesForSubpanels()'s batch request gets a chance to run, since that
                // batch itself has to read subpanels.value first to know which modules to fetch.
                // MintDataTable.vue already translates column.label lazily at render time.
                columns: Object.entries(subpanelDefs[key].columns ?? {})
                    .filter(([col, props]) => props.usage !== 'query_only')
                    .map(([col, props]) => ({
                        ...(props || {}),
                        name: col,
                        label: props.label || '',
                        type: props.type || '',
                    })),
                records: subpanelsData.value?.[key]?.records ?? [],
                page: subpanelsData.value?.[key]?.page ?? 0,
                total: subpanelsData.value?.[key]?.total ?? 0,
                paginateBy: backendStore.initData.global.list_max_entries_per_subpanel
                    ? parseInt(backendStore.initData.global.list_max_entries_per_subpanel, 10)
                    : 10,
            }))
    })

    const getSubpanelByKey = (subpanelKey: string) => {
        return subpanels.value.find((sp) => sp.key === subpanelKey)
    }

    const subpanelsFullyLoaded = ref<Record<string, boolean>>({})

    // One AbortController per in-flight subpanel fetch, keyed by subpanel key, so aborting one
    // (e.g. an on-expand fetchSubpanelRecords() superseding fetchSubpanelsData()'s initial fetch
    // for that same key) never drops the abort reference for any other subpanel's fetch.
    const subpanelsFetchAbortControllers = new Map<string, AbortController>()

    function abortSubpanelsFetch() {
        subpanelsFetchAbortControllers.forEach((controller) => controller.abort())
        subpanelsFetchAbortControllers.clear()
    }

    // Tracks the in-flight initial (collapsed, paginateBy=1) fetch per subpanel key, so that
    // expanding a panel while its initial fetch is still running can await it instead of firing
    // a second, overlapping fetchSubpanelRecords() call for the same key.
    const subpanelsInitialFetch = ref<Record<string, Promise<void> | undefined>>({})

    async function fetchOneSubpanel(subpanel: ReturnType<typeof getSubpanelByKey>, isExpanded: boolean, controller: AbortController) {
        if (!subpanel) return
        subpanelsLoading.value[subpanel.key] = true
        try {
            let link = bean.value.loadRelationship(subpanel.properties?.get_subpanel_data.toString())

            if (!link && subpanel.properties?.get_subpanel_data.toString().includes('function:')) {
                link = bean.value.createFakeLink(subpanel.properties?.get_subpanel_data.toString())
            }

            if (!link) return

            // Collapsed panels only need the total (badge/clickability) — paginateBy=1
            // runs the same backend COUNT(*) but returns 1 record instead of a full
            // page. Expanded panels still fetch full data as before.
            const paginateBy = isExpanded ? subpanel.paginateBy : 1
            await link.fetchRelatedRecords(subpanel.key, paginateBy, 0, subpanel.properties?.sortBy, subpanel.properties?.sortOrder, controller.signal)

            if (controller.signal.aborted) return

            if (!subpanelsData.value) {
                subpanelsData.value = {}
            }
            subpanelsData.value[subpanel.key] = {
                records: isExpanded ? link.beansArray : [],
                page: link.currentPage,
                total: link.total
            }
            subpanelsFullyLoaded.value[subpanel.key] = isExpanded
        } catch (error) {
            if (controller.signal.aborted) return
            throw error
        } finally {
            if (!controller.signal.aborted) {
                subpanelsLoading.value[subpanel.key] = false
            }
        }
    }

    async function fetchSubpanelsData() {
        if (!subpanels.value || subpanels.value.length === 0) return

        abortSubpanelsFetch()

        const storage = useLocalStorageStore()
        const expandedSubpanels = storage.getPanelSections(bean.value.module, 'MintPanelSubpanels')

        await Promise.all(
            subpanels.value.map((subpanel) => {
                const controller = new AbortController()
                subpanelsFetchAbortControllers.set(subpanel.key, controller)
                const isExpanded = expandedSubpanels.includes(subpanel.key)
                const promise = fetchOneSubpanel(subpanel, isExpanded, controller)
                subpanelsInitialFetch.value[subpanel.key] = promise
                return promise.finally(() => {
                    delete subpanelsInitialFetch.value[subpanel.key]
                    if (subpanelsFetchAbortControllers.get(subpanel.key) === controller) {
                        subpanelsFetchAbortControllers.delete(subpanel.key)
                    }
                })
            })
        )
    }

    async function fetchSubpanelRecords(subpanelKey: string, paginateBy: number, page: number, sortBy = '', sortOrder = '') {
        // If the collapsed-panel initial fetch for this key is still running, wait for it to
        // settle first instead of racing it — both writes target the same subpanelsData/
        // subpanelsFullyLoaded keys, and whichever finished last would silently win. Deliberately
        // NOT registering our own controller in subpanelsFetchAbortControllers yet: the initial
        // fetch's own controller is still the map's entry for this key at this point, so a
        // navigation-triggered abortSubpanelsFetch() during this await still cancels it (and, via
        // its aborted signal, the fetchRelatedRecords() call it's awaiting) instead of being
        // shadowed by an unrelated controller for a fetch that hasn't started yet.
        const initialFetchController = subpanelsFetchAbortControllers.get(subpanelKey)
        try {
            await subpanelsInitialFetch.value[subpanelKey]
        } catch (error) {
            // A real (non-abort) error in the initial fetch shouldn't block this fetch.
            console.error(error)
        }

        if (initialFetchController?.signal.aborted) {
            return
        }

        const controller = new AbortController()
        subpanelsFetchAbortControllers.set(subpanelKey, controller)

        const subpanel = getSubpanelByKey(subpanelKey)
        const getSubpanelData = subpanel?.properties?.get_subpanel_data?.toString()

        subpanelsLoading.value[subpanelKey] = true
        try {
            let link = bean.value.loadRelationship(getSubpanelData)
            if (!link && getSubpanelData?.includes('function:')) {
                link = bean.value.createFakeLink(getSubpanelData)
            }
            if (!link) {
                return
            }
            await link.fetchRelatedRecords(subpanelKey, paginateBy, page, sortBy || subpanel?.properties?.sortBy, sortOrder || subpanel?.properties?.sortOrder, controller.signal)

            if (controller.signal.aborted) {
                return
            }

            if (!subpanelsData.value) {
                subpanelsData.value = {}
            }
            subpanelsData.value[subpanelKey] = {
                records: link.beansArray,
                page: link.currentPage,
                total: link.total
            }
            subpanelsFullyLoaded.value[subpanelKey] = true
        } catch (error) {
            if (controller.signal.aborted) {
                return
            }
            throw error
        } finally {
            if (!controller.signal.aborted) {
                subpanelsLoading.value[subpanelKey] = false
            }
            if (subpanelsFetchAbortControllers.get(subpanelKey) === controller) {
                subpanelsFetchAbortControllers.delete(subpanelKey)
            }
        }
    }

    interface SubpanelsData {
        [key: string]: {
            records: ReturnType<typeof useBean>[]
            page: any
            total: any
        }
    }

    const subpanelsData = ref<SubpanelsData | null>(null)
    const subpanelsLoading = ref<Record<string, boolean>>({})

    const loadingSubpanels = ref<Map<string, number>>(new Map())

    function setSubpanelLoading(key: string, count: number) {
        const newMap = new Map(loadingSubpanels.value)
        if (count > 0) {
            newMap.set(key, count)
        } else {
            newMap.delete(key)
        }
        loadingSubpanels.value = newMap
    }

    const skeletonRecordIds = ref<Map<string, Set<string>>>(new Map())

    function addSkeletonRecord(subpanelKey: string, recordId: string) {
        const newMap = new Map(skeletonRecordIds.value)
        const ids = new Set(newMap.get(subpanelKey) ?? [])
        ids.add(recordId)
        newMap.set(subpanelKey, ids)
        skeletonRecordIds.value = newMap
    }

    function removeSkeletonRecord(subpanelKey: string, recordId: string) {
        const newMap = new Map(skeletonRecordIds.value)
        const ids = new Set(newMap.get(subpanelKey) ?? [])
        ids.delete(recordId)
        if (ids.size === 0) {
            newMap.delete(subpanelKey)
        } else {
            newMap.set(subpanelKey, ids)
        }
        skeletonRecordIds.value = newMap
    }

    async function fetchLanguagesForSubpanels() {
        const languages = useLanguagesStore()
        const modules = subpanels.value.map((subpanel) => subpanel.module).filter(Boolean)
        await languages.fetchModulesLanguage(modules)
    }

    function updateField(field: string, value: any, additionalFields: { [fieldName: string]: any }) {
        const updatedValues = { [field]: value, ...(additionalFields || {}) }
        bean.value.updateFields(updatedValues)
    }

    const sidepanelWidgets = computed<string[]>(() => {
        if (defs.value && !Array.isArray(defs.value) && Array.isArray(defs.value.sidepanel)) {
            return defs.value.sidepanel
        }
        return []
    })

    const hasSidepanel = computed(() => sidepanelWidgets.value.length > 0 && !unref(bean.value.isNew))

    return {
        defs,
        view,
        editCycles,
        hasCyclicRecords,
        inlineEditField,
        inlineEditFieldSaving,
        bean,
        resetBean,
        panels,
        subpanels,
        fetchSubpanelsData,
        fetchLanguagesForSubpanels,
        updateField,
        fetchSubpanelRecords,
        loadingSubpanels,
        setSubpanelLoading,
        skeletonRecordIds,
        addSkeletonRecord,
        removeSkeletonRecord,
        subpanelsLoading,
        hasSidepanel,
        sidepanelWidgets,
        subpanelsFullyLoaded,
    }
})
