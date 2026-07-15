<template>
    <div class="mint-date-field-detail">
        <v-text-field
            :label="label"
            variant="outlined"
            density="compact"
            hide-details
            :name="props.defs.name"
            :error="props.state === 'error'"
            v-model="parsedValue"
        >
            <template #append-inner>
                <v-menu
                    v-model="datePickerMenu"
                    offset="16"
                    :close-on-content-click="false"
                >
                    <template v-slot:activator="{ props: menuProps }">
                        <v-btn
                        icon
                        variant="text"
                        class="mint-date-field-btn"
                        v-bind="menuProps"
                        @keydown.enter.prevent="datePickerMenu = true"
                        @keydown.space.prevent="datePickerMenu = true"
                        >
                        <v-icon>mdi-calendar</v-icon>
                        </v-btn>
                    </template>
                    <v-date-picker
                        v-model="pickerValue"
                        hide-actions
                        @keydown.enter.prevent="onPickerEnter"
                        @keydown.space.prevent="onPickerEnter"
                    >
                        <template #header />
                    </v-date-picker>
                </v-menu>
            </template>
        </v-text-field>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { DateTime } from 'luxon'
import { FieldProps } from '../Field.model'
import { usePreferencesStore } from '@/store/preferences'
import { MintDate } from '@/composables/useMintDate'

const props = defineProps<FieldProps<MintDate>>()
const emit = defineEmits(['update:modelValue'])

const onPickerEnter = (element: KeyboardEvent) => {
    const target = element.target as HTMLElement

    if (target?.classList.contains('v-btn')) {
        target.click()
    }
}

const datePickerMenu = ref(false)
const model = ref(props.modelValue)
const preferences = usePreferencesStore()

const parsedValue = computed({
    get() {
        return model.value.formatted?.user_date || ''
    },
    set(newVal) {
        datePickerMenu.value = false
        if (!newVal?.trim()) {
            model.value.clear()
            return
        }
        const dt = DateTime.fromFormat(newVal, preferences.userDateFormat || 'yyyy-MM-dd', { zone: 'utc' })
        if (dt.isValid) {
            model.value.set(dt)
        }
    },
})
const pickerValue = computed({
    get() {
        return model.value.isValid ? model.value.formatted.js_date : new Date()
    },
    set(newVal) {
        // Get date components without timezone conversion
        const year = newVal.getFullYear()
        const month = String(newVal.getMonth() + 1).padStart(2, '0')
        const day = String(newVal.getDate()).padStart(2, '0')
        const dateString = `${year}-${month}-${day}`
        
        model.value.set(dateString)
        emit('update:modelValue', model.value)
        datePickerMenu.value = false
    },
})
</script>

<style scoped lang="scss">
.mint-date-field-detail {
    display: flex;
    gap: 16px;
    align-items: center;

    .mint-date-field-btn {
        transition: all 100ms ease-out;
        cursor: pointer;
        color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
        &:hover {
            color: rgba(var(--v-theme-on-surface), var(--v-high-emphasis-opacity));
        }
    }
}
</style>
