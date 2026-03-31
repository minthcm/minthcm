import { useBackendStore } from '@/store/backend'
import { usePreferencesStore } from '@/store/preferences'

export default class DateUtils {
    public static getDateTimeFormat() {
        const backend = useBackendStore()
        const preferences = usePreferencesStore()
        const dateFormat = preferences.userDateFormat || backend.initData.global.date_format || 'Y-m-d'
        const timeFormat = preferences.userTimeFormat || backend.initData.global.time_format || 'HH:mm'
        return { date: dateFormat, time: timeFormat }
    }

    public static getTimeFormatGeneralized() {
        const dateTimeFormat = this.getDateTimeFormat()
        switch (dateTimeFormat.time) {
            case 'H:i':
            case 'H.i':
            case 'HH:mm':
            case 'HH.mm':
                return '24hr'
            default:
                return 'ampm'
        }
    }
}
