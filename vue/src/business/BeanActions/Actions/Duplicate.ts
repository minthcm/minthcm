import { BeanAction } from '../BeanAction'
import router from '@/router'

export class Duplicate extends BeanAction {
    public static readonly TITLE = 'LBL_DUPLICATE_BUTTON'
    public static readonly ICON = 'mdi-content-duplicate'
    public static readonly ACL = ['edit']

    public async execute() {
        const query: { copy_id: string; excludedFields?: string } = {
            copy_id: this.bean.id,
        }
        
        // Serialize excludedFields as JSON string for query param
        if (this.options.skipFields && Array.isArray(this.options.skipFields)) {
            query.excludedFields = JSON.stringify(this.options.skipFields)
        }
        
        router.push({
            path: `/modules/${this.bean.module}/EditView`,
            query,
        })
        return true
    }
}
