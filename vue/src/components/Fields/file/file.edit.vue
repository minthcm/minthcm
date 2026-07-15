<template>
    <div>
        <v-file-input
            :label="props.label"
            variant="outlined"
            density="compact"
            hide-details
            :modelValue="file"
            :error="props.state === 'error'"
            @update:modelValue="(v) => updateModelValue(v)"
            @click:clear="() => updateModelValue(null)"
            :prepend-icon="isImage ? 'mdi-image' : 'mdi-paperclip'"
            :accept="[isImage ? 'image/*' : '*']"
        />
        <div v-if="isImage && previewUrl" class="image-preview-wrapper" @click="openImagePopup(previewUrl, file.name)">
            <img :src="previewUrl" class="image-preview" />
            <div class="image-preview-overlay">
                <v-icon icon="mdi-magnify-plus-outline" size="x-large" color="white" />
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { computed, onMounted, onBeforeUnmount, ref, watch } from 'vue'
import { FieldProps } from '../Field.model'
import { usePopupsStore } from '@/store/popups'
import MintPopupImage from '@/components/MintPopups/MintPopupImage.vue'

const props = defineProps<FieldProps<File>>()
const emit = defineEmits<{
    (e: 'update:modelValue', value: File): void
}>()

const file = ref<File>(getEmptyFile())
const localObjectUrl = ref<string | null>(null)

onMounted(() => {
    if (props.field.model) {
        file.value = new File([], props.field.model)
    }
})

onBeforeUnmount(() => {
    revokeObjectUrl()
})

function revokeObjectUrl() {
    if (localObjectUrl.value) {
        URL.revokeObjectURL(localObjectUrl.value)
        localObjectUrl.value = null
    }
}

async function updateModelValue(value: File | File[] | null) {
    revokeObjectUrl()
    if (!value) {
        file.value = getEmptyFile()
    } else {
        file.value = Array.isArray(value) ? value[0] : value
        if (isImage.value && file.value.size > 0) {
            localObjectUrl.value = URL.createObjectURL(file.value)
        }
    }
    props.field.model = file.value
    emit('update:modelValue', file.value)
}

const isImage = computed(() => {
    return props.defs.type === 'image'
})

const previewUrl = computed(() => {
    if (localObjectUrl.value) {
        return localObjectUrl.value
    }
    if (!props.data?.bean?.attributes?.id || !file.value.name) {
        return null
    }
    const beanId = props.data.bean.attributes.id
    const fieldName = props.defs.name
    return `legacy/index.php?entryPoint=download&type=${props.data.bean.module}&id=${beanId}_${fieldName}&time=${new Date().toISOString()}`
})

function openImagePopup(src: string, alt?: string) {
    usePopupsStore().showPopup({
        title: alt ?? '',
        icon: 'mdi-image',
        component: MintPopupImage,
        data: { src, alt },
    })
}

function getEmptyFile(): File {
    return new File([], '')
}

watch(
    () => props.field.model,
    (newVal) => {
        if (newVal !== file.value.name) {
            revokeObjectUrl()
            file.value = newVal ? new File([], newVal) : getEmptyFile()
        }
    },
)
</script>

<style lang="scss" scoped>
.image-preview-wrapper {
    position: relative;
    display: inline-block;
    margin-top: 8px;
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
        border-radius: 4px;
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
</style>
