<template>
    <div>
        <v-chip v-if="props.defs?.options_colors" :style="coloredEnumStyle" class="enum-chip">
            {{ item }}
        </v-chip>
        <div v-else>{{ item }}</div>
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

const item = computed(() => {
    const options = props.options ?? props.defs?.options
    if (!options) {
        return []
    }
    if (typeof options === 'string') {
        return languages.translateListValue(props.modelValue, props.defs?.options)
    }
    if (!Array.isArray(options) && typeof options === 'object') {
        let list = Object.entries(options).map(([key, value]) => ({
            key,
            value,
        }))
        return list.find((item) => item.key === props.modelValue)?.value || ''
    }
    return props.modelValue
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
