<template>
    <v-data-table
        class="duplicate-table"
        :headers="headers"
        :items="records"
        hide-default-footer
        :items-per-page="-1"
    >
        <template v-slot:item._name="{ item }">
            <router-link class="name-link" :to="{ name: 'record', params: { module: item.module, id: item.id } }">
                {{ item._name }}
            </router-link>
        </template>
    </v-data-table>
</template>

<script setup lang="ts">
import { computed } from 'vue'

type DuplicateRecord = {
    id: string
    module: string
    [key: string]: any
}

const props = defineProps<{
    duplicatedRecords: DuplicateRecord[]
}>()

const EXCLUDED_KEYS = new Set(['id', 'module', 'first_name', 'last_name', 'name'])

const records = computed(() =>
    (props.duplicatedRecords ?? []).map((record) => ({ ...record, _name: resolveName(record) }))
)

const headers = computed(() => {
    const sample = props.duplicatedRecords?.[0] ?? {}
    const dynamicHeaders = Object.keys(sample)
        .filter((key) => !EXCLUDED_KEYS.has(key))
        .map((key) => ({ title: key.replace(/_/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase()), key, sortable: true }))
    return [
        { title: 'Name', key: '_name', sortable: true },
        ...dynamicHeaders,
    ]
})

function resolveName(item: DuplicateRecord): string {
    if (item.first_name || item.last_name) {
        return `${item.first_name ?? ''} ${item.last_name ?? ''}`.trim()
    }
    if (item.name) {
        return item.name
    }
    return item.id
}
</script>

<style scoped lang="scss">
.duplicate-table {
    a {
        text-decoration: none;
        color: rgb(var(--v-theme-secondary));
    }

    .name-link {
        font-size: 15px;
        font-weight: 600;
    }
}
</style>