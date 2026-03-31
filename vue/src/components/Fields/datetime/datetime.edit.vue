<template>
    <div class="mint-date-field-detail" @keyup.enter="$emit('inlineEditSave')" @keyup.esc="$emit('inlineEditCancel')">
        <v-text-field
            :label="label"
            variant="outlined"
            density="compact"
            hide-details
            v-model="dateValue"
            :error="props.state === 'error'"
            :name="props.defs.name"
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
        <v-text-field :disabled="!dateValue" variant="outlined" density="compact" hide-details v-model="timeValue" :name="props.defs.name+'_time'">
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
import { defineProps, ref, computed, watch, defineEmits } from 'vue'
import { DateTime } from 'luxon'
import { FieldProps } from '../Field.model'
import { usePreferencesStore } from '@/store/preferences'
import DateUtils from '@/utils/dates'
import { MintDate } from '@/composables/useMintDate'

const props = defineProps<FieldProps<MintDate>>()
const emit = defineEmits(['update:modelValue'])

const datePickerMenu = ref(false)
const timePickerMenu = ref(false)
const model = ref(props.field.model)
const preferences = usePreferencesStore()

const allowedMinutesStep = (m: number) => m % (props.minuteStep || 5) === 0

const timeFormat = computed(() => {
    return DateUtils.getTimeFormatGeneralized()
})

const dateValue = computed({
    get() {
        if (!model.value.isValid) return ''
        const userDt = model.value.instance.setZone(preferences.userTimezone || 'utc')
        return userDt.toFormat(preferences.userDateFormat)
    },
    set(newVal) {
        datePickerMenu.value = false
        if (!newVal?.trim()) {
            model.value.clear()
            return
        }
        const dt = DateTime.fromFormat(
            `${newVal} ${timeValue.value}`,
            `${preferences.userDateFormat} HH:mm`,
            { zone: preferences.userTimezone || 'utc' }
        )
        if (dt.isValid) {
            model.value.set(dt)
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
            `${preferences.userDateFormat} HH:mm`,
            { zone: preferences.userTimezone || 'utc' }
        )
        if (dt.isValid) {
            model.value.set(dt)
        }
    },
})

const datePickerValue = computed({
    get() {
        if (!model.value.isValid) return new Date()
        const userDt = model.value.instance.setZone(preferences.userTimezone || 'utc')
        return new Date(userDt.year, userDt.month - 1, userDt.day)
    },
    set(newVal) {
        const year = newVal.getFullYear()
        const month = String(newVal.getMonth() + 1).padStart(2, '0')
        const day = String(newVal.getDate()).padStart(2, '0')
        const dateStr = `${year}-${month}-${day}`

        let timeStr: string
        if (timeFormat.value === 'ampm') {
            timeStr = model.value.isValid ? model.value.formatted.user_time.replace('.', ':') : '12:00 PM'
        } else {
            timeStr = model.value.isValid ? model.value.formatted.user_time_normal.slice(0, -3) : '12:00'
        }

        const formatedDatetime = DateTime.fromFormat(
            `${dateStr} ${timeStr}`,
            `yyyy-MM-dd ${timeFormat.value == 'ampm' ? 'hh:mm a' : 'HH:mm'}`,
            { zone: preferences.userTimezone || 'utc' }
        )
        if (formatedDatetime.isValid) {
            model.value.set(formatedDatetime)
        }
        datePickerMenu.value = false
    },
})

watch(
    () => props.field.model,
    () => {
        model.value = props.field.model
    },
)
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
