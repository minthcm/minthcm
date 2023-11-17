<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row">
            <a class="mint-phone-detail-field" :href="`tel:${props.modelValue}`">
                <v-icon size="x-small" v-if="props.modelValue">mdi-phone</v-icon>
                {{ props.modelValue }}
            </a>
            <Pencil :defs="props.defs" />
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
.mint-phone-detail-field {
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
    color: rgb(var(--v-theme-secondary));
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 4px;
    width: fit-content;
}
</style>
