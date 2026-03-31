import { BeanAction } from '../BeanAction'
import router from '@/router'

export class RejectAndMoveToAnotherRecruitment extends BeanAction {
    public static readonly TITLE = 'LBL_REJECT_AND_MOVE_TO_ANOTHER_RECRUITMENT'
    public static readonly ICON = 'mdi-account-reactivate'
    public static readonly ACL = ['edit']

    public isAvailable(): boolean {
        return super.isAvailable() && this.bean.module === 'Candidatures'
    }

    public async execute() {
        router.push({
            path: `/modules/${this.bean.module}/EditView`,
            query: {
                route_of_acquisition: 'created_from_other_candidature',
                original_candidature_id: this.bean.id,
                original_candidature_name: this.bean.attributes.name,
                entry_interview: this.bean.attributes.entry_interview,
                start_date: this.bean.attributes.start_date,
                source: this.bean.attributes.source,
                parent_type: this.bean.attributes.parent_type,
                parent_id: this.bean.attributes.parent_id,
                parent_name: this.bean.attributes.parent_name,
            },
        })
        return true
    }
}
