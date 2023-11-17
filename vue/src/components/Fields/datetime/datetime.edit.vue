<template>
    <div class="mint-date-field-detail">
        <v-text-field :label="label" variant="outlined" density="compact" hide-details v-model="dateValue">
            <template #append-inner>
                <v-menu v-model="datePickerMenu" offset="16" :close-on-content-click="false">
                    <template v-slot:activator="{ props, isActive }">
                        <v-icon class="mint-date-field-btn" v-bind="props">mdi-calendar</v-icon>
                    </template>
                    <v-date-picker v-model="pickerValue" hide-actions>
                        <template #header></template>
                    </v-date-picker>
                </v-menu>
            </template>
        </v-text-field>
        <v-text-field :disabled="!dateValue" variant="outlined" density="compact" type="time" hide-details v-model="timeValue" />
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { DateTime } from 'luxon'
import { FieldVardef } from '@/store/modules'
import { VDatePicker } from 'vuetify/labs/VDatePicker'

interface Props {
    defs: FieldVardef
    label: string
    modelValue?: any
    data?: any
}

const props = defineProps<Props>()
const emit = defineEmits(['update:modelValue'])

const datePickerMenu = ref(false)
const model = ref(props.modelValue)

const dateValue = computed({
    get() {
        const dt = DateTime.fromSQL(model.value)
        if (dt.isValid) {
            return dt.toFormat('dd.MM.yyyy') || ''
        }
        return ''
    },
    async set(newVal) {
        datePickerMenu.value = false
        if (!newVal?.trim()) {
            model.value = ''
        }
        const dt = DateTime.fromFormat(newVal, 'dd.MM.yyyy')
        if (dt.isValid) {
            model.value = dt.toSQLDate()
        }
    },
})

const timeValue = computed({
    get() {
        const dt = DateTime.fromSQL(model.value, { zone: 'UTC' })
        if (dt.isValid) {
            return dt.toLocal().toFormat('HH:mm') || '00:00'
        }
        return '00:00'
    },
    set(newVal) {
        newVal = DateTime.fromSQL(newVal).setZone('UTC').toFormat('HH:mm')
        const modelDt = DateTime.fromSQL(model.value, { zone: 'UTC' })
        if (modelDt.isValid) {
            model.value = `${modelDt.toFormat('yyyy-MM-dd')} ${newVal}:00`
        } else {
            model.value = ''
        }
    },
})

const pickerValue = computed({
    get() {
        if (!model.value?.trim()) {
            return new Date()
        }
        return new Date(model.value)
    },
    set(newVal) {
        const dt = DateTime.fromJSDate(newVal)
        if (dt.isValid) {
            const modelDt = DateTime.fromSQL(model.value)
            if (!modelDt.isValid) {
                model.value = dt.toFormat('yyyy-MM-dd HH:mm:ss')
            } else {
                model.value = `${dt.toFormat('yyyy-MM-dd')} ${modelDt.toFormat('HH:mm:ss')}`
            }
        }
    },
})

watch(model, (newVal) => {
    datePickerMenu.value = false
    const dt = DateTime.fromSQL(newVal?.toString())
    if (dt.isValid) {
        console.log('new val', model.value)
        emit('update:modelValue', model.value)
    } else {
        console.log('new val empty')
        emit('update:modelValue', '')
    }
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
.v-input {
    :deep(.v-field__outline__start),
    :deep(.v-field__outline__notch)::before,
    :deep(.v-field__outline__notch)::after,
    :deep(.v-field__outline__end) {
        opacity: 1;
    }

    :deep(.v-field__outline__start),
    :deep(.v-field__outline__notch)::before,
    :deep(.v-field__outline__notch)::after,
    :deep(.v-field__outline__end) {
        border-color: #dbdbdb;
    }

    &:hover {
        :deep(.v-field__outline__start),
        :deep(.v-field__outline__notch)::before,
        :deep(.v-field__outline__notch)::after,
        :deep(.v-field__outline__end) {
            border-color: rgb(var(--v-theme-primary));
        }
    }

    :deep(.v-field--focused .v-field__outline__start),
    :deep(.v-field--focused .v-field__outline__notch)::before,
    :deep(.v-field--focused .v-field__outline__notch)::after,
    :deep(.v-field--focused .v-field__outline__end) {
        border-color: rgb(var(--v-theme-primary));
    }

    :deep(.v-field-label.v-field-label--floating) {
        background: rgb(var(--v-theme-surface));
        color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
        opacity: 1;
        padding: 0px 2px;
    }
}
</style>
