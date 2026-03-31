<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row" v-on:dblclick.prevent="startInlineEdit()">
            <div>{{ value }}</div>
            <Pencil
                :defs="props.defs"
                :hidePencil="hidePencil"
                @inlineEditBtnClicked="(fieldName: string) => $emit('inlineEditBtnClicked', fieldName)"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import { useLanguagesStore } from '@/store/languages';
import { FieldProps } from '../Field.model';
import { computed } from 'vue';

const props = defineProps<FieldProps>()
const languages = useLanguagesStore()

const value = computed(() => {
    if (!props.modelValue) {
        return ''
    }
    let value = props.modelValue
    if (typeof value === 'string') {
        value = props.modelValue.replaceAll('^', '').split(',')
    }
    if (!props.defs?.options) {
        return value.join(', ')
    }
    const options = Object.fromEntries(
        languages.getList(props.defs.options)
            ?.map(item => [item.key, item.value])
    ) || {}
    return value.filter((val) => val in options)
        .map((val) => languages.translateListValue(val, props.defs.options))
        .join(', ')
})
</script>

<style scoped lang="scss">
label {
    font-size: 12px;
    color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
}
div {
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
}
</style>
