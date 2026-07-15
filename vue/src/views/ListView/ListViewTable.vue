<template>
    <v-data-table-server
        class="list-table sticky-columns"
        :style="{
            minHeight: store.mode === 'relate' ? 'calc(100vh - 300px)' : 0,
        }"
        :headers="store.headers"
        :items="store.results"
        :items-length="store.itemsLength || 0"
        :loading="store.isLoading || store.initialLoading"
        fixed-header
        must-sort
        :show-select="store.itemsSelectable"
        v-model="store.selected"
        v-model:sort-by="store.options.sortBy"
        @update:options="store.options = $event"
        :no-data-text="
            store.error
                ? languages.label('LBL_ESLIST_FETCHING_DATA_ERROR')
                : languages.label('LBL_ESLIST_NO_DATA_AVAILABLE')
        "
        hover
    >
        <template #header.data-table-select="{}">
            <div class="table-checkbox-wrapper">
                <v-checkbox
                    :model-value="store.isHeaderChecked"
                    :indeterminate="store.isHeaderIndeterminate"
                    @update:model-value="toggleHeader"
                />
            </div>
        </template>
        <template #item.data-table-select="{ item }">
            <div class="table-checkbox-wrapper">
                <v-checkbox
                    :model-value="isRowSelected(item)"
                    @update:model-value="toggleRow(item)"
                />
            </div>
        </template>

        <template
            v-for="header in store.headers.filter(h => h.sortable !== false)"
            :key="header.key"
            v-slot:[`header.${header.key}`]="{ column, toggleSort, isSorted, getSortIcon }"
        >
            <span
                tabindex="0"
                role="button"
                :aria-sort="isSorted(column) ? (getSortIcon(column) === 'mdi-arrow-up' ? 'ascending' : 'descending') : 'none'"
                @click="toggleSort(column)"
                @keydown.enter.prevent="toggleSort(column)"
                @keydown.space.prevent="toggleSort(column)"
                style="cursor: pointer;"
            >
                {{ column.title }}
                <v-icon v-if="isSorted(column)" size="small">
                    {{ getSortIcon(column) }}
                </v-icon>
            </span>
        </template>
        <template v-slot:item.is_favorite="{ item }">
            <v-icon
                color="secondary"
                :icon="item.attributes.is_favorite ? 'mdi-heart' : 'mdi-heart-outline'"
                @click="store.toggleFavorite(item)"
                @keydown.enter.prevent="store.toggleFavorite(item)"
                @keydown.space.prevent="store.toggleFavorite(item)"
                tabindex="0"
                role="button"
                :aria-label="item.attributes.is_favorite ? 'Remove from favorites' : 'Add to favorites'"
                size="small"
                class="favorite-icon"
                v-ripple
            />
        </template>
        <template v-for="column in store.visibleColumns" v-slot:[`item.${column.name}`]="{ item }" :key="column.name">
            <span
                v-if="store.mode === 'relate'"
                tabindex="0"
                @keydown.enter.prevent="chooseRecord(item)"
                @keydown.space.prevent="chooseRecord(item)"
                style="cursor: pointer"
            >
                <Field
                    view="list"
                    :defs="
                        column.name === 'name'
                            ? Object.assign(store.defs.columns[column.name], { type: 'name' })
                            : store.defs.columns[column.name]
                    "
                    :data="{ bean: item }"
                    :label="languages.label(store.defs.columns[column.name].label, store.module)"
                    :options="item.logic.fieldsOptions[column.name]"
                    :modelValue="item.attributes[column.name]"
                    :field="item.fields[column.name]"
                />
            </span>
            <Field
                v-else
                view="list"
                :defs="
                    column.name === 'name'
                        ? Object.assign(store.defs.columns[column.name], { type: 'name' })
                        : store.defs.columns[column.name]
                "
                :data="{ bean: item }"
                :label="languages.label(store.defs.columns[column.name].label, store.module)"
                :options="item.logic.fieldsOptions[column.name]"
                :modelValue="item.attributes[column.name]"
                :field="item.fields[column.name]"
                tabindex="0"
            />
        </template>
        <template v-slot:item.actions="{ item }">
            <div class="d-flex justify-end" style="gap: 8px">
                <v-tooltip
                    v-for="(action, index) in getItemActions(item)"
                    v-show="action.icon"
                    :key="`${action.icon}-${index}`"
                    location="top"
                >
                    <template v-slot:activator="{ props }">
                        <v-btn
                            icon
                            variant="text"
                            v-bind="props"
                            @click="action.onClick(item)"
                            @keydown.space.prevent="action.onClick(item)"
                            @keydown.enter.prevent="action.onClick(item)"
                        >
                            <v-icon size="small" color="secondary" :icon="action.icon"/>
                        </v-btn>
                    </template>
                    <span>{{ action.label ? languages.label(action.label, url.module) : '' }}</span>
                </v-tooltip>
            </div>
        </template>
        <template v-slot:bottom>
            <div class="v-data-table-footer">
                <div class="v-data-table-footer__items-per-page">
                    <span class="v-data-table-footer__items-per-page-text">
                        {{ languages.label('LBL_ESLIST_ITEMS_PER_PAGE') }}
                    </span>
                    <v-select
                        :items="store.config?.config?.itemsPerPageOptions || [10, 25, 50, 100]"
                        v-model="store.options.itemsPerPage"
                        density="compact"
                        variant="outlined"
                        hide-details
                    />
                </div>
                
                <div class="v-data-table-footer__info">
                    <div class="v-data-table-footer__page-text">{{ pageText }}</div>
                </div>
                
                <div class="v-data-table-footer__pagination">
                    <v-btn
                        icon="mdi-page-first"
                        variant="text"
                        :disabled="store.options.page === 1"
                        @click="store.options.page = 1"
                        density="comfortable"
                    />
                    <v-btn
                        icon="mdi-chevron-left"
                        variant="text"
                        :disabled="store.options.page === 1"
                        @click="store.options.page--"
                        density="comfortable"
                    />
                    <v-btn
                        icon="mdi-chevron-right"
                        variant="text"
                        :disabled="store.options.page >= totalPages"
                        @click="store.options.page++"
                        density="comfortable"
                    />
                    <v-btn
                        icon="mdi-page-last"
                        variant="text"
                        :disabled="store.options.page >= totalPages"
                        @click="store.options.page = totalPages"
                        density="comfortable"
                    />
                </div>
            </div>
        </template>
    </v-data-table-server>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useListViewStore } from './ListViewStore'
