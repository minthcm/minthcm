<template>
    <table class="mint-data-table">
        <thead>
            <tr>
                <th v-for="column in props.columns" :key="column.name">
                    {{ column.label }}
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="record in props.records" :key="record.id">
                <td v-for="column in columns" :key="column.name">
                    <Field view="list" :data="{ bean: record }" :defs="column" />
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script setup lang="ts">
import { defineProps } from 'vue'
import Field from '@/components/Fields/Field.vue'

interface Props {
    columns: []
    records: []
}

const props = defineProps<Props>()
</script>

<style scoped lang="scss">
.mint-data-table {
    width: 100%;
    border-collapse: collapse;

    th {
        transition: all 100ms ease-out;
        height: 56px;
        user-select: none;
        color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));

        &:hover {
            color: rgba(var(--v-theme-on-surface), var(--v-high-emphasis-opacity));
        }
    }

    td {
        height: 52px;
        color: rgba(var(--v-theme-on-surface), var(--v-high-emphasis-opacity));
    }

    th,
    td {
        padding: 0px 16px;
        font-weight: 400;
        text-align: start;
        border-bottom: thin solid rgba(var(--v-border-color), var(--v-border-opacity));
    }

    thead {
    }

    tbody {
        tr {
            transition: 100ms ease-in-out;
            &:hover {
                background: rgba(var(--v-theme-secondary), 0.05);
            }
        }
    }
}
</style>
