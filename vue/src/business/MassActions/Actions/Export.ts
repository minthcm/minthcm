import { MassAction } from '../MassAction'

export class Export extends MassAction {
    public async execute() {
        await this.sendRequest()
        location.href = `legacy/index.php?entryPoint=export&module=${this.module}`
        return false
    }
}