import { useLanguagesStore } from '@/store/languages'
import { useUrlStore } from '@/store/url'
import { usePopupsStore } from '@/store/popups'
import { mintApi } from '@/api/api'
import Field from '@/components/Fields/Field.vue'

const router = useRouter()
const store = useListViewStore()
const url = useUrlStore()
const languages = useLanguagesStore()
const popups = usePopupsStore()

const totalPages = computed(() => {
    return Math.ceil((store.itemsLength || 0) / (store.options.itemsPerPage || 10))
})

const pageText = computed(() => {
    const start = ((store.options.page - 1) * store.options.itemsPerPage) + 1
    const end = Math.min(store.options.page * store.options.itemsPerPage, store.itemsLength || 0)
    const total = store.itemsLength || 0
    
    return `${start} - ${end} ${languages.label('LBL_ESLIST_PAGE_TEXT')} ${total}`
})

function chooseRecord(item) {
    if (!store.relatePopup) return
    if (store.relatePopup.data.fieldToNameArray) {
        const nameToValueArray: { [key: string]: string } = {}
        for (const key in store.relatePopup.data.fieldToNameArray) {
            if (['full_name', 'name', 'last_name', 'first_name'].includes(key)) {
                nameToValueArray[store.relatePopup.data.fieldToNameArray[key]] =
                    item.attributes?.full_name || item.attributes?.name || item.attributes?.last_name || item.attributes?.first_name || ''
            } else if (!nameToValueArray[store.relatePopup.data.fieldToNameArray[key]] && key === 'subpanel_id') {
                nameToValueArray[store.relatePopup.data.fieldToNameArray[key]] = item.id
            } else {
                nameToValueArray[store.relatePopup.data.fieldToNameArray[key]] = item.attributes?.[key] ?? ''
            }
        }
        store.relatePopup.data?.onConfirm({ nameToValueArray })
    } else {
        store.relatePopup.data?.onConfirm({ selectionList: [item.id || ''] })
    }
    popups.closePopup(store.relatePopup)
}

