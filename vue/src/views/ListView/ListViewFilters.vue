<template>
    <v-row no-gutters class="filters-nav">
        <v-col :cols="$vuetify.display.lgAndDown ? 4 : 5">
            <v-text-field
                v-model="store.searchPhrase"
                class="filters-search"
                variant="plain"
                :placeholder="languages.label('LBL_MINT4_GS_SEARCH_INPUT')"
                prepend-inner-icon="mdi-magnify"
                @keyup.enter="handleSearchPhraseEnterKey"
                @input="updateOptionsDebounce"
                hide-details
            />
        </v-col>
        <v-switch v-model="store.myObjects" class="flex-grow-0" @change="store.getData" color="secondary" hide-details>
            <template #label>
                <span v-text="languages.label('LBL_ESLIST_MY_OBJECTS')"></span>
            </template>
        </v-switch>
        <MintButton
            variant="primary"
            icon="mdi-plus"
            :text="languages.label('LBL_ESLIST_ADD_FILTER')"
            @click="addFilterRow"
        />
        <MintButton
            icon="mdi-content-save-outline"
            :disabled="!filterRows.length"
            :text="languages.label('LBL_ESLIST_SAVE_FILTER')"
            @click="showSaveFilterPopup"
        />
        <v-select
            v-model="activeFilter"
            :items="store.preferences?.saved_filters"
            item-value="name"
            item-title="name"
            clearable
            class="flex-grow-1"
            variant="outlined"
            density="compact"
            :label="languages.label('LBL_ESLIST_SAVED_FILTERS')"
            :no-data-text="languages.label('LBL_ESLIST_SAVED_FILTERS_NO_DATA')"
            hide-details
        >
            <template #item="{ props }">
                <v-list-item :onClick="props.onClick">
                    <v-list-item-title class="d-flex">
                        <div>{{ props.title }}</div>
                        <v-btn
                            @click.stop="deleteSavedFilter(props.value)"
                            icon="mdi-delete"
                            variant="text"
                            density="compact"
                            class="ms-auto"
                            color="secondary-dark"
                        />
                    </v-list-item-title>
                </v-list-item>
            </template>
        </v-select>
    </v-row>
    <div v-if="filterRows.length" class="filters-rows">
        <ListViewFilterRow
            v-for="(row, index) in filterRows"
            v-model:field="row.field"
            @update:field="(newValue) => (row.field = newValue)"
            v-model:operator="row.operator"
            @update:operator="(newValue) => (row.operator = newValue)"
            v-model:inputs="row.inputs"
            @update:inputs="(newValue) => (row.inputs = newValue)"
            :key="row"
            :index="index"
            :row="row"
            @delete-filter-row="deleteFilterRow"
        />
    </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { storeToRefs } from 'pinia'
import MintButton from '@/components/MintButtons/MintButton.vue'
import { useListViewStore } from './ListViewStore'
import { useLanguagesStore } from '@/store/languages'
import ListViewFilterRow, { FilterRow } from './ListViewFilterRow.vue'
import * as operatorDefs from './operators'
import { usePopupsStore } from '@/store/popups'
import ListViewSaveFilterPopup from './ListViewSaveFilterPopup.vue'
import cloneDeep from 'lodash.clonedeep'

const store = useListViewStore()
const { activeFilter } = storeToRefs(useListViewStore())
const languages = useLanguagesStore()
const popups = usePopupsStore()

const searchPhraseDebounceTimer = ref<number | null>(null)

const filterRows = ref<FilterRow[]>([])

function updateOptionsDebounce() {
    if (searchPhraseDebounceTimer.value) {
        clearTimeout(searchPhraseDebounceTimer.value)
    }
    searchPhraseDebounceTimer.value = setTimeout(store.getData, 1000)
}

function handleSearchPhraseEnterKey() {
    if (searchPhraseDebounceTimer.value) {
        clearTimeout(searchPhraseDebounceTimer.value)
    }
    store.getData()
}

function addFilterRow() {
    filterRows.value.push({
        field: null,
        operator: null,
        inputs: [],
    })
}

function showSaveFilterPopup() {
    popups.showPopup({
        title: languages.label('LBL_ESLIST_SAVE_FILTER'),
        component: ListViewSaveFilterPopup,
        icon: 'mdi-content-save-outline',
        data: {
            filterRows,
        },
    })
}

function deleteFilterRow(index: number) {
    filterRows.value = filterRows.value.filter((filterRow, filterIndex) => index !== filterIndex)
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

function getOperator(field: string, operator: string) {
    const type = store.defs?.search[field].type
    const defs =
        operatorDefs[type] ?? operatorDefs[operatorDefs.typeMap[type]] ?? operatorDefs[operatorDefs.defaultOperator]
    return defs[operator]
}

function isInputValid(input) {
    return (
        input.value &&
        (input.type !== 'date' || input.value.length === 10) && // todo: date format validation
        (input.type !== 'multiselect' || input.value.length)
    )
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
        const esKey = store.defs?.search[row.field].key
        operator.filters.forEach((f) => {
            const keyword_suffix = f.use_keyword_subfield ? '.keyword' : ''
            query[filterType].push({
                [f.op]: {
                    [esKey + keyword_suffix]: replacePlaceholders(f.value, row.inputs),
                },
            })
        })
    })
    const filtersChanged = JSON.stringify(query) !== JSON.stringify(store.filters)
    store.filters = query
    if (filtersChanged) {
        store.getData()
    }
}

function deleteSavedFilter(filter: string) {
    store.preferences.saved_filters = store.preferences?.saved_filters.filter((f) => f.name !== filter)
    store.savePreferences()
}

watch(
    filterRows,
    (newFilterRows) => {
        setFilters(newFilterRows)
    },
    { deep: true },
)

watch(activeFilter, () => {
    filterRows.value = cloneDeep(
        store.preferences?.saved_filters?.find((f) => f.name === activeFilter.value)?.filters ?? [],
    )
})
</script>

<style scoped lang="scss">
.filters-nav {
    padding: 16px 16px 16px 16px;
    gap: 16px;
    align-items: center;
}

.filters-rows {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.filters-search {
    :deep(.v-field__prepend-inner) {
        padding-top: 12px;
        color: rgb(var(--v-theme-secondary));
    }
    :deep(.v-field__clearable) {
        padding-top: 12px;
        color: rgb(var(--v-theme-secondary));
    }
    :deep(.v-field__input) {
        padding-top: 0px;
    }
    :deep(.v-icon) {
        opacity: 1;
    }
}

.save-filter-item {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    width: 100%;

    & > div {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        width: 100%;
    }
}
</style>
