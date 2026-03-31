<template>
    <div>
        <div class="search-bar">
            <v-text-field
                v-model="searchQuery"
                variant="plain"
                @keyup.enter="search()"
                :placeholder="languages.label('LBL_MINT4_GS_SEARCH_INPUT')"
                :class="{
                    'search-bar-input': true,
                    'search-bar-input-active': isSearchBarFocused,
                }"
                @focus="isSearchBarFocused = true"
                @blur="isSearchBarFocused = false"
            />
            <v-btn @click="search()" class="search-button button primary">{{ languages.label('LBL_SEARCH') }}</v-btn>
        </div>
        <div class="list-view-mode-list">
            <h1 v-text="languages.label('LBL_SEARCH_REAULTS_TITLE')" />
            <div class="list-view-content">
                <v-data-table-server
                    class="list-table"
                    :headers="tableHeaders"
                    :items="searchResponse?.results || []"
                    :items-length="searchResponse?.total || 0"
                    :loading="isSearching"
                    fixed-header
                    must-sort
                    :no-data-text="languages.label('LBL_ESLIST_NO_DATA_AVAILABLE')"
                    @update:options="options = $event"
                >
                    <template v-slot:item.module="{ item }">
                        <v-tooltip :text="modules.modules[item.module].label" location="top">
                            <template #activator="{ props }">
                                <v-icon :icon="modules.modules[item.module].icon" class="module-icon" v-bind="props" />
                            </template>
                        </v-tooltip>
                    </template>
                    <template v-slot:item.name="{ item }">
                        <a @click="showRecord(item.module, item.id)" class="list-table-name-link">
                            {{ item.name }}
                        </a>
                    </template>
                    <template #bottom>
                        <VDataTableFooter
                            :items-per-page-options="itemsPerPageOptions"
                            v-bind:items-per-page-text="languages.label('LBL_ESLIST_ITEMS_PER_PAGE')"
                            :page-text="pageText"
                        />
                    </template>
                </v-data-table-server>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useLanguagesStore } from '@/store/languages'
import { useRouter } from 'vue-router'
import { ref, computed, watch, onMounted } from 'vue'
import { useModulesStore } from '@/store/modules'
import { unifiedSearchApi } from '@/api/unifiedSearch.api'
import { useBackendStore } from '@/store/backend'

interface SearchResponse {
    query: string
    next_page_exists: boolean
    results: SearchResult[]
}

interface SearchResult {
    id: string
    name: string
    module: string
    date_entered: string
    date_modified: string
    meta_array: {
        value: string
        label: string
    }
    meta: string
}

const languages = useLanguagesStore()
const router = useRouter()
const modules = useModulesStore()
const backend = useBackendStore()

const initialQuery = new URLSearchParams(location.href).get('query_string')
const searchQuery = ref<string | null>(initialQuery ?? '')
const isSearching = ref(false)
const isSearchBarFocused = ref(false)
const itemsPerPageOptions = computed(() => {
    let options = [5, 10, 20, 50, 100, 200, 500, 1000]
    return options.filter((option) => option <= (backend.initData.global.list_max_entries_per_page ?? 20))
})

const pageText = computed(() => {
    const pageText = `{0} - {1} ${languages.label('LBL_ESLIST_PAGE_TEXT')} {2}`
    return pageText
})

const options = ref({
    page: 1,
    itemsPerPage: 10,
    sortBy: [],
})

const tableHeaders = [
    {
        value: 'module',
        key: 'module',
        title: languages.label('LBL_UNIFIED_SEARCH_COLUMN_MODULE'),
        sortable: false,
    },
    {
        value: 'name',
        key: 'name',
        title: languages.label('LBL_UNIFIED_SEARCH_COLUMN_NAME'),
        sortable: false,
        class: 'stickyColumn',
    },
    {
        value: 'date_entered',
        key: 'date_entered',
        title: languages.label('LBL_UNIFIED_SEARCH_COLUMN_DATE_ENTERED'),
        sortable: false,
    },
    {
        value: 'date_modified',
        key: 'date_modified',
        title: languages.label('LBL_UNIFIED_SEARCH_COLUMN_DATE_MODIFIED'),
        sortable: false,
    },
    {
        value: 'meta',
        key: 'meta',
        title: '',
        sortable: false,
    },
]

