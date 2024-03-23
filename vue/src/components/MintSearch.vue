<template>
    <v-text-field
        v-model="searchQuery"
        variant="plain"
        :class="{
            'search-input': true,
        }"
        @input="emit('update:modelValue', $event.target.value)"
        :placeholder="props.label"
        hide-details
    >
        <template #prepend-inner>
            <v-fab-transition class="search-prepend-icon">
                <v-icon v-if="standardizedQuery" icon="mdi-close" @click="clear" />
                <v-icon v-else icon="mdi-magnify" />
            </v-fab-transition>
        </template>
    </v-text-field>
</template>

<script setup lang="ts">
import { defineProps, withDefaults, defineEmits, ref, computed } from 'vue'

const searchQuery = ref<string | null>('')
const standardizedQuery = computed(() => {
    return searchQuery.value?.trim()
})

interface Props {
    label?: string
    debounce?: number
}

const props = withDefaults(defineProps<Props>(), {
    label: 'Search',
    debounce: 1000,
})

const emit = defineEmits(['debounce', 'update:modelValue', 'clear'])

function clear() {
    searchQuery.value = ''
    emit('clear')
}
</script>

<style scoped lang="scss">
.search-input {
    .search-prepend-icon {
        margin: 0px 10px;
        top: -4px;
        opacity: 1;
        color: rgb(var(--v-theme-secondary));
    }
    :deep(.v-field__input) {
        padding-top: 0px;
    }
}
</style>
