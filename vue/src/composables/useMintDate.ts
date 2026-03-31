import { ref, computed, watch, UnwrapRef } from 'vue'
import { DateTime, DurationLike } from 'luxon'
import { usePreferencesStore } from '@/store/preferences'

type DateInput = string | 'now' | null | undefined | Date | DateTime

export type MintDate = UnwrapRef<ReturnType<typeof useMintDate>>

export const useMintDate = (input?: DateInput) => {
    const instance = ref<DateTime>(toLuxon(input))

    const isValid = computed(() => {
        return instance.value.isValid
    })

    const formatted = computed(() => {
        if (!isValid.value) {
            return {
                iso: null,
                db_date: null,
                db_time: null,
                db_datetime: null,
                user_date: null,
                user_time: null,
                user_time_normal: null,
                user_datetime: null,
                js_date: null,
            }
        }
        const preferences = usePreferencesStore()
        const userInstance = instance.value.setZone(preferences.userTimezone)
        return {
            iso: instance.value.toFormat("yyyy-MM-dd'T'HH:mm:ssZZ"),
            db_date: instance.value.toFormat('yyyy-MM-dd'),
            db_time: instance.value.toFormat('HH:mm:ss'),
            db_datetime: instance.value.toFormat('yyyy-MM-dd HH:mm:ss'),
            user_date: instance.value.toFormat(preferences.userDateFormat),
            user_time: userInstance.toFormat(preferences.userTimeFormat),
            user_time_normal: userInstance.toFormat('HH:mm:ss'),
            user_datetime: userInstance.toFormat(preferences.userDatetimeFormat),
            js_date: instance.value.toJSDate(),
        }
    })

    function modify(duration: DurationLike): DateTime {
        instance.value = instance.value.plus(duration)
        return instance.value
    }

    function set(date: DateInput): DateTime {
        instance.value = toLuxon(date)
        return instance.value
    }

    function clear(): DateTime {
        instance.value = DateTime.invalid('empty')
        return instance.value
    }

    watch(
        () => input,
        () => {
            instance.value = toLuxon(input)
        },
    )

    return {
        instance,
        isValid,
        formatted,
        modify,
        set,
        clear,
    }
}

function toLuxon(input: DateInput): DateTime {
    if (input === null || input === '') {
        return DateTime.invalid('empty')
    }
    if (!input || input === 'now') {
        return DateTime.utc()
    }
    if (typeof input === 'string') {
        let dt = DateTime.fromISO(input, { zone: 'utc' })
        if (!dt.isValid) {
            dt = DateTime.fromSQL(input, { zone: 'utc' })
        }
        return dt
    }
    if (input instanceof Date) {
        return DateTime.fromJSDate(input)
    }
    if (DateTime.isDateTime(input)) {
        return input.setZone('utc')
    }
    return DateTime.utc()
}
