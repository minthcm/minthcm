import { MassAction } from '../MassAction'
import router from '@/router'

export class Merge extends MassAction {
    public async execute() {
        router.push({
            name: 'merge-records',
            query: {
                return_action: 'index',
                uid: this.ids.join(','),
                action_module: this.module,
                action: 'index',
                return_module: this.module,
            },
        })
        return true
    }
}
