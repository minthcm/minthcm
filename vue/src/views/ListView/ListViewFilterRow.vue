<template>
    <div class="filter-row">
        <v-row no-gutters>
            <v-col cols="3" class="px-2">
                <v-autocomplete
                    class="col col-3"
                    v-model="field"
                    @update:model-value="handleFieldChange"
                    :items="store.filterableFields"
                    item-value="name"
                    item-title="label"
                    :label="languages.label('LBL_ESLIST_FIELD')"
                    :no-data-text="languages.label('LBL_ESLIST_NO_DATA')"
                    variant="outlined"
                    hide-details
                />
            </v-col>
            <v-col cols="3" v-if="field" class="px-2">
                <v-select
                    class="col col-3"
                    v-model="operator"
                    @update:model-value="handleOperatorChange"
                    :items="operatorItems"
                    item-value="key"
                    item-title="label"
                    :label="languages.label('LBL_ESLIST_OPERATOR')"
                    variant="outlined"
                    hide-details
                />
            </v-col>
            <v-col cols="3" v-for="input in inputs" :key="input" class="px-2">
                <component
                    :is="getInputComponent(input.type)"
                    :fieldDefs="fieldDefs"
                    :input="input"
                    @update:modelValue="newValue => input.value = newValue"
                />
            </v-col>
        </v-row>
        <v-btn
            class="ms-auto"
            variant="text"
            density="comfortable"
            icon="mdi-close"
            @click="emit('delete-filter-row', props.index)"
        />
    </div>
</template>

<script setup lang="ts">
import { defineProps, ref, defineEmits, computed } from 'vue'
import { useLanguagesStore } from '@/store/languages'
import { useListViewStore } from './ListViewStore'
import * as operatorDefs from './operators'
import * as inputDefs from './fields'

export interface FilterRow {
    field: string | null
    operator: string | null
    inputs: []
}

interface Props {
    row: FilterRow
    index: number
}

const props = defineProps<Props>()
const emit = defineEmits([
    'update:field',
    'update:operator',
    'update:inputs',
    'delete-filter-row',
])
const store = useListViewStore()
const languages = useLanguagesStore()
const field = ref(props.row.field ?? '')
const operator = ref(props.row.operator ?? '')
const inputs = ref(props.row.inputs ?? [])

const fieldDefs = computed(() => {
    return field.value ? store.defs?.search?.[field.value] : {}
})
const operatorList = computed(() => {
    if (!field.value) {
        return {}
    }
    const type = fieldDefs.value.type
    return (
        operatorDefs[type] ??
        operatorDefs[operatorDefs.typeMap[type]] ??
        operatorDefs[operatorDefs.defaultOperator]
    )
})
const operatorItems = computed(() => {
    return Object.entries(operatorList.value).map(([key, op]) => ({
        key,
        label: languages.label(op.label),
    }))
})

function getInputComponent(type: string) {
    return inputDefs[type] ? inputDefs[type] : null
}

function handleFieldChange() {
    operator.value = ''
    inputs.value = []
    emit('update:field', field.value)
    emit('update:operator', operator.value)
    emit('update:inputs', inputs.value)
}

function handleOperatorChange() {
    if (!operator.value || !operatorList.value[operator.value].inputs) {
        inputs.value = []
    } else {
        inputs.value = operatorList.value[operator.value].inputs.map((i) => ({
            type: i.type,
            value: null,
            label: languages.label(i.label),
        }))
    }
    emit('update:operator', operator.value)
    emit('update:inputs', inputs.value)
}
</script>

<style scoped lang="scss">
.filter-row {
    padding: 0px 8px;
    display: flex;
    align-items: center;
    gap: 16px;
}
</style>
