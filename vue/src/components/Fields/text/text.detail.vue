<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row" v-on:dblclick.prevent="startInlineEdit()">
            <div>
                {{ value }}
                <a v-if="props.field.model?.length > lengthToCrop" @click="toggleExpanded"
                    >{{ languages.label(expanded ? 'LBL_COLLAPSE' : 'LBL_EXPAND') }}
                    <v-icon :icon="expanded ? 'mdi-chevron-up' : 'mdi-chevron-down'" />
                </a>
            </div>
            <Pencil
                :defs="props.defs"
                :hidePencil="hidePencil"
                @inlineEditBtnClicked="(fieldName: string) => $emit('inlineEditBtnClicked', fieldName)"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useLanguagesStore } from '@/store/languages'
import Pencil from '../Pencil.vue'
import { FieldProps } from '../Field.model';
import { onMounted } from 'vue'
import router from '@/router';
import { modulesApi } from '@/api/modules.api';
import { useLocalStorageStore } from '@/store/localStorage';

async function loadExpandedPreference() {
    if (!props.defs?.name) {
        return
    }
    expanded.value = localStorage.getDescriptionFieldExpanded(router.currentRoute.value.params?.module as string, props.defs.name)  ?? false
}

onMounted(() => {
    loadExpandedPreference()
})

const props = defineProps<FieldProps<string>>()
const emit = defineEmits(['inlineEditBtnClicked'])
const languages = useLanguagesStore()
const localStorage = useLocalStorageStore()

const lengthToCrop = 180
const expanded = ref<boolean>(false)

function toggleExpanded() {
    expanded.value = !expanded.value
    localStorage.setDescriptionFieldExpanded(router.currentRoute.value.params?.module as string, props.defs.name, expanded.value)
}

const value = computed(() =>
    !expanded.value && props.field.model?.length > lengthToCrop
        ? props.field.model.substring(0, lengthToCrop).trim() + '...'
        : props.field.model,
)
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
a {
    color: rgb(var(--v-theme-secondary));
    display: block;
    &:hover {
        cursor: pointer;
    }
}
</style>
