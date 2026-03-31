<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row" v-on:dblclick.prevent="startInlineEdit()" @keyup.enter="$emit('inlineEditSave')">
            <router-link :name="props.defs.name" v-if="hasViewAccess" :to="recordUrl" class="relate-field">
                {{ props.modelValue }}
            </router-link>
            <span :name="props.defs.name" v-else>
                {{ props.modelValue }}
            </span>
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
import { useACL } from '@/composables/useACL';

const props = defineProps<FieldProps>()
const emit = defineEmits(['inlineEditBtnClicked'])

const recordUrl = computed(() => {
    const module = props.defs.module
    const id = props.data?.bean?.attributes?.[props.defs?.id_name]
    if (!module || !id) return ''
    return `/modules/${module}/DetailView/${id}`
})
function startInlineEdit() {
    if (props?.defs?.name && typeof props.defs.name === 'string' && props.defs.name.length > 0) {
        emit('inlineEditBtnClicked', props.defs.name)
    }
}
const hasViewAccess = computed<boolean>(() => {
    return useACL().hasAccess(props.defs.module, 'view', true, true)
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
.relate-field {
    text-decoration: none;
    color: rgb(var(--v-theme-secondary));
    cursor: pointer;
    display: block;
    width: fit-content;
}
</style>
