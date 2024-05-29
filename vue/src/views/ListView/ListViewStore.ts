import { ref, computed, watch } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios'
import { useUrlStore } from '@/store/url'
import { useBackendStore } from '@/store/backend'
import { useLanguagesStore } from '@/store/languages'
import { FilterRow } from './ListViewFilterRow.vue'
import { getAllTypesMatchingTo } from './operators'
import { useRouter } from 'vue-router'
import { usePopupsStore } from '@/store/popups'
import MintPopupRelate from '@/components/MintPopups/MintPopupRelate.vue'
import MassActions from '@/business/MassActions'

interface Preferences {
    columns: string[]
    items_per_page: number
    saved_filters: []
}

interface Defs {
    columns: object
    search: object
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
    const isInit = ref(false)
    const config = ref({})
    const defs = ref<Defs | null>(null)
    const preferences = ref<Preferences | null>(null)
    const module = ref(url.module)
    const results = ref([]) //todo: decode
    const itemsLength = ref(0)
    const initialLoading = ref(true)
    const isLoading = ref(true)
    const myObjects = ref(false)
    const activeFilter = ref<string | null>(null)
    const filters = ref({
        filter: [],
        must_not: [],
    })
    const pageOffsetMap = ref({})
    const searchPhrase = ref('')
    const options = ref({
        page: 1,
        itemsPerPage: 10,
        sortBy: [],
    })
    const selected = ref([])
    const defaultAction = 'ESList'
    const defaultActionUrl = 'legacy/index.php?'
    let requestCount = 0;

    async function init() {
        requestCount = 0;
        initialLoading.value = true
        const result = await axios.post(getListActionUrl(), {
            module: module.value,
            function_name: 'getInitialData',
        })
        initialLoading.value = false
        config.value = result.data?.config
        defs.value = result.data?.defs
        preferences.value = result.data?.preferences
        module.value = result.data?.module
        isInit.value = true
    }

    async function getData() {
        requestCount++;
        isLoading.value = requestCount > 0;
        const result = await axios.post(getListActionUrl(), {
            module: module.value,
            function_name: 'getResults',
            page: options.value.page,
            itemsPerPage: options.value.itemsPerPage === -1 ? 100 : options.value.itemsPerPage,
            myObjects: myObjects.value,
            searchPhrase: searchPhrase.value,
            filters: filters.value,
            offset: pageOffsetMap.value[options.value.page - 1],
            sortBy: defs.value?.columns[options.value.sortBy[0]?.key]?.key,
            sortOrder: options.value.sortBy[0]?.order ?? 'asc',
        })
        requestCount--;
        isLoading.value = requestCount > 0;
        results.value = result.data?.results
        itemsLength.value = result.data?.total
        if (options.value.page === 1) {
            pageOffsetMap.value = {}
        }
        pageOffsetMap.value[options.value.page] = result.data?.offset ?? 0
    }

    async function savePreferences() {
        const response = await axios.post(getListActionUrl(), {
            module: module.value,
            preferences: preferences.value,
            function_name: 'savePreferences',
        })
    }

    function getListActionUrl(){
        return defaultActionUrl + 'action=' + defaultAction
    }

