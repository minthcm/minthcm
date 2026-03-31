<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row">
            <template v-if="fileUrl">
                <img v-if="isImage" :src="fileUrl" :alt="props.modelValue" />
                <a v-else :href="fileUrl">
                    {{ props.field.model }}
                </a>
            </template>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { FieldProps } from '../Field.model'

const props = defineProps<FieldProps<string>>()

const serverFileName = computed(() => {
    if (!props.data?.bean?.attributes?.id) {
        return ''
    }
    let serverFileName = props.data.bean.attributes.id
    if (isImage.value) {
        serverFileName += `_${props.defs.name}`
    }
    return serverFileName
})

const fileUrl = computed(() => {
    if (props.field.model) {
        return `legacy/index.php?entryPoint=download&type=${props.data.bean.module}&id=${serverFileName.value}&time=${new Date().toISOString()}`
    }
    return ''
})

const isImage = computed(() => {
    return props.defs.type === 'image'
})
</script>

<style lang="scss" scoped>
label {
    font-size: 12px;
    color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
}
img {
    max-width: 100%;
}
a {
    color: rgba(var(--v-theme-secondary), var(--v-high-emphasis-opacity));
    text-decoration: none;
    &:hover {
        text-decoration: underline;
    }
}
</style>
