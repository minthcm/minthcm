<template>
    <div class="parent-container">
    <v-autocomplete
            :items="languages.getList(props.defs?.options)"
            :label="languages.label('LBL_ASSIGNED_TO_MODULE')"
        variant="outlined"
        density="compact"
        hide-details
            v-model="parentModel"
            item-value="value"
            item-title="key"
        @keyup.enter="$emit('inlineEditSave')"
        @keyup.esc="$emit('inlineEditCancel')"
    />
        <v-menu v-model="menuOpen" :location="'bottom'">
            <template v-slot:activator="val">
                <v-text-field
                    :label="languages.label('LBL_ASSIGNED_TO_RECORD')"
                    variant="outlined"
                    density="compact"
                    hide-details
                    v-model="recordModel.name"
                    v-bind="val.props"
                    @input="(event) => fetchRecordItems(event)"
                    @click="menuOpen = false"
                >
                    <template #append-inner>
                        <v-fab-transition class="search-prepend-icon">
                            <v-icon
                                v-if="recordModel.name"
                                icon="mdi-close"
                                @click="recordModel = { id: '', name: '' }"
                            />
                            <v-icon v-else icon="mdi-magnify" />
                        </v-fab-transition>
</template>
                </v-text-field>
            </template>
            <v-list>
                <v-list-item v-if="isLoading">
                    <v-progress-circular color="primary" indeterminate></v-progress-circular>
                </v-list-item>
                <v-list-item
                    v-if="!isLoading && !items.length"
                    class="text-caption"
                    v-text="
                        !items || recordModel.name.length < 3
                            ? languages.label('LBL_MINT4_GS_HELP_TIP')
                            : languages.label('LBL_MINT4_GS_NO_RECORDS_FOUND')
                    "
                />
                <div v-if="!isLoading">
                    <v-list-item @click="clickOnMenuItem(item)" v-for="(item, index) in items" :key="index">
                        <span v-html="getHighlightedText(item.name, recordModel.name)"></span>
                    </v-list-item>
                </div>
                <v-divider />
                <v-list-item>
                    <MintButton
                        variant="text"
                        :text="languages.label('LBL_ADVANCED_SEARCH_BUTTON')"
                        @click="openRelatePopup"
                        icon="mdi-text-search"
                        color="primary"
                    />
                </v-list-item>
            </v-list>
        </v-menu>
    </div>
</template>

<script setup lang="ts">
import { defineProps, computed, ref, defineEmits } from 'vue'
import axios from 'axios'
import { FieldVardef } from '@/store/modules'
import { useLanguagesStore } from '@/store/languages'
import { usePopupsStore } from '@/store/popups'
import MintPopupRelate from '@/components/MintPopups/MintPopupRelate.vue'
import MintButton from '@/components/MintButtons/MintButton.vue'
import he from 'he'

interface Props {
    defs: FieldVardef
    label: string
    modelValue?: any
    data?: any
}

const props = defineProps<Props>()
const emit = defineEmits(['update:modelValue'])

const DEBOUNCE_TIME = 500
let debounceTimeout: number | null = null

const languages = useLanguagesStore()
const popupsStore = usePopupsStore()
const menuOpen = ref(false)
const items = ref(
    props.data.bean[props.defs.id_name]
        ? [
              {
                  id: props.data.bean[props.defs.id_name],
                  name: props.modelValue,
              },
          ]
        : [],
)
const currentRecordItem = ref({ id: props.data.bean[props.defs.id_name], name: props.data.bean[props.defs.name] })
const recordModel = computed({
    get() {
        return currentRecordItem.value
    },
    set(newVal) {
        props.data.bean[props.defs.id_name] = newVal.id
        currentRecordItem.value = newVal
        emit('update:modelValue', [props.defs.id_name])
    },
})
const currentTypeItem = ref('')
const parentModel = computed({
    get() {
        return languages.translateListValue(
            props.data.bean.parent_type ?? props.defs?.default ?? '',
            props.defs?.options,
        )
    },
    set(newValue) {
        let optionKeys = languages.languages.app_list_strings[props.defs?.options]
        let selectedKey = Object.keys(optionKeys).find((key) => optionKeys[key] === newValue)
        props.data.bean[props.defs.type_name] = selectedKey
        currentTypeItem.value = selectedKey
        recordModel.value = { id: '', name: '' }
        emit('update:modelValue', [props.defs.type_name])
    },
})
const isLoading = ref(false)
async function fetchRecordItems(e) {
    if (recordModel.value.name.length >= 3) {
        items.value = []
        isLoading.value = true
        menuOpen.value = true
        const val = e?.target?.value ?? props.data.bean[props.defs.name] ?? ''
        const filter = {
            field: 'name',
            type: 'wildcard',
            value: val.toLowerCase() + '*',
        }
        if (debounceTimeout) {
            clearTimeout(debounceTimeout)
        }
        debounceTimeout = window.setTimeout(async () => {
    const response = await axios.post(`api/${props.data.bean.parent_type}`, {
        offset: 0,
                sortBy: 'name',
                filters: [filter],
    })
    if (response.data?.results?.length) {
        items.value = response.data.results.sort((a, b) => a.name.localeCompare(b.name, 'pl'))
    }
            isLoading.value = false
        }, DEBOUNCE_TIME)
    } else {
        items.value = []
}
    }

function openRelatePopup() {
    popupsStore.showPopup({
        component: MintPopupRelate,
        title: useLanguagesStore().translateListValue(props.data.bean.parent_type, 'moduleList'),
        icon: 'mdi-view-list',
        data: {
            moduleName: props.data.bean.parent_type,
            popupMode: 'single',
            fieldToNameArray: { id: props.defs.id_name, name: props.defs.name },
            onConfirm: (data: string | string[]) => {
                recordModel.value = {
                    id: data.nameToValueArray[props.defs.id_name],
                    name: data.nameToValueArray[props.defs.name],
    }
            },
            onClose: () => {},
        },
    })
}

function clickOnMenuItem(item) {
    recordModel.value = {
        id: item.id,
        name: item.name,
        }
    }

function getHighlightedText(text: string, query: string) {
    query = he.encode(query)
    text = he.encode(text)
    if (!query) {
        return text
    }
    try {
        const regex = new RegExp(
            `\\b(${query
                .split(' ')
                .filter((q) => !['AND', 'OR'].includes(q) && q.length > 2)
                .join('|')})`,
            'gi',
        )
        text = text.replace(regex, '<span class="highlighted">$&</span>')
        return text
    } catch {
        return text
    }
}
</script>

<style scoped lang="scss">
.v-list-item {
    :deep(.highlighted) {
        font-weight: 600;
        color: rgb(var(--v-theme-primary));
        background: rgb(var(--v-theme-primary-light));
    }
}
.parent-container {
    display: flex;
    flex-direction: column;
    width: 100%;
    gap: 24px;
    padding-left: 16px;
    border-left: 1px solid rgb(var(--v-theme-primary-light));
}
</style>
