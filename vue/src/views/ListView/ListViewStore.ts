import { ref, computed, watch } from 'vue'
import { defineStore } from 'pinia'
import { useUrlStore } from '@/store/url'
import { useLanguagesStore } from '@/store/languages'
import { FilterRow } from './ListViewFilterRow.vue'
import { MassUpdateRow } from './ListViewMassUpdateRow.vue'
import { getAllTypesMatchingTo } from './operators'
import { useRouter } from 'vue-router'
import { usePopupsStore } from '@/store/popups'
import MintPopupRelate from '@/components/MintPopups/MintPopupRelate.vue'
import MassActions from '@/business/MassActions'
import { modulesApi } from '@/api/modules.api'
import * as operatorDefs from './operators'
import { useFavoritesStore } from '@/store/favorites'
import { useBean } from '@/composables/useBean'
import { useStatusBoxesStore } from '@/store/statusBoxes'
import { mintApi } from '@/api/api'
import { useBackendStore } from '@/store/backend'
import ComponentLoader from '@/utils/componentLoader'
import { decodeRecordStrings } from '@/utils/html'
import { useLocalStorageStore } from '@/store/localStorage'

interface Preferences {
    columns: string[]
    items_per_page: number
    saved_filters: []
    activeFilter: string | null
    filterRows: FilterRow[]
    sortParams: {
        sortOrder: 'asc' | 'desc'
        sortBy: string
    }
}

interface Defs {
    columns: object
    search: object
    massupdate: object
}

export type Mode = 'list' | 'relate'

interface MassAction {
    icon: string
    title: string
    onClick: () => void
}

