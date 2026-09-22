import { BeanAction } from '../BeanAction'
import { mintApi } from '@/api/api'

export class PublishNews extends BeanAction {
    public static readonly TITLE = 'LBL_PUBLISH_BTN'
    public static readonly ICON = 'mdi-publish'
    public static readonly ACL = ['edit']

    public isAvailable(): boolean {
        return (
            super.isAvailable() &&
            this.bean.module === 'News' &&
            this.bean.attributes?.news_status === 'draft'
        )
    }

    public async execute() {
        await mintApi.patch(`News/update/status`, { news_id: this.bean.id, status: 'published' })
        await this.bean.retrieve()
        return true
    }
}
