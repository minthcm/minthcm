<template>
    <div class="mint-date-field-detail" @keyup.enter="$emit('inlineEditSave')" @keyup.esc="$emit('inlineEditCancel')">
        <v-text-field
            :label="dateLabel"
            variant="outlined"
            density="compact"
            hide-details
            v-model="dateValue"
            :error="!isValidDateTime"
        >
            <template #append-inner>
                <v-menu v-model="datePickerMenu" offset="16" :close-on-content-click="false">
                    <template v-slot:activator="{ props }">
                        <v-icon class="mint-date-field-btn" v-bind="props">mdi-calendar</v-icon>
                    </template>
                    <v-date-picker v-model="datePickerValue" hide-actions>
                        <template #header></template>
                    </v-date-picker>
                </v-menu>
            </template>
        </v-text-field>
        <v-text-field
            :disabled="!dateValue"
            variant="outlined"
            density="compact"
            hide-details
            v-model="timeValue"
            :label="timeLabel"
            :error="!isValidDateTime"
        >
            <template #append-inner>
                <v-menu v-model="timePickerMenu" offset="16" :close-on-content-click="false">
                    <template v-slot:activator="{ props }">
                        <v-icon class="mint-date-field-btn" v-bind="props">mdi-clock-time-eight-outline</v-icon>
                    </template>
                    <v-time-picker
                        v-model="timeValue"
                        :format="timeFormat"
                        :ampm-in-title="timeFormat === 'ampm'"
                        :allowed-minutes="allowedMinutesStep"
                        scrollable
                    >
                        <template #header></template>
                    </v-time-picker>
                </v-menu>
            </template>
        </v-text-field>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { DateTime } from 'luxon'
import { usePreferencesStore } from '@/store/preferences'
import DateUtils from '@/utils/dates'
import { useMintDate } from '@/composables/useMintDate'

const emit = defineEmits(['update:modelValue'])
const props = defineProps(['input', 'disabled'])
const value = ref(props.input?.value)
const datePickerMenu = ref(false)
const timePickerMenu = ref(false)
const preferences = usePreferencesStore()
const dateLabel = ref(props.input.label[0])
const timeLabel = ref(props.input.label[1])
const model = ref(useMintDate(props.input?.value))
const isValidDateTime = computed(() => {
    return !value.value || value.value.length === 19
})

const allowedMinutesStep = (m: number) => m % 5 === 0

const timeFormat = computed(() => {
    return DateUtils.getTimeFormatGeneralized()
})

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
        const dt = DateTime.fromFormat(newVal, preferences.user?.date_format || 'yyyy-MM-dd')
        if (dt.isValid) {
            model.value.set(dt)
            emit('update:modelValue', model.value.formatted.db_datetime)
        }
    },
})

const timeValue = computed({
    get() {
        return model.value.isValid ? model.value.formatted.user_time_normal.slice(0, -3) : '12:00'
    },
    set(newVal) {
        const dt = DateTime.fromFormat(
            `${dateValue.value} ${newVal}`,
            `${preferences.user?.date_format || 'yyyy-MM-dd'} HH:mm`
        )
        if (dt.isValid) {
            model.value.set(dt)
            emit('update:modelValue', model.value.formatted.db_datetime)
        }
    },
})

const datePickerValue = computed({
    get() {
        return model.value.isValid ? model.value.formatted.js_date : new Date()
    },
    set(newVal) {
        const dt = DateTime.fromJSDate(newVal)
        if (!dt.isValid) {
            return
        }
        model.value.set(
            DateTime.fromFormat(
                `${dt.toFormat('yyyy-MM-dd')} ${model.value.isValid ? model.value.formatted.user_time : (timeFormat.value == 'ampm' ? '12:00 PM' : '12:00')}`,
                `yyyy-MM-dd ${preferences.user?.time_format || 'HH:mm'}`
            ),
        )
        datePickerMenu.value = false
        emit('update:modelValue', model.value.formatted.db_datetime)
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
