<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row">
            <div>
                {{ value }}
                <a v-if="props.modelValue?.length > lengthToCrop" @click="expanded = !expanded"
                    >{{ languages.label(expanded ? 'LBL_COLLAPSE' : 'LBL_EXPAND') }}
                    <v-icon :icon="expanded ? 'mdi-chevron-up' : 'mdi-chevron-down'" />
                </a>
            </div>
            <Pencil :defs="props.defs" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useLanguagesStore } from '@/store/languages'
import { FieldVardef } from '@/store/modules'
import Pencil from '../Pencil.vue'

interface Props {
    defs: FieldVardef
    label: string
    modelValue?: any
    data?: any
}

const props = defineProps<Props>()
const languages = useLanguagesStore()

const lengthToCrop = 180
const expanded = ref<boolean>(false)

const value = computed(() =>
    !expanded.value && props.modelValue?.length > lengthToCrop
        ? props.modelValue.substring(0, lengthToCrop).trim() + '...'
        : props.modelValue,
)
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
a {
    color: rgb(var(--v-theme-secondary));
    display: block;
    &:hover {
        cursor: pointer;
    }
}
</style>
