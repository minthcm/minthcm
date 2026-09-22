import { BeanAction } from '../BeanAction'
import { mintApi } from '@/api/api'

export class ArchiveNews extends BeanAction {
    public static readonly TITLE = 'LBL_ARCHIVE_BTN'
    public static readonly ICON = 'mdi-archive'
    public static readonly ACL = ['edit']

    public isAvailable(): boolean {
        return (
            super.isAvailable() &&
            this.bean.module === 'News' &&
            this.bean.attributes?.news_status === 'published'
        )
    }

    public async execute() {
        await mintApi.patch(`News/update/status`, { news_id: this.bean.id, status: 'archived' })
        await this.bean.retrieve()
        return true
    }
}
