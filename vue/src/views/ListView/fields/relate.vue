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
    />
</template>

<script setup lang="ts">
import { defineProps, defineEmits, ref, onMounted, computed } from 'vue'
import axios from 'axios'

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

const activeItem = computed(() => items.value.find((item) => item.id === value.value))

onMounted(async () => {
    if (props.input?.value) {
        isLoading.value = true
        const response = await axios.post(`api/${props.fieldDefs.module}`, {
            offset: 0,
            filters: [
                {
                    field: '_id',
                    type: 'equals',
                    value: props.input.value,
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
    if (activeItem.value?.name === query) {
        return
    }
    if (!query) {
        items.value = []
        value.value = null
        emit('update:modelValue', null)
        return
    }
    if (query === prevQuery) {
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
        const response = await axios.post(`api/${props.fieldDefs.module}`, {
            offset: 0,
            sortBy: 'name',
            filters: [
                {
                    field: 'name',
                    type: filterType,
                    value: query,
                    operator: 'AND',
                },
            ],
        })
        isLoading.value = false
        items.value = []
        await autocomplete.value.$nextTick()
        items.value = response.data?.results ?? []
        if (value.value && !items.value.find((item) => item.id === value.value)) {
            value.value = null
        }
    }, DEBOUNCE_DELAY_MS)
}
</script>

<style scoped lang="scss"></style>
