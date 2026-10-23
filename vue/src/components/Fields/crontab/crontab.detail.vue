<template>
    <div class="crontab-detail">
        <span class="text-body-2">{{ label }}</span>
        <span class="text-body-1 crontab-value">{{ humanReadable }}</span>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { FieldProps } from '../Field.model'
import { useLanguagesStore } from '@/store/languages'

const props = defineProps<FieldProps>()
const languages = useLanguagesStore()

const SCHEDULER_MODULE = 'Schedulers'

function translate(key: string): string {
    return languages.label(key, SCHEDULER_MODULE) || key
}

const DAY_LABELS: Record<string, string> = {
    '1': 'LBL_MON',
    '2': 'LBL_TUE',
    '3': 'LBL_WED',
    '4': 'LBL_THU',
    '5': 'LBL_FRI',
    '6': 'LBL_SAT',
    '0': 'LBL_SUN',
    '*': 'LBL_ALL',
}

function formatIntervalSegment(
    type: number,
    value: string,
    mins: string,
    hours: string,
): string {
    switch (type) {
        case 0: // minutes
            if (value === '0') {
                return translate('LBL_ON_THE').trim() + translate('LBL_HOUR_SING')
            }
            if (!/[^0-9]/.test(hours) && !/[^0-9]/.test(value)) {
                return ''
            }
            if (/\*\//.test(value)) {
                return value.replace('*/', '') + translate('LBL_MINUTES')
            }
            if (!/[^0-9]/.test(value)) {
                return translate('LBL_ON_THE') + value + translate('LBL_MIN_MARK')
            }
            return value

        case 1: { // hours
            if (/\*\//.test(value)) {
                return value.replace('*/', '') + translate('LBL_HOUR')
            }
            if (/[^0-9]/.test(mins)) {
                return value
            }
            const hourNum = parseInt(value, 10)
            const minutePadded = mins.padStart(2, '0')
            const date = new Date(2000, 0, 1, hourNum, parseInt(minutePadded, 10))
            return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
        }

        case 2: // day of month
            if (/\*\//.test(value)) {
                return value.replace('*/', '') + ' ' + translate('LBL_DAY_OF_MONTH')
            }
            if (/\*/.test(value)) return value
            return toOrdinal(parseInt(value, 10))

        case 3: { // month
            if (/\*\//.test(value)) {
                return value.replace('*/', '') + ' ' + translate('LBL_MONTHS')
            }
            const monthNum = parseInt(value, 10)
            if (isNaN(monthNum)) return value
            const date = new Date(2005, monthNum - 1, 1)
            return date.toLocaleString(undefined, { month: 'long' })
        }

        case 4: // day of week
            return translate(DAY_LABELS[value] || 'LBL_ALL')

        default:
            return value
    }
}

function toOrdinal(number: number): string {
    const suffixes = ['th', 'st', 'nd', 'rd']
    const remainder = number % 100
    return number + (suffixes[(remainder - 20) % 10] || suffixes[remainder] || suffixes[0])
}

function processInterval(
    key: number,
    interval: string,
    mins: string,
    hours: string,
): string {
    if (interval === '*' || interval === '*/1') return ''

    let result = ''
    const separator = translate('LBL_AND')

    if (interval.includes(',')) {
        const parts = interval.split(',')
        for (const part of parts) {
            if (part.includes('-')) {
                const rangeParts = part.split('-')
                for (const rangeValue of rangeParts) {
                    if (result) result += separator
                    result += formatIntervalSegment(key, rangeValue, mins, hours)
                }
            } else {
                if (result) result += separator
                result += formatIntervalSegment(key, part, mins, hours)
            }
        }
    } else if (interval.includes('-')) {
        const rangeParts = interval.split('-')
        result += translate('LBL_FROM')
        result += formatIntervalSegment(key, rangeParts[0], mins, hours)
        result += translate('LBL_RANGE')
        result += formatIntervalSegment(key, rangeParts[1], mins, hours)
    } else if (interval.includes('*/')) {
        result += translate('LBL_EVERY')
        result += formatIntervalSegment(key, interval, mins, hours)
    } else {
        result += formatIntervalSegment(key, interval, mins, hours)
    }

    return result
}

const humanReadable = computed(() => {
    const val = props.modelValue
    if (!val || typeof val !== 'string') return ''

    const parts = val.split('::')
    if (parts.length !== 5) return val

    const [min, hour] = parts

    if (parts.every((p) => p === '*')) {
        return translate('LBL_OFTEN')
    }

    const segments: string[] = []
    for (let i = 0; i < 5; i++) {
        const segment = processInterval(i, parts[i], min, hour)
        if (segment) segments.push(segment)
    }

    return segments.join('; ').replace(/;\s*$/, '').trim()
})
</script>

<style scoped lang="scss">
.crontab-detail {
    display: flex;
    flex-direction: column;
    gap: 2px;

    .text-body-2 {
        font-size: 12px;
        color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
    }

    .crontab-value {
        color: rgb(var(--v-theme-secondary));
        font-weight: 500;
    }
}
</style>
