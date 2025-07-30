import { MassAction } from '../MassAction'
import axios from 'axios'
export class MassConfirmation extends MassAction {
    public async execute() {
        const result = await axios.post(`legacy/index.php?&module=${this.module}&action=WSMassConfirmation&sugar_body_only=1&ids=${this.ids.join(',')}`)
        return true
    }
}