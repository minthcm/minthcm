<template>
    <v-row no-gutters class="filters-nav">
        <v-col :cols="$vuetify.display.lgAndDown ? 4 : 5" class="filters-search-wrapper">
            <v-text-field
                v-model="store.searchPhrase"
                class="filters-search"
                :class="{ 'filters-search-active': isFocused }"
                variant="plain"
                :placeholder="languages.label('LBL_MINT4_GS_SEARCH_INPUT')"
                @input="updateOptionsDebounce"
                @keyup.enter="searchByPhrase"
                @update:focused="isFocused = $event"
                hide-details
            >
                <template #prepend-inner>
                    <v-fab-transition>
                        <v-icon v-if="store.searchPhrase" icon="mdi-close" @click="clearInput" />
                        <v-icon v-else icon="mdi-magnify" />
                    </v-fab-transition>
                </template>
            </v-text-field>
            <v-slide-y-transition>
                <div v-if="isFocused && !store.searchPhrase" class="filters-search-tip">
                    <span class="text-caption" v-text="languages.label('LBL_MINT4_GS_HELP_TIP')" />
                </div>
            </v-slide-y-transition>
        </v-col>
        <v-switch 
            v-model="store.myObjects" 
            class="flex-grow-0" 
            @change="store.getData" 
            color="secondary" 
            hide-details
            @keydown.space.prevent="toggleMyObjects"
            @keydown.enter.prevent="toggleMyObjects"
        >
            <template #label>
                <span v-text="languages.label('LBL_ESLIST_MY_OBJECTS')"></span>
            </template>
        </v-switch>
        <v-switch
            v-model="store.onlyFavorites"
            class="flex-grow-0"
            @change="store.getData"
            color="secondary"
            hide-details
            @keydown.space.prevent="toggleFavorites"
            @keydown.enter.prevent="toggleFavorites"
        >
            <template #label>
                <span v-text="languages.label('LBL_ESLIST_MY_FAVORITES')"></span>
            </template>
        </v-switch>
        <MintButton
            icon="mdi-content-save-outline"
            :disabled="!filterRows.length || store.predefinedFilters"
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
            :disabled="store.predefinedFilters"
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
    <div v-if="filterRows?.length" class="filters-rows">
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
        />
    </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { storeToRefs } from 'pinia'
import MintButton from '@/components/MintButtons/MintButton.vue'
import { useListViewStore } from './ListViewStore'
import { useLanguagesStore } from '@/store/languages'
import ListViewFilterRow from './ListViewFilterRow.vue'
import { usePopupsStore } from '@/store/popups'
import ListViewSaveFilterPopup from './ListViewSaveFilterPopup.vue'
import cloneDeep from 'lodash.clonedeep'
import { useLocalStorageStore } from '@/store/localStorage'

const store = useListViewStore()
const { activeFilter, filterRows } = storeToRefs(useListViewStore())
const languages = useLanguagesStore()
const popups = usePopupsStore()
const searchPhraseDebounceTimer = ref<number | null>(null)
const isFocused = ref(false)

const toggleFavorites = () => {
    store.onlyFavorites = !store.onlyFavorites
    store.getData()
}

const toggleMyObjects = () => {
    store.myObjects = !store.myObjects
    store.getData()
}

function updateOptionsDebounce() {
    if (searchPhraseDebounceTimer.value) {
        clearTimeout(searchPhraseDebounceTimer.value)
    }
    searchPhraseDebounceTimer.value = setTimeout(store.getData, 1000)
}

function searchByPhrase() {
    if (searchPhraseDebounceTimer.value) {
        clearTimeout(searchPhraseDebounceTimer.value)
    }
    store.clearAllSelection()
    store.getData()
}

function showSaveFilterPopup() {
    let myObjects = store.myObjects
    popups.showPopup({
        title: languages.label('LBL_ESLIST_SAVE_FILTER'),
        component: ListViewSaveFilterPopup,
        icon: 'mdi-content-save-outline',
        data: {
            filterRows: cloneDeep(filterRows.value),
            myObjects,
        },
    })
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
        (input.type !== 'multiselect' || input.value.length) &&
        (input.type !== 'multirelate' || input.value.length)
    )
}

function clearInput() {
    store.searchPhrase = ''
    searchByPhrase()
}

function deleteSavedFilter(filter: string) {
    store.preferences.saved_filters = store.preferences?.saved_filters.filter((f) => f.name !== filter)
    filterRows.value = []
    activeFilter.value = null
    store.preferences.deleteActiveFilter = true
    useLocalStorageStore().setModuleActiveFilter(store.module, null)
    store.savePreferences()
}

watch(activeFilter, () => {
    store.preferences.activeFilter = activeFilter?.value
    if(!activeFilter.value){
        store.preferences.deleteActiveFilter = true
    }
    useLocalStorageStore().setModuleActiveFilter(store.module, activeFilter.value)
    store.savePreferences()
    filterRows.value = cloneDeep(
        store.preferences?.saved_filters?.find((f) => f.name === activeFilter.value)?.filters ?? [],
    )
    store.myObjects = store.preferences?.saved_filters?.find((f) => f.name === activeFilter.value)?.myObjects ?? false
    store.clearAllSelection()
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
}

.filters-search-wrapper {
    position: relative;
}

.filters-search {
    border-radius: 8px;
    transition: background 100ms ease-in-out;

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

    &.filters-search-active {
        background: rgb(var(--v-theme-primary-light));
    }
}

.filters-search-tip {
    position: absolute;
    width: 100%;
    left: 0;
    top: 100%;
    background: rgb(var(--v-theme-surface));
    border-radius: 0 0 8px 8px;
    box-shadow: 0px 3px 6px #00000029;
    z-index: 10;
    padding: 8px 16px;
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

.filters-nav {
    :deep(.mint-button:focus-visible) {
        outline: 2px solid rgb(var(--v-theme-secondary-dark));
        outline-offset: 2px;
    }
}
</style>