export const useListViewStore = defineStore('listview', () => {
    const mode = ref<Mode>('list')
    const languages = useLanguagesStore()
    const url = useUrlStore()
    const router = useRouter()
    const favorites = useFavoritesStore()
    const isInit = ref(false)
    const config = ref({})
    const allSelected = ref(false)
    const defs = ref<Defs | null>(null)
    const preferences = ref<Preferences | null>(null)
    const module = ref(url.module)
    const results = ref<ReturnType<typeof useBean>[]>([])
    const itemsLength = ref(0)
    const initialLoading = ref(true)
    const isLoading = ref(true)
    const myObjects = ref(false)
    const onlyFavorites = ref(false)
    const activeFilter = ref<string | null>(null)
    const error = ref(false)
    const filters = ref({
        filter: [],
        must_not: [],
    })
    const pageOffsetMap = ref({})
    const searchPhrase = ref('')
    const options = ref({
        page: 1,
        itemsPerPage: 10,
        sortBy: new Array,
    })
    const selected = ref([])
    const defaultAction = 'ESList'
    const defaultActionUrl = 'legacy/index.php?'
    const filterRows = ref<FilterRow[]>([])
    
    const selectedOnPageCount = computed(() => {
        return results.value.map(r => r.id).filter(id => selected.value.map(s => s.id).includes(id)).length
    })

    const isHeaderChecked = computed(() => {
        if (allSelected.value) {
            return true
        } 

        return results.value.map(r => r.id).every(id => selected.value.map(s => s.id).includes(id))
    })

    const isHeaderIndeterminate = computed(() => {
        if (allSelected.value) {
            return true
        }
        return selectedOnPageCount.value > 0 && selectedOnPageCount.value < results.value.map(r => r.id).length
    })

    let currentRequestId = 0
    const predefinedFilters = ref<boolean>(false)
    const isMassUpdate = ref(false)

    const customActionsCache = ref(new Map<
        string,
        {
            icon: string
            onClick: (item: any) => void | Promise<void>
            hasAccess?: (item: any) => boolean
        }
    >())
    const loadingActions = ref(new Set<string>())
    const customActionsModules = import.meta.glob('@/custom/views/ListView/Actions/*.ts', { eager: false })
    
    // Computed that tracks cache size to trigger reactivity
    const actionsLoaded = computed(() => customActionsCache.value.size)

    async function init(filtersParam: FilterRow[] | null = null) {
        initialLoading.value = true
        clearCustomActionsCache()
        const result = await modulesApi.getListInit(getModule()).catch(moduleAccessError)
        if (module.value === result.data.module) {
            config.value = result.data?.config
            defs.value = result.data?.defs
            module.value = result.data?.module
            preferences.value = Array.isArray(result.data?.preferences) ? {} : result.data?.preferences
            
            await prepareInitFilters(filtersParam)

            const defaultSortBy = result.data?.defs?.defaultSort?.field ?? '';
            const defaultSortOrder = result.data?.defs?.defaultSort?.order ?? 'ASC';
            await prepareDefaultSort(defaultSortBy, defaultSortOrder);
            
            isInit.value = true
            getData()
        }
        initialLoading.value = false
    }

    async function prepareInitFilters(filtersParam: FilterRow[] | null = null) {
        if (Array.isArray(filtersParam)) {
            activeFilter.value = null
            filterRows.value = filtersParam
            predefinedFilters.value = true
            return;
        }

        const ls = useLocalStorageStore()
        const savedActiveName = ls.getModuleActiveFilter(getModule())
        const matchedFilter = (preferences.value?.saved_filters as any[])?.find((f: any) => f.name === savedActiveName)
        if (savedActiveName && matchedFilter) {
            activeFilter.value = savedActiveName
            filterRows.value = matchedFilter.filters ?? []
        } else {
            activeFilter.value = null
            filterRows.value = ls.getModuleFilters(getModule())
        }
    }

    async function prepareDefaultSort(defaultSortBy: string, defaultSortOrder: string)
    {
        const preferenceSortBy = preferences.value?.sortParams?.sortBy ?? '';
        const preferenceSortOrder = preferences.value?.sortParams?.sortOrder ?? '';
        if (preferenceSortOrder && preferenceSortBy !== '_score') {
            options.value.sortBy = [
                {
                    key: preferenceSortBy,
                    order: preferenceSortOrder,
                }
            ]
            return;
        }

        if (defaultSortBy && defaultSortOrder) {
            options.value.sortBy = [
                {
                    key: defaultSortBy,
                    order: defaultSortOrder.toLowerCase() as 'asc' | 'desc',
                }
            ]
        }
    }

    async function getData() {
        isMassUpdate.value = false
        const myRequestId = ++currentRequestId
        isLoading.value = true
        error.value = false

        const result = await modulesApi.getListData(
            getModule(),
            searchPhrase.value,
            filters.value,
            options.value.page ?? 0,
            options.value.itemsPerPage === -1 ? 100 : options.value.itemsPerPage,
            myObjects.value,
            options.value.sortBy[0]?.key ?? '',
            options.value.sortBy[0]?.order ?? 'asc',
            activeFilter.value,
            onlyFavorites.value,
        )
            .catch(moduleAccessError)
            .catch((requestError) => {
                if (myRequestId !== currentRequestId) return
                console.error('Error fetching data:', requestError?.response?.data || requestError)
                isLoading.value = false
                error.value = true
                results.value = []
            })

        if (myRequestId !== currentRequestId) return

        if (module.value === result?.data.module) {
            isLoading.value = false
            results.value = result.data?.results.map((item) => {
                const bean = useBean(item.module, item.id)
                bean.setData({ ...item, attributes: decodeRecordStrings(item.attributes) })
                return bean
            }) || []
            itemsLength.value = result.data?.total
            if (options.value.page === 1) {
                pageOffsetMap.value = {}
            }
            pageOffsetMap.value[options.value.page] = result.data?.offset ?? 0
        }
    }

    async function savePreferences() {
        await modulesApi.saveListPreferences(getModule(), preferences.value)
    }

    function moduleAccessError(error: any): Promise<any> {
        if (error.response.status === 403) {
            useStatusBoxesStore().showStatus('module_access_error', {
                type: 'error',
                message: useLanguagesStore().label('LBL_MINT4_NO_ACCESS_TO_MODULE'),
                autoClose: true,
            })
            router.push({ name: 'dashboard' })
        }
        return Promise.reject(error)
    }


    function getListActionUrl() {
        return defaultActionUrl + 'action=' + defaultAction
    }

    function setDefaultColumns() {
        if (preferences.value?.columns) {
            preferences.value.columns = []
        }
    }

    const allColumns = computed(() => {
        return Object.values(defs.value?.columns || {}).sort((a, b) => a.label?.localeCompare(b.label, 'pl'))
    })

    const visibleColumns = computed(() => {
        if (preferences.value?.columns && preferences.value?.columns.length) {
            // user preferences
            return preferences.value.columns.reduce((prev, curr) => {
                if (defs.value?.columns[curr]) {
                    return [...prev, defs.value?.columns[curr]]
                }
                return prev
            }, [])
        } else {
            // default
            return Object.values(defs.value?.columns || {}).filter((col) => col.default)
        }
    })

    const headers = computed(() => {
        if (!isInit.value) {
            return []
        }
        const headers = visibleColumns.value.map((col) => {
            if (col.name === 'favorites') {
                return {
                    value: 'attributes.is_favorite',
                    key: 'is_favorite',
                    title: '',
                    sortable: false,
                    align: 'center',
                }
            }
            return {
                value: `attributes.${col.name}`,
                key: col.name,
                title: languages.label(col.label, module.value),
                sortable: !(col.sortable === false),
                class: col.name == 'name' ? 'stickyColumn' : '',
            }
        })
        if (mode.value === 'list') {
            headers.push({
                value: 'actions',
                key: 'actions',
                title: languages.label('LBL_ESLIST_ACTIONS'),
                sortable: false,
                align: 'end',
            })
        }
        return headers
    })

    const filterableFields = computed(() => {
        return Object.values(defs.value?.search || {}).sort((a, b) => a.label?.localeCompare(b.label, 'pl'))
    })

    function addFilterRow() {
        filterRows.value.push({
            field: null,
            operator: null,
            inputs: [],
        })
    }

    function deleteFilterRow(index: number) {
        filterRows.value = filterRows.value.filter((filterRow, filterIndex) => index !== filterIndex)
    }

    const massUpdatableFields = computed(() => {
        let fields = []
        Object.values(defs.value?.massupdate || {}).forEach((field) => {
            if (
                (field.massupdate == undefined || field.massupdate == false) 
                || ['date_entered', 'date_modified', 'created_by', 'modified_by', 'favorites'].includes(field.name)
            ) {
                return
            }

            if (['parent_type'].includes(field.type)) {
                console.warn(`Mass update not supported for field type "${field.type}" (${field.name})`)
                return
            }

            if (field.massupdate) {
                fields.push(field)
            }
        })
        return fields
    })

    const massUpdateRows = ref<MassUpdateRow[]>([])
    function addMassUpdateRow() {
        massUpdateRows.value.push({
            field: null,
            inputs: []
        })
    }

    function deleteMassUpdateRow(index: number) {
        massUpdateRows.value = massUpdateRows.value.filter((filterRow, filterIndex) => index !== filterIndex)
    }

    function handleSelectRelate() {
        if (!relatePopup.value || !selected.value.length) {
            return
        }
        const selectionList: { [key: string]: string } = {}
        selected.value.forEach((item, index) => {
            selectionList[`ID_${index + 1}`] = item
        })
        relatePopup.value.data?.onConfirm({ selectionList })
        usePopupsStore().closePopup(relatePopup.value)
    }

    function selectAll() {
        allSelected.value = true
        selected.value = []
    }

    function clearAllSelection() {
        allSelected.value = false
        selected.value = []
    }

    const massActions = computed<MassAction[]>(() => {
        if (!isInit.value || !config.value?.config?.massActions?.length) {
            return []
        }
        const massActions: MassAction[] = []
        config.value.config.massActions.forEach((massAction) => {
            const actionClass = MassActions[massAction.action]
            if (!actionClass) {
                console.error('Mass action not found', massAction.action)
                return
            }
            massActions.push({
                icon: massAction.icon,
                title: languages.label(massAction.label, module.value),
                onClick: async () => {
                    const selectedIds = selected.value.map(item => item.id);
                    const result = await new actionClass(module.value, allSelected.value ? ['all'] : selectedIds, {
                        searchPhrase: searchPhrase.value,
                        filters: filters.value,
                        activeFilter: activeFilter.value,
                        myObjects: myObjects.value
                    }).execute()
                    if (result) {
                        selected.value = []
                        getData()
                    }
                },
            })
        })
        return massActions
    })

    function handleOptionsUpdate(event: { page: number; itemsPerPage: number }) {
        options.value.page = event.page
        options.value.itemsPerPage = event.itemsPerPage
    }

    function setSortBy(key: string) {
        const current = options.value.sortBy[0]
        options.value.sortBy = [{
            key,
            order: current?.key === key && current?.order === 'asc' ? 'desc' : 'asc',
        }]
    }

    let optionsWatchTimer: ReturnType<typeof setTimeout> | null = null
    watch(
        options,
        () => {
            if (isInit.value) {
                //getData()
                if (optionsWatchTimer) clearTimeout(optionsWatchTimer)
                optionsWatchTimer = setTimeout(() => getData(), 30)
            }
        },
        { deep: true },
    )

    const relatePopup = computed(() => {
        if (mode.value !== 'relate') {
            return null
        }
        return usePopupsStore().popups.find((popup) => popup.component === MintPopupRelate)
    })

    const itemsSelectable = computed(() => {
        return !!(
            (mode.value === 'list' && massActions.value.length) ||
            (mode.value === 'relate' &&
                relatePopup.value?.data?.popupMode &&
                relatePopup.value.data.popupMode !== 'single')
        )
    })

    function getModule() {
        return Array.isArray(module.value) ? module.value[0] : module.value
    }

    function replacePlaceholders(placeholders, inputs) {
        if (!inputs || !inputs.length) {
            return placeholders
        }
        let value = JSON.stringify(placeholders)
        inputs.forEach((input, i) => {
            if (value.includes(`"{${i}}"`)) {
                value = value.replaceAll(`"{${i}}"`, JSON.stringify(input.value))
            } else {
                value = value.replaceAll(`{${i}}`, input.value)
            }
        })
        return JSON.parse(value)
    }

    function isInputValid(input: any) {
        return (
            input.value &&
            (input.type !== 'date' || input.value.length === 10) && // todo: date format validation
            (input.type !== 'multiselect' || input.value.length)
        )
    }

    function getOperator(field: string, operator: string) {
        const type = defs.value?.search[field].type
        const fieldDefs =
            operatorDefs[type] ?? operatorDefs[operatorDefs.typeMap[type]] ?? operatorDefs[operatorDefs.defaultOperator]
        return fieldDefs[operator]
    }

    function isFilterRowValid(row: FilterRow) {
        if (!row.field || !row.operator) {
            return false
        }
        const operator = getOperator(row.field, row.operator)
        if (!operator) {
            return false
        }
        if (operator.inputs && row.inputs.some((input) => !isInputValid(input))) {
            return false
        }
        return true
    }

    function setFilters(filterRows: FilterRow[]) {
        const query = { filter: [], must_not: [] }
        filterRows.filter(isFilterRowValid).forEach((row) => {
            const operator = getOperator(row.field!, row.operator!)
            const filterType = operator.not ? 'must_not' : 'filter'
            const esKey = defs.value?.search[row.field].key
            operator.filters.forEach((f) => {
                const keyword_suffix = f.use_keyword_subfield ? '.keyword' : ''
                query[filterType].push({
                    [f.op]: {
                        [esKey + keyword_suffix]: replacePlaceholders(f.value, row.inputs),
                    },
                })
            })
        })
        const filtersChanged = JSON.stringify(query) !== JSON.stringify(filters.value)
        filters.value = query
        if (filtersChanged && isInit.value) {
            preferences.value.filterRows = filterRows
            if (mode.value === 'list') {
                savePreferences()
                useLocalStorageStore().setModuleFilters(getModule(), filterRows)
            }
            getData()
        }
    }

    watch(
        filterRows,
        (newFilterRows) => {
            newFilterRows.forEach((filterRow) => {
                if ((!filterRow.inputs || filterRow.inputs.length === 0) && filterRow.value) {
                    filterRow.inputs = buildFilterRowInputs(filterRow.field, filterRow.operator, filterRow.value)
                }
            })
            if (activeFilter.value) {
                preferences.value.activeFilter = activeFilter.value
            }
            setFilters(newFilterRows)
        },
        { deep: true },
    )

    function buildFilterRowInputs(field: string, operator: string, value: any) {
        const fieldDefs = defs.value?.search?.[field]
        if (!fieldDefs) {
            console.error('Field defs not found for field', field)
        }
        const type = fieldDefs.type
        const operators = operatorDefs[type] ?? operatorDefs[operatorDefs.typeMap[type]] ?? operatorDefs[operatorDefs.defaultOperator]
        return operators[operator].inputs.map((i, index) => ({
            type: i.type,
            value: filterValueMapper(value, index),
            label: languages.label(i.label),
            modifiers: i.modifiers ?? null,
        }))
    }

    function filterValueMapper(value: string | { lte: string, gte: string } | Array<string>, index: number) {
        if (typeof value === 'string' || Array.isArray(value)) {
            return value
        } else {
            return value[Object.keys(value)[index]]
        }
}
    function toggleFavorite(item) {
        if (!item.attributes.is_favorite) {
            favorites.addToFavorites(getModule(), item.id, item.name)
        } else {
            favorites.removeFromFavorites(getModule(), item.id)
        }
        item.attributes.is_favorite = !item.attributes.is_favorite
    }

    function setMassUpdate(value: boolean) {
        isMassUpdate.value = value
        if (!value) {
            massUpdateRows.value = []
        }
    }

    async function loadCustomAction(actionName: string) {
        const cacheKey = `${module.value}-${actionName}`
        
        if (customActionsCache.value.has(cacheKey)) {
            return customActionsCache.value.get(cacheKey)
        }

        if (loadingActions.value.has(cacheKey)) {
            return null
        }

        loadingActions.value.add(cacheKey)

        const loader = Object.entries(customActionsModules).find(([path]) => path.endsWith(`/${actionName}.ts`))?.[1]

        if (!loader) {
            loadingActions.value.delete(cacheKey)
            return null
        }

        try {
            const actionModule = (await loader()) as { default: (context: any) => any }
            const action = actionModule.default
            const backend = useBackendStore()
            const popups = usePopupsStore()
            const languages = useLanguagesStore()
            const resolvedAction = action({ 
                router, 
                store: useListViewStore(), 
                url, 
                languages, 
                popups, 
                backend, 
                ComponentLoader, 
                mintApi 
            })
            customActionsCache.value.set(cacheKey, resolvedAction)
            loadingActions.value.delete(cacheKey)
            return resolvedAction
        } catch (error) {
            console.error(`Failed to load custom action: ${actionName}`, error)
            loadingActions.value.delete(cacheKey)
            return null
        }
    }

    function clearCustomActionsCache() {
        customActionsCache.value.clear()
        loadingActions.value.clear()
    }

    return {
        mode,
        init,
        getData,
        isInit,
        config,
        preferences,
        defs,
        module,
        headers,
        filters,
        results,
        initialLoading,
        isLoading,
        options,
        itemsLength,
        myObjects,
        searchPhrase,
        filterableFields,
        visibleColumns,
        allColumns,
        activeFilter,
        savePreferences,
        setDefaultColumns,
        setFilters,
        pageOffsetMap,
        selected,
        filterRows,
        addFilterRow,
        deleteFilterRow,
        relatePopup,
        handleSelectRelate,
        itemsSelectable,
        massActions,
        predefinedFilters,
        error,
        toggleFavorite,
        onlyFavorites,
        setMassUpdate,
        isMassUpdate,
        massUpdateRows,
        addMassUpdateRow,
        deleteMassUpdateRow,
        massUpdatableFields,
        loadCustomAction,
        clearCustomActionsCache,
        customActionsCache,
        actionsLoaded,
        handleOptionsUpdate,
        setSortBy,
        allSelected,
        isHeaderChecked,
        isHeaderIndeterminate,
        selectedOnPageCount,
        selectAll,
        clearAllSelection
    }
})