    function setDefaultColumns() {
        if (preferences.value?.columns) {
            preferences.value.columns = null
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
        const headers = visibleColumns.value.map((col) => ({
            value: col.name,
            key: col.name,
            title: languages.label(col.label, module.value),
            sortable: !(col.sortable === false),
            class: col.name == 'name' ? 'stickyColumn' : '',
        }))
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

    const links = computed(() => {
        if (!isInit.value) {
            return {}
        }
        return Object.values(defs.value?.columns || {})
            .filter((col) => col.link && (!['name', 'full_name'].includes(col.name) || mode.value === 'list'))
            .map((col) => ({
                nameField: col.name,
                urlField: `${col.name}_link`,
            }))
    })
    const booleans = computed(() => {
        if (!isInit.value) {
            return {}
        }
        return Object.values(defs.value?.columns || {})
            .filter((col) => ['bool', 'boolean'].includes(col.type))
            .map((col) => col.name)
    })
    const lists = computed(() => {
        if (!isInit.value) {
            return {}
        }

        return Object.values(defs.value?.columns || {})
            .filter((col) => getAllTypesMatchingTo('enum').includes(col.type) && col.options)
            .map((col) => ({
                field: col.name,
                colors: languages.languages.app_list_strings[col.options + '_colored'],
                options:
                    typeof col.options === 'string' ? languages.languages.app_list_strings[col.options] : col.options,
            }))
    })
    const multienums = computed(() => {
        if (!isInit.value) {
            return {}
        }
        return Object.values(defs.value?.columns || {})
            .filter((col) => col.type === 'multienum' && col.options)
            .map((col) => ({
                field: col.name,
                options:
                    typeof col.options === 'string' ? languages.languages.app_list_strings[col.options] : col.options,
            }))
    })
    const dates = computed(() => {
        if (!isInit.value) {
            return {}
        }
        return Object.values(defs.value?.columns || {})
            .filter((col) => ['date', 'datetime', 'datetimecombo'].includes(col.type))
            .map((col) => ({
                field: col.name,
                style: col.custom_field_style?.list,
            }))
    })
    const currencies = computed(() => {
        if (!isInit.value) {
            return {}
        }
        return Object.values(defs.value?.columns || {})
            .filter((col) => ['currency'].includes(col.type))
            .map((col) => col.name)
    })
    const customFields = computed(() => {
        if (!isInit.value) {
            return {}
        }

        return {
            links: links.value,
            booleans: booleans.value,
            lists: lists.value,
            multienums: multienums.value,
            dates: dates.value,
            currencies: currencies.value,
        }
    })

    const filterableFields = computed(() => {
        return Object.values(defs.value?.search || {}).sort((a, b) => a.label?.localeCompare(b.label, 'pl'))
    })

    const filterRows = ref<FilterRow[]>([])

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

    function handleNameClick(item: any) {
        if (!item?.id) {
            return
        }
        if (mode.value === 'list') {
            const link = item.name_link ?? item.full_name_link
            if (!link) {
                return
            }
            router.push(url.fromLegacyUrl(link))
        } else if (mode.value === 'relate') {
            if (!relatePopup.value) {
                return
            }
            const nameToValueArray: { [key: string]: string } = {}
            for (const key in relatePopup.value.data.fieldToNameArray) {
                if (['full_name', 'name', 'last_name', 'first_name'].includes(key)) {
                    nameToValueArray[relatePopup.value.data.fieldToNameArray[key]] = item.full_name || item.name || item.last_name || item.first_name || ''
                } else if (!nameToValueArray[relatePopup.value.data.fieldToNameArray[key]] && key === 'subpanel_id') {
                    nameToValueArray[relatePopup.value.data.fieldToNameArray[key]] = item.id
                } else {
                    nameToValueArray[relatePopup.value.data.fieldToNameArray[key]] = item[key] ?? ''
                }
            }
            relatePopup.value.data?.onConfirm({ nameToValueArray })
            usePopupsStore().closePopup(relatePopup.value)
        }
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
                    const result = await new actionClass(module.value, selected.value).execute()
                    if (result) {
                        selected.value = []
                        getData()
                    }
                },
            })
        })
        return massActions
    })

    watch(options, () => {
        getData()
    })

    const relatePopup = computed(() => {
        if (mode.value !== 'relate') {
            return null
        }
        return usePopupsStore().popups.find((popup) => popup.component === MintPopupRelate)
    })

    const itemsSelectable = computed(() => {
        return !!(
            (mode.value === 'list' && massActions.value.length)
            || (mode.value === 'relate' && relatePopup.value?.data?.popupMode && relatePopup.value.data.popupMode !== 'single')
        )
    })

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
        customFields,
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
        pageOffsetMap,
        selected,
        filterRows,
        addFilterRow,
        deleteFilterRow,
        handleNameClick,
        handleSelectRelate,
        itemsSelectable,
        massActions,
    }
})
