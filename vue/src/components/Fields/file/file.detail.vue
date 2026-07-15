<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row">
            <template v-if="fileUrl">
                <div v-if="isImage" class="image-preview-wrapper" @click="openImagePopup(fileUrl, props.field.model)">
                    <img :src="fileUrl" :alt="props.modelValue" class="image-preview" />
                    <div class="image-name-bar">{{ props.field.model }}</div>
                    <div class="image-preview-overlay">
                        <v-icon icon="mdi-magnify-plus-outline" size="x-large" color="white" />
                    </div>
                </div>
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
import { usePopupsStore } from '@/store/popups'
import MintPopupImage from '@/components/MintPopups/MintPopupImage.vue'

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

function openImagePopup(src: string, alt?: string) {
    usePopupsStore().showPopup({
        title: alt ?? '',
        icon: 'mdi-image',
        component: MintPopupImage,
        data: { src, alt },
    })
}
</script>

<style lang="scss" scoped>
label {
    font-size: 12px;
    color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
}
.image-preview-wrapper {
    position: relative;
    display: inline-block;
    cursor: pointer;
    border-radius: 4px;
    overflow: hidden;

    &:hover .image-preview-overlay {
        opacity: 1;
    }

    .image-preview {
        max-width: 100%;
        max-height: 200px;
        display: block;
    }

    .image-name-bar {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 4px 8px;
        background: rgba(0, 0, 0, 0.55);
        color: white;
        font-size: 11px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .image-preview-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.55);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s ease;
    }
}
a {
    color: rgba(var(--v-theme-secondary), var(--v-high-emphasis-opacity));
    text-decoration: none;
    &:hover {
        text-decoration: underline;
    }
}
</style>
