<template>
    <div class="mint-date-field-detail">
        <v-text-field
            v-model="dateValue"
            variant="outlined"
            :label="input.label"
            autocomplete="off"
            :error="!isValidDate"
            hide-details
            density="compact"
            :disabled="disabled"
        >
            <template #append-inner>
                <v-menu v-model="datePickerMenu" offset="16" :close-on-content-click="false">
                    <template v-slot:activator="{ props }">
                        <v-icon class="mint-date-field-btn" v-bind="props">mdi-calendar</v-icon>
                    </template>
                    <v-date-picker v-model="pickerValue" hide-actions :first-day-of-week="firstDayOfWeek">
                        <template #header></template>
                    </v-date-picker>
                </v-menu>
            </template>
        </v-text-field>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { DateTime } from 'luxon'
import { usePreferencesStore } from '@/store/preferences'
import { useMintDate } from '@/composables/useMintDate'

const emit = defineEmits(['update:modelValue'])
const props = defineProps(['input', 'disabled'])
const preferences = usePreferencesStore()
const datePickerMenu = ref(false)
const model = ref(useMintDate(props.input?.value))

const isValidDate = computed(() => {
    return !model.value.formatted.db_date || model.value.formatted.db_date.length === 10
})

const firstDayOfWeek = computed(() => preferences.user?.first_day_of_week ?? 1)

const dateValue = computed({
    get() {
        return model.value.isValid ? model.value.formatted.user_date : ''
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
            emit('update:modelValue', model.value.formatted.db_date)
        }
    },
})

const pickerValue = computed({
    get() {
        return model.value.isValid ? model.value.formatted.js_date : new Date()
    },
    set(newVal) {
        const dt = DateTime.fromJSDate(newVal)
        if (!dt.isValid) {
            return
        }
        const year = dt.year
        const month = String(dt.month).padStart(2, '0')
        const day = String(dt.day).padStart(2, '0')
        const dateString = `${year}-${month}-${day}`

        model.value.set(dateString)
        datePickerMenu.value = false
        emit('update:modelValue', model.value.formatted.db_date)
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
        &:hover {
            color: rgb(var(--v-theme-on-surface));
        }
    }
}
</style>
