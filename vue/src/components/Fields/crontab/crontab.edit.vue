<template>
    <div class="crontab-edit">
        <v-label class="mb-1 text-caption">{{ props.label }}</v-label>

        <div class="d-flex align-center mb-2">
            <v-btn-toggle v-model="mode" density="compact" mandatory color="secondary" variant="outlined">
                <v-btn value="simple" size="small" variant="flat" :color="mode === 'simple' ? 'secondary' : undefined">
                    {{ languages.label('LBL_BASIC_OPTIONS', "Schedulers") || 'Basic' }}
                </v-btn>
                <v-btn value="advanced" size="small" variant="flat" :color="mode === 'advanced' ? 'secondary' : undefined">
                    {{ languages.label('LBL_ADV_OPTIONS', "Schedulers") || 'Advanced' }}
                </v-btn>
            </v-btn-toggle>
        </div>

        <div v-if="mode === 'simple'" class="crontab-simple d-flex ga-2 flex-wrap">
            <v-select
                :label="languages.label('LBL_MINS', 'Schedulers') || 'min'"
                v-model="simpleParts.min"
                :items="minuteOptions"
                density="compact"
                variant="outlined"
                hide-details
                style="min-width: 100px; max-width: 130px"
                @update:model-value="emitFromSimple"
            />
            <v-select
                :label="languages.label('LBL_HOURS', 'Schedulers') || 'hrs'"
                v-model="simpleParts.hour"
                :items="hourOptions"
                density="compact"
                variant="outlined"
                hide-details
                style="min-width: 100px; max-width: 130px"
                @update:model-value="emitFromSimple"
            />
            <v-select
                :label="languages.label('LBL_DAY_OF_MONTH', 'Schedulers') || 'date'"
                v-model="simpleParts.dom"
                :items="domOptions"
                density="compact"
                variant="outlined"
                hide-details
                style="min-width: 100px; max-width: 130px"
                @update:model-value="emitFromSimple"
            />
            <v-select
                :label="languages.label('LBL_MONTHS', 'Schedulers') || 'mo'"
                v-model="simpleParts.month"
                :items="monthOptions"
                density="compact"
                variant="outlined"
                hide-details
                style="min-width: 100px; max-width: 130px"
                @update:model-value="emitFromSimple"
            />
            <v-select
                :label="languages.label('LBL_DAY_OF_WEEK', 'Schedulers') || 'day'"
                v-model="simpleParts.dow"
                :items="dowOptions"
                item-title="title"
                item-value="value"
                density="compact"
                variant="outlined"
                hide-details
                style="min-width: 100px; max-width: 130px"
                @update:model-value="emitFromSimple"
            />
        </div>

        <v-text-field
            v-else
            v-model="rawValue"
            :placeholder="'*::*::*::*::*'"
            density="compact"
            variant="outlined"
            hide-details
            :error="!isValidRaw"
            @update:model-value="emitRaw"
            @keyup.enter="$emit('inlineEditSave')"
            @keyup.esc="$emit('inlineEditCancel')"
        />
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, watch, computed } from 'vue'
import { FieldProps } from '../Field.model'
import { useLanguagesStore } from '@/store/languages'

const props = defineProps<FieldProps>()
const emit = defineEmits(['update:modelValue', 'inlineEditSave', 'inlineEditCancel'])
const languages = useLanguagesStore()

const mode = ref<'simple' | 'advanced'>('simple')
const rawValue = ref('')

const simpleParts = reactive({
    min: '*',
    hour: '*',
    dom: '*',
    month: '*',
    dow: '*',
})

const SEGMENT_PATTERN = /^[\d*,/\-A-Z]+$/

function isSimpleValue(val: string): boolean {
    const parts = val.split('::')
    if (parts.length !== 5) return false
    return parts.every((p) => SEGMENT_PATTERN.test(p))
}

function parseValue(val: string | undefined, setMode = false) {
    if (!val || typeof val !== 'string') {
        simpleParts.min = '*'
        simpleParts.hour = '*'
        simpleParts.dom = '*'
        simpleParts.month = '*'
        simpleParts.dow = '*'
        rawValue.value = '*::*::*::*::*'
        return
    }

    rawValue.value = val
    const parts = val.split('::')
    if (parts.length === 5 && isSimpleValue(val)) {
        simpleParts.min = parts[0]
        simpleParts.hour = parts[1]
        simpleParts.dom = parts[2]
        simpleParts.month = parts[3]
        simpleParts.dow = parts[4]
        if (setMode) mode.value = 'simple'
    } else {
        if (setMode) mode.value = 'advanced'
    }
}

parseValue(props.modelValue, true)

watch(
    () => props.modelValue,
    (newVal) => parseValue(newVal, true),
)

const minuteOptions = computed(() => {
    const opts = ['*', '*/5', '*/10', '*/15', '*/30']
    for (let i = 0; i <= 59; i++) opts.push(String(i))
    return opts
})

const hourOptions = computed(() => {
    const opts = ['*']
    for (let i = 0; i <= 23; i++) opts.push(String(i))
    return opts
})

const domOptions = computed(() => {
    const opts = ['*']
    for (let i = 1; i <= 31; i++) opts.push(String(i))
    return opts
})

const monthOptions = computed(() => {
    const opts = ['*']
    for (let i = 1; i <= 12; i++) opts.push(String(i))
    return opts
})

const dowOptions = computed(() => [
    { value: '*', title: languages.label('LBL_ALL', 'Schedulers') || 'All' },
    { value: '1', title: languages.label('LBL_MON', 'Schedulers') || 'Mon' },
    { value: '2', title: languages.label('LBL_TUE', 'Schedulers') || 'Tue' },
    { value: '3', title: languages.label('LBL_WED', 'Schedulers') || 'Wed' },
    { value: '4', title: languages.label('LBL_THU', 'Schedulers') || 'Thu' },
    { value: '5', title: languages.label('LBL_FRI', 'Schedulers') || 'Fri' },
    { value: '6', title: languages.label('LBL_SAT', 'Schedulers') || 'Sat' },
    { value: '0', title: languages.label('LBL_SUN', 'Schedulers') || 'Sun' },
])

const isValidRaw = computed(() => {
    if (!rawValue.value) return true
    const parts = rawValue.value.split('::')
    return parts.length === 5 && parts.every((p) => SEGMENT_PATTERN.test(p))
})

function emitFromSimple() {
    const val = `${simpleParts.min}::${simpleParts.hour}::${simpleParts.dom}::${simpleParts.month}::${simpleParts.dow}`
    rawValue.value = val
    if (props.field) {
        props.field.model = val
    }
    emit('update:modelValue', val)
}

function emitRaw(val: string) {
    if (!isValidRaw.value) return
    if (props.field) {
        props.field.model = val
    }
    emit('update:modelValue', val)
}
</script>

<style scoped lang="scss">
.crontab-edit {
    width: 100%;
}
</style>
