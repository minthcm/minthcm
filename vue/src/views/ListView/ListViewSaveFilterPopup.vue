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
        <MintButton @click="$emit('close')" :disabled="validation" variant="text" :text="languages.label('LBL_ESLIST_CANCEL')" />
        <MintButton @click="save" :disabled="validation" variant="primary" :text="languages.label('LBL_ESLIST_SAVE')" />
    </div>
</template>

<script setup lang="ts">
import { ref, defineEmits, defineProps, computed } from 'vue'
import { useLanguagesStore } from '@/store/languages'
import { useListViewStore } from './ListViewStore'
import { usePopupsStore } from '@/store/popups'
import MintButton from '@/components/MintButtons/MintButton.vue'
import MintPopupConfirm from '@/components/MintPopups/MintPopupConfirm.vue'

const emit = defineEmits(['close'])
const props = defineProps(['data'])

const filterName = ref('')
const errorMsg = ref('')

const languages = useLanguagesStore()
const store = useListViewStore()
const popups = usePopupsStore()

const validation = computed( () => {
    return saveFilterPopup?.unclosable
})

let saveFilterPopup = popups.popups[popups.popups.findIndex((f) => f.title === languages.label('LBL_ESLIST_SAVE_FILTER'))]

async function save() {
    if (await validate()) {
        const name = filterName.value.trim()
        const filterWithSameNameIndex = store.preferences?.saved_filters?.findIndex((f) => f.name === name)
        if (filterWithSameNameIndex > -1) {
            store.preferences.saved_filters[filterWithSameNameIndex] = {
                filters: props.data.filterRows,
                name,
                myObjects: props.data.myObjects 
            }
            store.preferences = {
                ...store.preferences,
                saved_filters: [...store.preferences.saved_filters],
            }
        } else {
            const savedFilters = store.preferences?.saved_filters ?? []
            store.preferences = {
                ...store.preferences,
                saved_filters: [...savedFilters, { 
                    filters: props.data.filterRows, 
                    name, 
                    myObjects: props.data.myObjects 
                }],
            }
        }
        store.savePreferences()
        store.activeFilter = name
        emit('close')
    }
}

async function validate() {
    errorMsg.value = ''
    let valid = true
    let trimmedFilterName = filterName.value.trim();
    if (!trimmedFilterName) {
        errorMsg.value = languages.label('LBL_ESLIST_REQUIRED_FIELD_ERROR')
        valid = false
    }
    if(store.preferences?.saved_filters?.findIndex((f) => f.name === trimmedFilterName) !== -1){
        saveFilterPopup.unclosable = true;
        return new Promise((resolve) => {
            popups.showPopup({
                title: languages.label('LBL_CONFIRM'),
                component: MintPopupConfirm,
                unclosable: true,
                icon: 'mdi-content-save-outline',
                data: { 
                    text: languages.label('LBL_ESLIST_OVERWRITE_FILTER_CONFIRM'),
                    onReject: () => {
                        saveFilterPopup.unclosable = false; 
                        resolve(false)
                    },
                    onConfirm: () => {
                        saveFilterPopup.unclosable = false;
                        resolve(true)
                    }
                }
            })
        })
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
