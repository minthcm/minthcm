<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row" v-on:dblclick.prevent="startInlineEdit()">
            <v-chip
                v-if="props.defs?.options_colors"
                class="enum-chip"
                :style="coloredEnumStyle"
            >
                {{ parsedValue }}
            </v-chip>
            <div v-else>{{ parsedValue }}</div>
            <Pencil
                :defs="props.defs"
                :hidePencil="hidePencil"
                @inlineEditBtnClicked="(fieldName: string) => $emit('inlineEditBtnClicked', fieldName)"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import { useLanguagesStore } from '@/store/languages'
import Pencil from '../Pencil.vue'
import { computed } from 'vue'
import { FieldProps } from '../Field.model'
import { useBackendStore } from '@/store/backend'
import { defineProps } from 'vue'

const props = defineProps<FieldProps>()
const languages = useLanguagesStore()
const backend = useBackendStore()

const parsedValue = computed(() => {
    return items.value.find((item) => item.key === props.field.model)?.value || ''
})

const coloredEnumStyle = computed(() => {
    const colors = backend.initData.field_variables?.ColoredEnum?.options_colors
    if (colors && props.defs?.options_colors) {
        return colors[props.defs.options_colors[props.modelValue]] || colors['-default-']
    }
    return ''
})

const items = computed(() => {
    const options = props.options ?? props.defs?.options
    if (!options) {
        return []
    }
    if (typeof options === 'string') {
        return languages.getList(options)
    }
    if (!Array.isArray(options) && typeof options === 'object') {
        return Object.entries(options).map(([key, value]) => ({
            key,
            value,
        }))
    }
    return options
})
function startInlineEdit() {
    if (props?.defs?.name && typeof props.defs.name === 'string' && props.defs.name.length > 0) {
        emit('inlineEditBtnClicked', props.defs.name)
    }
}
</script>

<style scoped lang="scss">
label {
    font-size: 12px;
    color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
    display: block;
}
:deep(.v-chip.v-chip--size-default) {
    height: 28px;
    border-radius: 4px;
    letter-spacing: 0.09px;
}
.enum-chip {
    display: flex;
    align-items: center;
    justify-content: center;
    width: fit-content;
    font-size: 13px;
    padding: 4px 12px;
    font-weight: bold;
    text-transform: uppercase;
    border-radius: 5px;
    letter-spacing: 0.09px;
}
</style>
