<template>
    <div>
        <v-chip v-if="props.defs?.options_colors" :style="coloredEnumStyle" class="enum-chip">
            {{ languages.translateListValue(props.modelValue, props.defs?.options) }}
        </v-chip>
        <div v-else>{{ languages.translateListValue(props.modelValue, props.defs?.options) }}</div>
    </div>
</template>

<script setup lang="ts">
import { useLanguagesStore } from '@/store/languages'
import { FieldProps } from '../Field.model';
import { useBackendStore } from '@/store/backend';
import { computed } from 'vue';

const props = defineProps<FieldProps>()
const languages = useLanguagesStore()
const backend = useBackendStore()

const coloredEnumStyle = computed(() => {
    const colors = backend.initData.field_variables?.ColoredEnum?.options_colors
    if (colors && props.defs?.options_colors) {
        return colors[props.defs.options_colors[props.modelValue]] || colors['-default-']
    }
    return ''
})
</script>

<style scoped lang="scss">
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