const standardizedQuery = computed(() => {
    let query = searchQuery.value?.trim() ?? ''
    query
        .split(' ')
        .filter((q) => !['AND', 'OR'].includes(q) && !q.includes('*'))
        .forEach((q) => {
            query = query.replace(q, `${q}*`)
        })
    return query
})

onMounted(() => {
    search()
})

watch(options, () => {
    search()
})

function showRecord(module: string, id: string) {
    if (module && id) {
        router.push({
            name: 'module-view',
            params: {
                module,
                action: 'DetailView',
                record: id,
            },
        })
        searchQuery.value = ''
    }
}

const searchResponse = ref<SearchResponse | null>(null)

async function search() {
    isSearching.value = true
    try {
        const response = await unifiedSearchApi.globalSearch({
            query: standardizedQuery.value,
            itemsPerPage: options.value.itemsPerPage,
            page: options.value.page,
            isUnifiedSearch: true,
        })
        searchResponse.value = response.data
        searchResponse.value.results.forEach((item) => {
            const label = languages.label(item.meta_array.label, item.module)
            const colon = label && label.substr(label.length - 1) !== ':' ? ':' : ''
            item.meta = `${label}${colon} ${item.meta_array.value}`
        })
    } finally {
        isSearching.value = false
    }
}

</script>

<style scoped lang="scss">
h1 {
    letter-spacing: 1px;
    font-weight: 600;
}
.list-view-mode-list {
    padding: 8px 32px !important;

    .list-view-content {
        margin-top: 4px;
        border-radius: 16px;
        padding-bottom: 8px;
        background: rgb(var(--v-theme-surface));
        box-shadow: 0px 2px 4px -1px var(--v-shadow-key-umbra-opacity, rgba(0, 0, 0, 0.2)),
            0px 4px 5px 0px var(--v-shadow-key-penumbra-opacity, rgba(0, 0, 0, 0.14)),
            0px 1px 10px 0px var(--v-shadow-key-penumbra-opacity, rgba(0, 0, 0, 0.12));
    }
}

.list-table {
    a {
        text-decoration: none;
        color: rgb(var(--v-theme-secondary));
    }
    :deep(.v-pagination__first),
    :deep(.v-pagination__last) {
        display: none;
    }
    .list-table-name-link {
        cursor: pointer;
    }

    :deep(th:first-child) {
        width: 30px;
        text-align: end;
    }
    :deep(td:first-child) {
        width: 30px;
        text-align: end;
    }
}

.search-bar {
    margin-left: 32px;
    margin-top: 32px;
    display: flex;
    width: 40%;

    .search-bar-input {
        background: rgb(var(--v-theme-surface));
        border-radius: 24px;
        box-shadow: 0px 2px 4px -1px var(--v-shadow-key-umbra-opacity, rgba(0, 0, 0, 0.2)),
            0px 4px 5px 0px var(--v-shadow-key-penumbra-opacity, rgba(0, 0, 0, 0.14)),
            0px 1px 10px 0px var(--v-shadow-key-penumbra-opacity, rgba(0, 0, 0, 0.12));
        height: 48px;
        :deep(.v-field__input) {
            padding-top: 0px;
            padding-left: 16px !important;
        }
    }

    .search-bar-input-active {
        background: rgb(var(--v-theme-primary-light)) !important;
        transition: background 0.2s;
        border-radius: 24px;
    }
}

.search-button {
    border-radius: 100px;
    background: rgb(var(--v-theme-secondary));
    color: rgb(var(--v-theme-surface));
    margin-left: 16px;
    height: 48px;
    box-shadow: 0px 2px 4px -1px var(--v-shadow-key-umbra-opacity, rgba(0, 0, 0, 0.2)),
        0px 4px 5px 0px var(--v-shadow-key-penumbra-opacity, rgba(0, 0, 0, 0.14)),
        0px 1px 10px 0px var(--v-shadow-key-penumbra-opacity, rgba(0, 0, 0, 0.12));
}

.module-icon {
    color: rgb(var(--v-theme-secondary));
}
</style>