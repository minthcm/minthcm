<template>
    <div class="parent-container">
        <v-autocomplete
            class="flex-grow-1"
            :items="parentTypeOptions"
            :label="languages.label('LBL_ASSIGNED_TO_MODULE')"
            :title="parentModel"
            variant="outlined"
            density="compact"
            hide-details
            :error="props.state === 'error'"
            v-model="parentModel"
            v-bind="$attrs"
            item-value="key"
            item-title="value"
            :name="props.defs.name + '_module'"
        /> 
        <v-menu v-model="menuOpen" :location="'bottom'">
            <template v-slot:activator="val">
            <v-text-field
                class="flex-grow-1"
                :label="languages.label('LBL_ASSIGNED_TO_RECORD')"
                variant="outlined"
                density="compact"
                hide-details
                :name="props.defs.name"
                :title="recordModel.name"
                v-model="recordModel.name"
                v-bind="val.props"
                @input="(event) => fetchRecordItems(event)"
                @click="menuOpen = true"
            >
                <template #append-inner>
                <v-fab-transition class="search-prepend-icon">
                    <v-icon
                    v-if="recordModel.name"
                    icon="mdi-close"
                    @click="recordModel = { id: '', name: '' }"
                    />
                    <v-icon v-else icon="mdi-magnify" @click.stop="openRelatePopup" />
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
                <v-list-item
                @click="clickOnMenuItem(item)"
                v-for="(item, index) in items"
                :key="index"
                >
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
import { computed, ref, watch } from 'vue'
import { useModulesStore } from '@/store/modules'
import { useLanguagesStore } from '@/store/languages'
import { usePopupsStore } from '@/store/popups'
import MintPopupRelate from '@/components/MintPopups/MintPopupRelate.vue'
import MintButton from '@/components/MintButtons/MintButton.vue'
import { modulesApi } from '@/api/modules.api'
import he from 'he'
import getFilters from '@/utils/qsOperators'
import { FieldProps } from '../Field.model'
import { provideSSRWidth } from '@vueuse/core'

const props = defineProps<FieldProps>()
const emit = defineEmits(['update:modelValue'])

const DEBOUNCE_TIME = 500
let debounceTimeout: number | null = null

const languages = useLanguagesStore()
const popupsStore = usePopupsStore()
const modulesStore = useModulesStore()
const menuOpen = ref(false)

const parentTypeOptions = computed(() => {
    if (!Array.isArray(props.options) && typeof props.options === 'object') {
        return Object.entries(props.options).map(([key, value]) => ({
            key,
            value,
        }))
    }
    return props.options
})

const items = ref(
    props.data?.bean.fields[props.defs.id_name]?.model
        ? [
              {
                  id: props.data.bean.fields[props.defs.id_name]?.model || '',
                  name: props.field.model || '',
              },
          ]
        : [],
)
const currentRecordItem = ref({
    id: props.data?.bean.fields[props.defs.id_name]?.model ?? '',
    name: props.data?.bean.fields[props.defs.name]?.model ?? '',
})
const recordModel = computed({
    get() {
        return currentRecordItem.value
    },
    set(newVal) {
        currentRecordItem.value = newVal
        emit('update:modelValue', currentRecordItem.value?.name ?? '', {
            [props.defs.type_name]: currentTypeItem.value,
            [props.defs.id_name]: currentRecordItem.value?.id ?? '',
        })
    },
})
const currentTypeItem = ref(props.data?.bean.fields[props.defs.type_name]?.model ?? '')
const parentModel = computed({
    get() {
        return currentTypeItem.value
    },
    set(newValue) {
        currentTypeItem.value = newValue
        recordModel.value = { id: '', name: '' }
        emit('update:modelValue', '', { [props.defs.type_name]: newValue, [props.defs.id_name]: '' })
    },
})
const isLoading = ref(false)
async function fetchRecordItems(e) {
    if (recordModel.value.name.length >= 3) {
        items.value = []
        isLoading.value = true
        menuOpen.value = true
        const val = e?.target?.value ?? props.data?.bean.fields[props.defs.name]?.model ?? ''
        const predefinedFilters = getFilters(
            modulesStore.modules[currentTypeItem.value].vardefs,
            props.defs.filters && typeof props.defs.filters === 'object' && !Array.isArray(props.defs.filters)
                ? props.defs.filters[currentTypeItem.value]
                : [],
        )
        const filters = {
            ...predefinedFilters,
            must: [
                ...(predefinedFilters.must || []),
                {
                    wildcard: {
                        name: val + '*',
                    },
                },
            ],
        }

        if (debounceTimeout) {
            clearTimeout(debounceTimeout)
        }
        debounceTimeout = window.setTimeout(async () => {
            const response = await modulesApi.getListData(
                currentTypeItem.value,
                '',
                filters,
                0,
                100,
                false,
                null,
                'asc',
            )
            items.value = response.data.results
            isLoading.value = false
        }, DEBOUNCE_TIME)
    } else {
        items.value = []
    }
}
function openRelatePopup() {
    popupsStore.showPopup({
        component: MintPopupRelate,
        title: useLanguagesStore().translateListValue(currentTypeItem.value, 'moduleList'),
        icon: 'mdi-view-list',
        data: {
            moduleName: currentTypeItem.value,
            popupMode: 'single',
            fieldToNameArray: { id: props.defs.id_name, name: props.defs.name },
            filterDefs:
                props.defs.filters && typeof props.defs.filters === 'object' && !Array.isArray(props.defs.filters)
                    ? props.defs.filters[currentTypeItem.value] || []
                    : [],
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

watch(
    () => props.data?.bean.fields[props.defs.id_name]?.model,
    (newVal) => {
        currentRecordItem.value = {
            id: newVal ?? '',
            name: props.data?.bean.fields[props.defs.name]?.model ?? '',
        }
        currentTypeItem.value = props.data?.bean.fields[props.defs.type_name].model ?? ''
    }
)
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
    align-items: center;
    gap: 12px;
    .v-input {
        flex: 1 1 0;
        min-width: 0;
    }
    .v-field__input, .v-select__selection-text {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
}
</style>
