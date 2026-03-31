import { DateTime } from 'luxon'
import { computed, Ref, toValue } from 'vue'

interface DataPeriod {
    dateFrom: Ref<string>
    dateTo: Ref<string>
}

interface DataPositionConfig {
    minDate: Ref<string>
    maxDate: Ref<string>
}

export const useDataPosition = (period: DataPeriod, config: DataPositionConfig) => {
    const isOverflowLeft = computed(() => {
        return toValue(period.dateFrom) < toValue(config.minDate)
    })
    const isOverflowRight = computed(() => {
        return toValue(period.dateTo) > toValue(config.maxDate)
    })

    const timelineSeconds = computed(() => {
        const minDt = DateTime.fromFormat(toValue(config.minDate), 'yyyy-MM-dd HH:mm:ss', { zone: 'UTC' })
        const maxDt = DateTime.fromFormat(toValue(config.maxDate), 'yyyy-MM-dd HH:mm:ss', { zone: 'UTC' })
        if (!minDt.isValid || !maxDt.isValid) {
            return 0
        }
        return maxDt.diff(minDt, 'seconds').seconds
    })

    const left = computed(() => {
        if (isOverflowLeft.value) {
            return '0%'
        }
        return (
            (DateTime.fromSQL(toValue(period.dateFrom), { zone: 'UTC' }).diff(
                DateTime.fromSQL(toValue(config.minDate), { zone: 'UTC' }),
                'seconds',
            ).seconds /
                timelineSeconds.value) *
                100 +
            '%'
        )
    })

    const right = computed(() => {
        if (isOverflowRight.value) {
            return '0%'
        }
        return (
            (DateTime.fromSQL(toValue(config.maxDate), { zone: 'UTC' }).diff(
                DateTime.fromSQL(toValue(period.dateTo), { zone: 'UTC' }),
                'seconds',
            ).seconds /
                timelineSeconds.value) *
                100 +
            '%'
        )
    })

    const isVisible = computed(() => {
        return (
            toValue(period.dateTo) >= toValue(period.dateFrom) &&
            toValue(period.dateTo) >= toValue(config.minDate) &&
            toValue(period.dateFrom) <= toValue(config.maxDate)
        )
    })

    const style = computed(() => {
        const style: { [key: string]: string } = {}
        if (isVisible.value) {
            style.left = left.value
            style.right = right.value
        } else {
            style.display = 'none'
        }
        return style
    })

    return {
        style,
    }
}
