<template>
    <div class="search-container">
        <v-text-field
            v-model="searchQuery"
            ref="searchInput"
            class="search-input"
            :class="[isFocused && 'search-input-active']"
            hide-details
            :placeholder="languages.label('LBL_MINT4_GS_SEARCH_INPUT')"
            @keyup.enter="goToFullList"
            variant="plain"
            @update:focused="isFocused = $event"
        >
            <template #prepend-inner>
                <v-fab-transition class="search-prepend-icon">
                    <v-icon v-if="searchQuery" icon="mdi-close" @click="searchQuery = ''" />
                    <v-icon v-else icon="mdi-magnify" />
                </v-fab-transition>
            </template>
        </v-text-field>
        <v-slide-y-transition>
            <template v-if="isFocused">
                <v-skeleton-loader v-if="isSearching" type="list-item-two-line" class="search-results" />
                <div v-else-if="!searchResponse?.results?.length" class="search-results">
                    <div
                        class="ma-4 text-caption"
                        v-text="
                            !searchResponse?.query || searchResponse.query.length < 3
                                ? languages.label('LBL_MINT4_GS_HELP_TIP')
                                : languages.label('LBL_MINT4_GS_NO_RECORDS_FOUND')
                        "
                    />
                </div>
                <div v-else-if="searchResponse?.results?.length" class="search-results">
                    <div
                        v-for="result in searchResponse.results"
                        :key="result.id"
                        color="primary"
                        v-ripple="{ class: 'text-primary' }"
                        @click="showRecord(result.module, result.id)"
                        class="search-result"
                    >
                        <v-icon :icon="modules.modules[result.module]?.icon || modules.defaultIcon" color="primary" />
                        <div>
                            <span v-html="getHighlightedText(result.name, searchResponse.query)" />
                            <div class="search-result-description">
                                <span v-text="getModuleName(result.module)" />
                                <span
                                    v-text="
                                        languages.label(result.meta?.def?.vname, result.module) +
                                        ' ' +
                                        getFieldValue(result.meta?.def, result.meta?.value)
                                    "
                                />
                            </div>
                        </div>
                    </div>
                    <div class="search-results-footer">
                        <span
                            @click="goToFullList"
                            v-text="
                                searchResponse.next_page_exists
                                    ? languages.label('LBL_MINT4_GS_GO_TO_LIST_MORE')
                                    : languages.label('LBL_MINT4_GS_GO_TO_LIST')
                            "
                        />
                    </div>
                </div>
            </template>
        </v-slide-y-transition>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useModulesStore } from '@/store/modules'
import { VSkeletonLoader } from 'vuetify/labs/VSkeletonLoader'
import he from 'he'
import axios from 'axios'
import { watch } from 'vue'
import { useLanguagesStore } from '@/store/languages'
import { DateTime } from 'luxon'

const modules = useModulesStore()
const languages = useLanguagesStore()
const router = useRouter()
const searchInput = ref<HTMLInputElement | null>(null)
const isFocused = ref(false)
const isSearching = ref(false)
const initialQuery = new URL(location.href).searchParams.get('query_string')
const searchQuery = ref<string | null>(initialQuery ?? '')
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

function getModuleName(module: string) {
    return languages.languages.app_list_strings?.moduleList?.[module] ?? ''
}
function getFieldValue(field_definition: object, value: string) {
    switch (field_definition.type) {
        case 'date':
            value = DateTime.fromSQL(value).toFormat('dd.MM.yyyy')
            break
        case 'datetimecombo':
        case 'datetime':
            value = DateTime.fromSQL(value, { zone: 'UTC' }).toLocal().toFormat('dd.MM.yyyy HH:mm')
            break
        case 'enum':
            value = languages.languages.app_list_strings[field_definition.options][value]
            break

        default:
            break
    }
    return value
}
interface SearchResult {
    id: string
    name: string
    module: string
    meta: {
        value: string
        def: object
    }
}

interface SearchResponse {
    query: string
    next_page_exists: boolean
    results: SearchResult[]
}

const searchResponse = ref<SearchResponse | null>(null)

async function search() {
    if (debounce.value) {
        clearTimeout(debounce.value)
    }
    if (standardizedQuery.value?.length >= 4) {
        isSearching.value = true
        try {
            const response = await axios.get('api/global_search', {
                params: {
                    query: standardizedQuery.value,
                },
            })
            searchResponse.value = response.data
        } finally {
            isSearching.value = false
        }
    }
}

function getHighlightedText(text: string, query: string) {
    query = he.encode(query)
    text = he.encode(text)
    if (!query) {
        return text
    }
    try {
        const regex = new RegExp(
            `\\b(${query
                .split(' ')
                .filter((q) => !['AND', 'OR'].includes(q) && q.length > 2)
                .join('|')})`,
            'gi',
        )
        text = text.replace(regex, '<span class="highlighted">$&</span>')
        return text
    } catch {
        return text
    }
}

function goToFullList() {
    if (standardizedQuery.value?.length >= 4) {
        router.push(`/modules/Home/UnifiedSearch?search_form=false&query_string=${searchQuery.value}`)
        searchQuery.value = ''
        searchInput.value?.blur()
    }
}

const debounce = ref<null | number>(null)
watch(searchQuery, (newVal) => {
    if (!newVal) {
        searchResponse.value = null
    } else {
        if (debounce.value) {
            clearTimeout(debounce.value)
        }
        debounce.value = setTimeout(() => {
            search()
        }, 500)
    }
})
</script>

<style scoped lang="scss">
.search-container {
    position: relative;
    flex-grow: 1;
    max-width: 50ch;
}

.search-input {
    position: relative;
    background: rgb(var(--v-theme-surface));
    z-index: 1;
    border-radius: 100px;
    transition: all 100ms ease-in-out;

    .search-prepend-icon {
        margin: 0px 10px;
        padding-top: 0px;
        top: -9px;
        opacity: 1;
        color: rgb(var(--v-theme-secondary));
    }

    :deep(.v-field__input) {
        padding-top: 0px;
    }

    &-active {
        background: rgb(var(--v-theme-primary-light));
    }
}

.search-results {
    position: absolute;
    padding: 48px 0px 16px 0px;
    width: 100%;
    left: 0px;
    top: 20px;
    background: rgb(var(--v-theme-surface));
    border-radius: 0px 0px 24px 24px;
    box-shadow: 0px 3px 6px #00000029;

    .search-results-footer {
        margin-top: 8px;
        text-align: center;

        span {
            font-size: 12px;
            color: rgb(var(--v-theme-secondary));
            text-decoration: underline;
            cursor: pointer;
        }
    }
}

.search-result {
    width: 100%;
    padding: 10px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 200ms ease-in-out;
    cursor: pointer;

    &:hover {
        background: rgb(var(--v-theme-primary-light));
    }

    :deep(.highlighted) {
        font-weight: 600;
        color: rgb(var(--v-theme-primary));
        background: rgb(var(--v-theme-primary-light));
    }

    .search-result-description {
        display: flex;
        gap: 32px;
        font-size: 12px;
        opacity: 0.7;
    }
}
</style>
