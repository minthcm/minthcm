<template>
    <div class="mint-date-field-detail">
        <v-text-field
            v-bind="props"
            :label="label"
            variant="outlined"
            density="compact"
            hide-details
            :name="props.defs.name"
            :error="props.state === 'error'"
            v-model="parsedValue"
        />
        <v-menu v-model="datePickerMenu" offset="16" :close-on-content-click="false">
            <template v-slot:activator="{ props, isActive }">
                <v-icon class="mint-date-field-btn" v-bind="props">mdi-calendar</v-icon>
            </template>
            <v-date-picker v-model="pickerValue" hide-actions>
                <template #header></template>
            </v-date-picker>
        </v-menu>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { FieldProps } from '../Field.model'
import { MintDate } from '@/composables/useMintDate'
import { DateTime } from 'luxon'
import { usePreferencesStore } from '@/store/preferences'

const props = defineProps<FieldProps<MintDate>>()

const emit = defineEmits(['update:modelValue'])

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
