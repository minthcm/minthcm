<template>
    <v-text-field
        class="input"
        ref="input"
        v-model="filterName"
        :label="languages.label('LBL_ESLIST_FILTER_NAME')"
        variant="outlined"
        density="compact"
        :error-messages="errorMsg"
    />
    <div class="buttons mt-4">
        <MintButton @click="$emit('close')" variant="text" :text="languages.label('LBL_ESLIST_CANCEL')" />
        <MintButton @click="save" variant="primary" :text="languages.label('LBL_ESLIST_SAVE')" />
    </div>
</template>

<script setup lang="ts">
import { ref, defineEmits, defineProps } from 'vue'
import { useLanguagesStore } from '@/store/languages'
import { useListViewStore } from './ListViewStore'
import MintButton from '@/components/MintButtons/MintButton.vue'

const emit = defineEmits(['close'])
const props = defineProps(['data'])

const filterName = ref('')
const errorMsg = ref('')

const languages = useLanguagesStore()
const store = useListViewStore()

function save() {
    if (validate()) {
        const name = filterName.value.trim()
        const filterWithSameNameIndex = store.preferences?.saved_filters?.findIndex((f) => f.name === name)
        if (filterWithSameNameIndex > -1) {
            store.preferences.saved_filters[filterWithSameNameIndex] = {
                filters: props.data.filterRows,
                name,
            }
            store.preferences = {
                ...store.preferences,
                saved_filters: [...store.preferences.saved_filters],
            }
        } else {
            const savedFilters = store.preferences?.saved_filters ?? []
            store.preferences = {
                ...store.preferences,
                saved_filters: [...savedFilters, { filters: props.data.filterRows, name }],
            }
        }
        store.savePreferences()
        store.activeFilter = name
        emit('close')
    }
}

function validate() {
    errorMsg.value = ''
    let valid = true
    if (!filterName.value.trim()) {
        errorMsg.value = languages.label('LBL_ESLIST_REQUIRED_FIELD_ERROR')
        valid = false
    }
    return valid
}
</script>

<style scoped lang="scss">
.input {
    min-width: 350px;
}

.buttons {
    display: flex;
    width: 100%;
    gap: 16px;
    justify-content: space-between;
}
</style>
