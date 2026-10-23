<template>
    <div class="admin-config-panel">
        <div class="panel-header">
            <div class="panel-title">
                {{ props.data.title ? lbl(props.data.title) : '' }}
            </div>
            <div class="buttons">
                <v-alert
                    v-if="adminStore.error"
                    type="error"
                    density="compact"
                    :text="adminStore.error"
                />
                <MintButton
                    variant="primary"
                    :loading="adminStore.saving"
                    :disabled="adminStore.saving"
                    :text="languages.label('LBL_SAVE_BUTTON_LABEL', 'Administration')"
                    icon="mdi-check"
                    @click="save"
                />
            </div>
        </div>
        <div
            v-for="(section, sectionKey) in props.data.sections"
            :key="sectionKey"
            class="section"
        >
            <div v-if="section.title" class="section-title">
                {{ lbl(section.title) }}
            </div>
            <div
                v-for="(row, rowIdx) in section.fields"
                :key="rowIdx"
                v-show="isRowVisible(row)"
                class="field-row"
            >
                <div
                    v-for="(fieldDef, cellIdx) in row"
                    :key="cellIdx"
                    class="field-cell"
                >
                    <template v-if="fieldDef && fieldDef.name">
                        <template v-if="fieldDef.static_value && fieldDef.type === 'url'">
                            <a :href="fieldDef.static_value" target="_blank" class="mint-static-url">
                                {{ lbl(fieldDef.label) }}
                                <v-icon size="x-small">mdi-open-in-new</v-icon>
                            </a>
                        </template>
                        <template v-else>
                            <div v-if="fieldDef.additional_info" class="field-label-with-info">
                                <span class="field-label-text">{{ lbl(fieldDef.label) }}</span>
                                <v-tooltip location="top" :max-width="280">
                                    <template #activator="{ props: tooltipProps }">
                                        <v-icon v-bind="tooltipProps" size="small" class="ml-1 info-icon">mdi-information-outline</v-icon>
                                    </template>
                                    <span v-html="lbl(fieldDef.additional_info)"></span>
                                </v-tooltip>
                            </div>
                            <Field
                                v-if="props.data.fieldStates?.[fieldDef.name]"
                                view="edit"
                                :defs="fieldDef"
                                :label="fieldDef.additional_info ? '' : lbl(fieldDef.label)"
                                :field="props.data.fieldStates[fieldDef.name]"
                                :modelValue="props.data.record?.[fieldDef.name]"
                                @update:modelValue="(val: any) => props.data.onUpdateField?.(fieldDef.name, val)"
                            />
                        </template>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useRoute } from 'vue-router'
import Field from '@/components/Fields/Field.vue'
import MintButton from '@/components/MintButtons/MintButton.vue'
import { FieldVardef } from '@/store/modules'
import { useLanguagesStore } from '@/store/languages'
import { MintField } from '@/components/Fields/useField'
import { useAdminViewStore } from '@/views/AdminView/AdminViewStore'
import { useStatusBoxesStore } from '@/store/statusBoxes'
import { computed } from 'vue'

interface Section {
    title?: string
    fields: FieldVardef[][]
}

interface Props {
    data: {
        title?: string
        label_module?: string
        sections: Record<string, Section>
        fieldStates?: Record<string, MintField<any>>
        record?: Record<string, any>
        onUpdateField?: (name: string, value: any) => void
    }
}

const props = defineProps<Props>()
const languages = useLanguagesStore()
const adminStore = useAdminViewStore()
const statusBoxes = useStatusBoxesStore()
const route = useRoute()

const labelModule = computed(() => props.data.label_module ?? 'Administration')
const lbl = (key: string) => languages.label(key, labelModule.value)

function isFieldVisible(fieldDef: FieldVardef): boolean {
    if (!fieldDef?.visible_when?.length) return true
    return fieldDef.visible_when.every((dep) => {
        const model = props.data.fieldStates?.[dep.field]?.model
        return Number(model) == Number(dep.value)
    })
}

function isRowVisible(row: FieldVardef[]): boolean {
    // A row is visible if at least one named (non-placeholder) cell is visible
    return row.some((cell) => cell && cell.name && isFieldVisible(cell))
}

async function save() {
    const viewName = route.params.viewName as string
    try {
        await adminStore.saveConfig(viewName)
        statusBoxes.showStatus('admin-config-save-success', {
            type: 'success',
            message: languages.label('LBL_SAVED', 'Administration'),
            autoClose: true,
        })
    } catch {
        // error displayed via adminStore.error
    }
}
</script>

<style scoped lang="scss">
.admin-config-panel {
    background: rgb(var(--v-theme-surface));
    border-radius: 16px;
    box-shadow: 0px 1px 12px #00997619;
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.panel-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin: -24px -24px 0;
    padding: 24px 24px 16px;
    border-bottom: 1px solid #dbdbdb;

    .panel-title {
        flex: 1;
        font-size: 15px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.47px;
        color: rgb(var(--v-theme-secondary));
    }

    .buttons {
        display: flex;
        align-items: center;
        gap: 16px;
    }
}

.section-title {
    font-size: 16px;
    font-weight: 600;
    padding-bottom: 8px;
    border-bottom: 1px solid #dbdbdb;
    margin-bottom: 20px;
}

.field-row {
    display: flex;
    align-items: flex-start;
    gap: 24px;
    margin-bottom: 24px;
}

.field-cell {
    flex: 1;
}

.field-label-with-info {
    display: flex;
    align-items: center;
    margin-bottom: 4px;
}

.field-label-text {
    font-size: 12px;
    color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
}

.info-icon {
    color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
    cursor: help;
}

.mint-static-url {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    color: rgb(var(--v-theme-secondary));
    text-decoration: none;

    &:hover {
        text-decoration: underline;
    }
}
</style>
