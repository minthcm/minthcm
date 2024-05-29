import { MassAction } from '../MassAction'

export class Export extends MassAction {
    public async execute() {
        location.href = `legacy/index.php?entryPoint=export&module=${this.module}&uid=${this.ids.join(',')}`
        return false
    }
}
