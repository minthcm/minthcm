<template>
    <div class="mass-update-row">
        <v-row no-gutters>
            <v-col cols="2" class="px-2">
                <v-autocomplete
                    class="col col-2"
                    v-model="field"
                    @update:model-value="handleFieldChange"
                    :items="store.massUpdatableFields"
                    item-value="name"
                    item-title="label"
                    :label="languages.label('LBL_ESLIST_FIELD')"
                    :no-data-text="languages.label('LBL_ESLIST_NO_DATA')"
                    variant="outlined"
                    hide-details
                    density="compact"
                />
            </v-col>
            <v-col cols="2" v-for="input in inputs" :key="input" class="px-2">
                <component
                    :is="getInputComponent(input.type)"
                    :fieldDefs="fieldDefs"
                    :input="input"
                    density="compact"
                    @update:modelValue="(newValue) => (input.value = input.modifiers ? runModifiers(input.modifiers, newValue) : newValue)"
                    :inputs="inputs"
                />
            </v-col>
        </v-row>
        <v-btn
            class="ms-auto"
            variant="text"
            density="comfortable"
            icon="mdi-close"
            @click="store.deleteMassUpdateRow(props.index)"
        />
    </div>
</template>

<script setup lang="ts">
import { useListViewStore } from './ListViewStore'
import { defineProps, ref, defineEmits, computed } from 'vue'
import { useLanguagesStore } from '@/store/languages'
import * as fieldsDefs from './fields'
import * as inputDefs from './inputs'

export interface MassUpdateRow {
    field: string | null
    value?: any
    inputs: []
}

interface Props {
    row: MassUpdateRow
    index: number
}

const props = defineProps<Props>()

const store = useListViewStore()
const languages = useLanguagesStore()
const emit = defineEmits(['update:field', 'update:inputs'])

const field = ref(props.row.field ?? '')
const inputs = ref(props.row.inputs ?? [])
const fieldDefs = computed(() => {
    return field.value ? store.defs?.massupdate?.[field.value] : {}
})

const inputsMap = computed(() => {
    if (!field.value) return {}
    const type = fieldDefs.value.type
    const inputDef = inputDefs[type] ?? inputDefs[inputDefs.typeMap[type]] ?? inputDefs[inputDefs.defaultInput]

    if (inputDef.type && inputDef.label) {
        return {
            inputs: [inputDef],
        }
    }

    const inputs = Object.values(inputDef)
    return { inputs }
})

function handleFieldChange() {
    inputs.value = inputsMap.value.inputs.map((i) => ({
        type: i.type,
        value: null,
        label: parseLabel(i.label),
        modifiers: i.modifiers ?? null,
    }))
    emit('update:field', field.value)
    emit('update:inputs', inputs.value)
}

function parseLabel(label: string | string[])
{
    if (Array.isArray(label)) {
        label.forEach((part, index) => {
            label[index] = languages.label(part)
        })
        return label
    }
    return languages.label(label)
}

function getInputComponent(type: string) {
    return fieldsDefs[type] ? fieldsDefs[type] : null
}

function runModifiers(modifier: any, value: any) {
    if (!Array.isArray(modifier) && modifier instanceof Function) {
        return modifier(value)
    } else {
        modifier.forEach((m) => {
            value = runModifiers(m, value)
        })
    }
    return value
}

</script>
<style scoped lang="scss">
.mass-update-row {
    margin: 0 10px;
    padding: 10px 0 10px;
    display: flex;
    align-items: center;
    gap: 16px;
    border-bottom: 1px solid #0000001f;
}

.mass-update-row:first-child {
    border-top: 1px solid #0000001f;
}
</style>