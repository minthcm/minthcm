<template>
    <v-select
        ref="vSelectRef"
        :label="props.label"
        variant="outlined"
        density="compact"
        hide-details
        :error="props.state === 'error'"
        v-model="parsedValue"
        :items="items"
        item-title="value"
        item-value="key"
        @keydown="onKeyDown"
        @update:menu="onMenuUpdate"
    >
    </v-select>
</template>

<script setup lang="ts">
import { ref, computed, watch, nextTick, onUnmounted } from 'vue'
import { useLanguagesStore } from '@/store/languages'
import { FieldProps } from '../Field.model'

const props = defineProps<FieldProps>()
const languages = useLanguagesStore()
const emit = defineEmits(['update:modelValue'])
const model = ref('')

const vSelectRef = ref<any>(null)

let searchBuffer = ''
let searchTimer: ReturnType<typeof setTimeout> | null = null

function clearSearch() {
    if (searchTimer) {
        clearTimeout(searchTimer)
        searchTimer = null
    }
    searchBuffer = ''
}

function getActiveListbox(): HTMLElement | null {
    return document.querySelector('.v-overlay--active [role="listbox"]')
}

// After the open animation, focus the selected (or first) list item for visual clarity.
// Vuetify's onAfterEnter would do this, but only when isFocused=true — and clicking the
// chevron arrow does NOT set isFocused, so we handle it ourselves.
function focusActiveListItem() {
    if (!vSelectRef.value?.menu) {
        return
    }
    const listbox = getActiveListbox()
    if (!listbox) {
        return
    }
    const active = (listbox.querySelector('[aria-selected="true"]')
        ?? listbox.querySelector('[role="option"]:not([aria-disabled="true"])')) as HTMLElement | null
    if (!active) {
        return
    }
    active.scrollIntoView({ block: 'nearest' })
    active.focus()
}

// Capture-phase document listener active while the menu is open.
// Intercepts printable keys and Enter for ALL focus states (input OR list item) so that
// Vuetify's onKeydown on VTextField never fires — which would call listRef.focus() and
// fight our scrollTop updates. Arrow keys are left to Vuetify's VList navigation.
function handleGlobalKeyDown(e: KeyboardEvent) {
    if (!vSelectRef.value?.menu) {
        return
    }
    if (e.key === 'Enter' || (e.key.length === 1 && !e.ctrlKey && !e.metaKey && !e.altKey)) {
        e.stopPropagation()
        onKeyDown(e)
    } else if (e.key === 'ArrowUp' || e.key === 'ArrowDown') {
        clearSearch()
    }
}

function onMenuUpdate(open: boolean) {
    if (!open) {
        clearSearch()
        document.removeEventListener('keydown', handleGlobalKeyDown, true)
    } else {
        document.addEventListener('keydown', handleGlobalKeyDown, true)
        setTimeout(focusActiveListItem, 200)
    }
}

function onKeyDown(e: KeyboardEvent) {
    if (e.key === 'Enter' && vSelectRef.value?.menu) {
        const listbox = getActiveListbox()
        if (listbox) {
            const focused = document.activeElement as HTMLElement | null
            if (focused && listbox.contains(focused)) {
                const title = focused.querySelector('.v-list-item-title')?.textContent?.trim()
                if (title) {
                    const keyboardItem = items.value.find(i => String(i.value).trim() === title)
                    if (keyboardItem) {
                        parsedValue.value = keyboardItem.key
                    }
                }
            }
        }
        clearSearch()
        nextTick(() => {
            if (vSelectRef.value) {
                vSelectRef.value.menu = false
            }
        })
        return
    }

    if (e.key.length === 1 && !e.ctrlKey && !e.metaKey && !e.altKey) {
        if (searchTimer) {
            clearTimeout(searchTimer)
        }
        const pressedChar = e.key.toLowerCase()
        searchBuffer += pressedChar

        // Try the accumulated multi-char buffer first (e.g. "wo" → "Workshop").
        let match = items.value.find(item =>
            String(item.value ?? '').toLowerCase().startsWith(searchBuffer)
        )

        if (match === undefined) {
            // Extended buffer matched nothing — reset to the single pressed character
            // and cycle to the next item that starts with it, advancing past the
            // current selection so repeated presses walk through all matching items.
            searchBuffer = pressedChar
            const charMatches = items.value.filter(item =>
                String(item.value ?? '').toLowerCase().startsWith(pressedChar)
            )
            if (charMatches.length > 0) {
                const currentIdx = charMatches.findIndex(item => item.key === parsedValue.value)
                const nextIdx = currentIdx === -1 ? 0 : (currentIdx + 1) % charMatches.length
                match = charMatches[nextIdx]
            }
        }

        if (match !== undefined) {
            const selectedMatch = match
            const matchIndex = items.value.findIndex(i => i.key === selectedMatch.key)
            parsedValue.value = selectedMatch.key
            nextTick(() => {
                const listbox = getActiveListbox()
                if (!listbox || matchIndex < 0) {
                    return
                }
                // Drive scrollTop directly so the matched item is centered regardless of
                // whether it is already in the visible range.
                const anyItem = listbox.querySelector('[role="option"]') as HTMLElement | null
                const itemHeight = anyItem?.offsetHeight ?? 36
                listbox.scrollTop = Math.max(0, matchIndex * itemHeight - listbox.clientHeight / 2 + itemHeight / 2)
                // Move the focus ring to the matched item. Works for items already in the
                // virtual scroll window; no-op for far items (not yet in DOM after scroll).
                const active = listbox.querySelector('[aria-selected="true"]') as HTMLElement | null
                active?.focus()
            })
        }

        searchTimer = setTimeout(clearSearch, 800)
    } else if (e.key === 'ArrowUp' || e.key === 'ArrowDown') {
        clearSearch()
    }
}

onUnmounted(() => {
    document.removeEventListener('keydown', handleGlobalKeyDown, true)
})

const parsedValue = computed({
    get() {
        return items.value.find((item) => item.key === props.field.model)?.key || ''
    },
    set(newValue) {
        model.value = newValue
        props.field.model = newValue
        emit('update:modelValue', model.value)
    },
})

const items = computed(() => {
    const options = props.options ?? props.defs?.options
    if (!options) {
        return []
    }
    if (typeof options === 'string') {
        return languages.getList(options)
    }
    if (!Array.isArray(options) && typeof options === 'object') {
        return Object.entries(options).map(([key, value]) => ({
            key,
            value,
        }))
    }
    return options
})

watch(items, () => {
    if (!items.value.find((item) => item.key === model.value)) {
        const newItem = items.value.find((item) => !item.key) || items.value[0]
        model.value = newItem?.key || ''
        props.field.model = newItem?.key ?? ''
        emit('update:modelValue', model.value)
    }
})
</script>

<style scoped lang="scss"></style>
