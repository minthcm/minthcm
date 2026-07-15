<template>
    <v-data-table
        class="mint-data-table"
        :headers="headers"
        :items="tableItems"
        hide-default-footer
        :items-per-page="-1"
        :row-props="({ item }) => isSkeleton(item) ? { class: 'skeleton-row' } : {}"
        @update:sort-by="onSortBy"
    >
        <template
            v-for="column in typedColumns"
            v-slot:[`item.${column.name}`]="{ item }"
        >
            <v-skeleton-loader
                v-if="isSkeleton(item)"
                :key="'skeleton-' + column.name"
                type="text"
                width="80%"
            />
            <Field
                v-else
                :key="column.name"
                view="list"
                :data="{ bean: item }"
                :defs="column.name === 'name' || column.widget_class === 'SubPanelDetailViewLink'
                    ? { ...column, type: 'name' }
                    : column"
                :label="languages.label(column.label, item.module)"
                :modelValue="item.attributes[column.name]"
            />
        </template>
        <template v-if="hasInlineButtons" v-slot:[actionsSlot]="{ item }">
            <MintSubpanelsInlineButtons
                v-if="!isSkeleton(item)"
                :module="props.module"
                :recordId="item.id"
                :subpanel="props.subpanel"
            />
        </template>
    </v-data-table>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import Field from '@/components/Fields/Field.vue'
import { useLanguagesStore } from '@/store/languages'
import MintSubpanelsInlineButtons from '../MintPanel/MintPanelSubpanels/MintSubpanelsInlineButtons.vue'

const emit = defineEmits<{
    (e: 'sort-changed', params: { sortBy: string; sortOrder: string }): void
}>()

const languages = useLanguagesStore()

interface Column {
    name: string
    label: string
    [key: string]: unknown
}

interface Props {
    columns: Array<Object>
    records: Array<Object>
    module?: string
    subpanel?: Object
    loading?: number
    skeletonIds?: Set<string>
}

const props = defineProps<Props>()

const actionsSlot = 'item.actions'

const typedColumns = computed(() => props.columns as Column[])

const hasInlineButtons = computed(
    () => props.subpanel && (props.subpanel as any).inlineButtons && props.module,
)

function onSortBy(sortItems: Array<{ key: string; order: string }>) {
    const first = sortItems[0]
    emit('sort-changed', {
        sortBy: first?.key ?? '',
        sortOrder: first?.order ?? '',
    })
}

const headers = computed(() => {
    const cols: { key: string; title: string; sortable: boolean }[] = typedColumns.value.map(col => ({
        key: col.name,
        title: col.label,
        sortable: true,
    }))
    if (hasInlineButtons.value) {
        cols.push({ key: 'actions', title: '', sortable: false })
    }
    return cols
})

function isSkeleton(item: any): boolean {
    return item?.__skeleton === true
}

const tableItems = computed(() => {
    const items = (props.records as Array<any>).map(record => ({
        ...record,
        ...record.attributes,
        __skeleton: props.skeletonIds?.has(record.id) ?? false,
    }))
    for (let i = 0; i < (props.loading ?? 0); i++) {
        items.unshift({ __skeleton: true, id: `__loading__${i}` })
    }
    return items
})
</script>

<style scoped lang="scss">
.mint-data-table {
    background: transparent;

    :deep(th) {
        transition: all 100ms ease-out;
        height: 56px !important;
        color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity)) !important;
        font-weight: 400;

        span, .v-data-table-header__content {
            font-size: 0.875rem !important;
        }

        &:hover {
            color: rgba(var(--v-theme-on-surface), var(--v-high-emphasis-opacity)) !important;
        }
    }

    :deep(td) {
        height: 52px !important;
        font-size: 0.875rem;
        color: rgba(var(--v-theme-on-surface), var(--v-high-emphasis-opacity));
    }

    :deep(tbody tr) {
        transition: 100ms ease-in-out;

        &:hover td {
            background: rgba(var(--v-theme-secondary), 0.05) !important;
        }

        &.skeleton-row {
            pointer-events: none;

            &:hover td {
                background: transparent !important;
            }
        }
    }
}
</style>
