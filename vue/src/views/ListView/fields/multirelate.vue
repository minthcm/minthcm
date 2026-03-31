<template>
    <v-autocomplete
        ref="autocomplete"
        v-model="value"
        @update:model-value="emit('update:modelValue', value)"
        :items="items"
        item-value="id"
        item-title="name"
        :label="props.input.label"
        variant="outlined"
        hide-no-data
        @update:search="fetchItems"
        :loading="isLoading"
        clearable
        chips
        multiple
    />
</template>

<script setup lang="ts">
import { defineProps, defineEmits, ref, onMounted, computed, watch } from 'vue'
import { modulesApi } from '@/api/modules.api'

const DEBOUNCE_DELAY_MS = 500

export interface Props {
    input: any
    fieldDefs: any
}

const props = defineProps<Props>()
const emit = defineEmits(['update:modelValue'])

const autocomplete = ref(null)
const value = ref(null)
const items = ref([])
const isLoading = ref(false)

onMounted(async () => {
    if (props.input?.value) {
        isLoading.value = true
        const response = await modulesApi.getListData(props.fieldDefs.module, '', {
            filter: [
                {
                    terms: {
                        _id: props.input.value,
                    },
                },
            ],
        })
        isLoading.value = false
        items.value = response.data?.results ?? []
        value.value = props.input.value
    }
})

let debounceTimeout: null | number = null
let prevQuery = ''

function fetchItems(query: string) {
    if (query === prevQuery || query == null || query == '') {
        return
    }
    prevQuery = query
    query = query?.trim()?.toLowerCase() ?? ''
    if (debounceTimeout) {
        clearTimeout(debounceTimeout)
    }
    debounceTimeout = setTimeout(async () => {
        const filterType = query.includes(' ') ? 'match' : 'wildcard'
        if (filterType === 'wildcard') {
            query += '*'
        }
        isLoading.value = true
        const response = await modulesApi.getListData(props.fieldDefs.module, '', {
            must: [
                {
                    [filterType]: {
                        name: query,
                    },
                },
            ],
        })
        isLoading.value = false
        await autocomplete.value.$nextTick()
        items.value = items.value.filter(
            (item) => {
                return value.value !== null ? Object.values(value.value).includes(item.id) : false
            }
        )
        const result = response.data?.results.length > 0 ? 
            response.data?.results.filter((result) => !items.value.find((item) => item.id === result.id)) 
            : []

        if (Object.values(result).length > 0) {
            items.value.push(...result)
        }
        
    }, DEBOUNCE_DELAY_MS)
}

watch(
    () => value.value,
    (newValue) => {
        if (newValue !== null && Object.values(newValue).length <= 0) {
            items.value = []
            value.value = null
            emit('update:modelValue', null)
        }
    },
)
</script>

<style scoped lang="scss"></style>
