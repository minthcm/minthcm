<template>
    <div>
        <label>{{ props.label }}</label>
        <div class="detail-field-row">
            <div>
                <div v-for="line in (value || '').split('\n')" :key="line">{{ line }}</div>
                <a 
                    v-if="(props.modelValue?.length > lengthToCrop) || (linesNumber >= linesToCrop)" 
                    @click="toggleExpanded"
                >{{ languages.label(expanded ? 'LBL_COLLAPSE' : 'LBL_EXPAND') }}
                    <v-icon :icon="expanded ? 'mdi-chevron-up' : 'mdi-chevron-down'" />
                </a>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useLanguagesStore } from '@/store/languages'
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
const languages = useLanguagesStore()
const localStorage = useLocalStorageStore()

const lengthToCrop = 180
const linesToCrop = 4
const expanded = ref<boolean>(false)

function toggleExpanded() {
    expanded.value = !expanded.value
    localStorage.setDescriptionFieldExpanded(router.currentRoute.value.params?.module as string, props.defs.name, expanded.value)
}

const value = computed(() => {
    let textValue = ''
    if (!expanded.value && ((props.modelValue || '').split('\n').length > linesToCrop || props.modelValue?.length > lengthToCrop)) {
        textValue  = props.modelValue.substring(0, lengthToCrop).trim() + '...'
        const lines = (textValue || '').split('\n').slice(0, linesToCrop)
        const joinedLines = lines.join('\n')
        textValue = joinedLines.slice(-3) == '...' ? joinedLines : joinedLines + '...'
    } else {
        textValue = props.modelValue || ''
    }
    return textValue
})

const linesNumber = computed(() => (props.modelValue || '').split('\n').length)
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
