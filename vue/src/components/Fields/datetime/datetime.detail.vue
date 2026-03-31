<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row" v-on:dblclick.prevent="startInlineEdit()">
            <div :name="props.defs.name">{{ parsedDate }}</div>
            <Pencil
                :defs="props.defs"
                :hidePencil="hidePencil"
                @inlineEditBtnClicked="(fieldName: string) => $emit('inlineEditBtnClicked', fieldName)"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import Pencil from '../Pencil.vue'
import { FieldProps } from '../Field.model';

const props = defineProps<FieldProps>()
const parsedDate = computed(() => {
    return props.field.formatted.user
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
}
div {
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
}
</style>