const coreActions = {
    edit: {
        icon: 'mdi-pencil',
        label: 'LBL_EDIT_BUTTON',
        onClick: (item) => router.push(`/modules/${url.module}/EditView/${item.id}`),
    },
    view: {
        icon: 'mdi-eye',
        label: 'LBL_VIEW_BUTTON',
        onClick: (item) => router.push(`/modules/${url.module}/DetailView/${item.id}`),
    },
    delete: {
        icon: 'mdi-delete',
        label: 'LBL_DELETE_BUTTON',
        onClick: async (item) => {
            const confirmMessage = `${languages.label('LBL_ESLIST_DELETE_RECORD_CONFIRM_BODY')} ${item.name}?`
            if (await popups.confirm(confirmMessage)) {
                await mintApi.delete(`${url.module}/${item.id}`)
                store.getData()
            }
        },
    },
}

function toggleHeader(value: boolean) {
    if (value) {
        const idsOnPage = store.results
        store.selected = [
        ...store.selected.filter(s => !idsOnPage.some(i => i.id === s.id)),
        ...idsOnPage
        ]
    } else {
        const idsOnPage = store.results.map(r => r.id)
        store.selected = store.selected.filter(s => !idsOnPage.includes(s.id))
    }
}

function isRowSelected(item: any) {
    if (store.allSelected) {
        return true
    }
    return store.selected.some(s => s.id === item.id)
}

function toggleRow(item: any) {
    if (store.allSelected) {
        return
    }

    if (store.selected.some(s => s.id === item.id)) {
        store.selected = store.selected.filter(s => s.id !== item.id)
    } else {
        store.selected.push(item)
    }
}

function getItemActions(item: Record<string, unknown>) {
    // Access computed to establish reactivity dependency
    store.actionsLoaded

    const actions = store.config.config.actions
        .filter((action: any) => typeof action !== 'string' || (item.aclAccess as any)[action])
        .map((action: any) => {
            const actionName = action.action || action
            if (typeof actionName === 'string') {
                const cached = store.customActionsCache.get(`${url.module}-${actionName}`)
                if (cached) {
                    if (cached.hasAccess && !cached.hasAccess(item)) {
                        return null
                    }
                    return cached
                }

                const coreAction = (coreActions as any)[actionName]

                if (!coreAction) {
                    store.loadCustomAction(actionName)
                    return {
                        icon: 'mdi-loading',
                        label: 'LBL_LOADING',
                        onClick: async (item: any) => {
                            const resolvedAction = await store.loadCustomAction(actionName)
                            if (resolvedAction?.onClick) {
                                await resolvedAction.onClick(item)
                            }
                        },
                    }
                }

                return coreAction
            }
            return {
                ...action,
                onClick: (item: any) => eval(action.onClick)(item),
            }
        })
        .filter((action: any) => action !== null)

    return actions
}
</script>

<style scoped lang="scss">
.list-table {
    a {
        text-decoration: none;
        color: rgb(var(--v-theme-secondary));
    }
    :deep(a:focus-visible) {
        outline: 2px solid rgb(var(--v-theme-secondary-dark)) !important;
        outline-offset: 2px;
        border-radius: 2px;
    }
    :deep(.v-pagination__first),
    :deep(.v-pagination__last) {
        display: none;
    }
    .favorite-icon {
        position: relative;
        cursor: pointer;
        border-radius: 50%;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        min-width: 40px;
        min-height: 40px;

        &:hover {
            background-color: rgba(var(--v-theme-on-surface), 0.04);
        }
        &:focus-visible {
            background-color: rgba(var(--v-theme-on-surface), 0.12);
            outline: 2px solid rgb(var(--v-theme-secondary-dark));
            outline-offset: 2px;
        }
        &:active {
            background-color: rgba(var(--v-theme-on-surface), 0.16);
        }
    }

    :deep(th .v-data-table-header__content:focus-visible) {
        outline: 2px solid rgb(var(--v-theme-secondary-dark));
        outline-offset: 2px;
    }

    :deep(td .v-icon:focus-visible) {
        outline: 2px solid rgb(var(--v-theme-secondary-dark));
        outline-offset: 2px;
        border-radius: 50%;
    }

    @media only screen and (min-width: 1280px) {
        &.sticky-columns {
            :deep(th:nth-child(2)),
            :deep(td:nth-child(2)) {
                position: sticky;
                left: 48px;
                z-index: 9;
                background-color: rgb(var(--v-theme-surface));
            }

            :deep(th:first-child),
            :deep(td:first-child) {
                position: sticky;
                left: 0;
                z-index: 10;
                background-color: rgb(var(--v-theme-surface));
            }
        }
    }
}
.v-data-table-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 16px;

    &__items-per-page {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    &__pagination {
        display: flex;
        gap: 4px;
    }
}
.table-checkbox-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    padding-left: 4px;
}
</style>
