import { useAuthStore } from '@/store/auth'
import { useBackendStore } from '@/store/backend'

export default class DateUtils {
    public static getDateTimeFormat() {
        const auth = useAuthStore()
        const backend = useBackendStore()
        const dateFormat = auth.user.preferences.date_time_preferences.date || backend.initData.global.date_format || 'Y-m-d'
        const timeFormat = auth.user.preferences.date_time_preferences.time || backend.initData.global.time_format || 'H:i'
        return { date: dateFormat, time: timeFormat }
    }

    public static getTimeFormatGeneralized() {
        const dateTimeFormat = this.getDateTimeFormat()
        switch (dateTimeFormat.time) {
            case 'H:i':
            case 'H.i':
                return '24hr'
            default:
                return 'ampm'
        }
    }
}
