<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row">
            <a class="mint-email-detail-field" :href="`mailto:${props.modelValue}`">
                <v-icon size="x-small" v-if="props.modelValue">mdi-email</v-icon>
                <span>{{ props.modelValue }}</span>
            </a>
            <Pencil
                :defs="props.defs"
                @inlineEditBtnClicked="(fieldName: string) => $emit('inlineEditBtnClicked', fieldName)"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import { FieldVardef } from '@/store/modules'
import Pencil from '../Pencil.vue'

interface Props {
    defs: FieldVardef
    label: string
    modelValue?: any
    data?: any
}

const props = defineProps<Props>()
</script>

<style scoped lang="scss">
label {
    font-size: 12px;
    color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
}
.mint-email-detail-field {
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
    color: rgb(var(--v-theme-secondary));
    text-decoration: none;
    display: flex;
    align-items: baseline;
    gap: 6px;
    width: fit-content;

    .v-icon {
        top: 1px;
    }
}
</style>
