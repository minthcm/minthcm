import { ref, computed, watch } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios'
import { useUrlStore } from '@/store/url'
import { useBackendStore } from '@/store/backend'
import { useLanguagesStore } from '@/store/languages'

interface Preferences {
    columns: string[]
    items_per_page: number
    saved_filters: []
}

interface Defs {
    columns: object
    search: object
}

export const useListViewStore = defineStore('listview', () => {
    const languages = useLanguagesStore()
    const url = useUrlStore()
    const isInit = ref(false)
    const config = ref({})
    const defs = ref<Defs | null>(null)
    const preferences = ref<Preferences | null>(null)
    const module = ref('')
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

    async function init() {
        initialLoading.value = true
        const result = await axios.post('legacy/index.php?action=ESList', {
            module: url.module,
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
        isLoading.value = true
        const result = await axios.post('legacy/index.php?action=ESList', {
            module: url.module,
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
        isLoading.value = false
        results.value = result.data?.results
        itemsLength.value = result.data?.total
        if (options.value.page === 1) {
            pageOffsetMap.value = {}
        }
        pageOffsetMap.value[options.value.page] = result.data?.offset ?? 0
    }

    async function savePreferences() {
        const response = await axios.post('legacy/index.php?action=ESList', {
            module: module.value,
            preferences: preferences.value,
            function_name: 'savePreferences',
        })
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
        headers.push({
            value: 'actions',
            key: 'actions',
            title: languages.label('LBL_ESLIST_ACTIONS'),
            sortable: false,
            align: 'end',
        })
        return headers
    })

    const links = computed(() => {
        if (!isInit.value) {
            return {}
        }
        return Object.values(defs.value?.columns || {})
            .filter((col) => col.link)
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
            .filter((col) => col.type === 'enum' && col.options)
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

    watch(options, () => {
        getData()
    })

    return {
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
    }
})
