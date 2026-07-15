import { BeanAction } from '../BeanAction'
import router from '@/router'
import { DateTime } from 'luxon'

export class DuplicateMeetings extends BeanAction {
    public static readonly TITLE = 'LBL_DUPLICATE_BUTTON'
    public static readonly ICON = 'mdi-content-duplicate'
    public static readonly ACL = ['edit']

    private parseDateAttr(value: string): DateTime {
        const iso = DateTime.fromISO(value, { zone: 'UTC' })
        if (iso.isValid) return iso
        return DateTime.fromSQL(value, { zone: 'UTC' })
    }

    public async execute() {
        const skipFields: string[] = Array.isArray(this.options.skipFields) ? this.options.skipFields : []
        const query: Record<string, string> = {
            copy_id: this.bean.id,
        }

        const origStart = this.parseDateAttr(this.bean.attributes.date_start)
        const origEnd = this.parseDateAttr(this.bean.attributes.date_end)

        if (origStart.isValid && origEnd.isValid) {
            const duration = origEnd.diff(origStart)
            const newStart = DateTime.now().setZone('UTC').set({
                hour: origStart.hour,
                minute: origStart.minute,
                second: 0,
                millisecond: 0,
            })
            const newEnd = newStart.plus(duration)

            query.date_start = newStart.toISO() ?? ''
            query.date_end = newEnd.toISO() ?? ''
            query.excludedFields = JSON.stringify([...skipFields, 'date_start', 'date_end'])
        } else {
            if (skipFields.length > 0) {
                query.excludedFields = JSON.stringify(skipFields)
            }
        }

        router.push({
            path: `/modules/${this.bean.module}/EditView`,
            query,
        })
        return true
    }
}
