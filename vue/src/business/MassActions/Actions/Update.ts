import { MassAction } from '../MassAction'
import { useListViewStore } from '@/views/ListView/ListViewStore'
import { storeToRefs } from 'pinia'


export class Update extends MassAction {
    public async execute() {
        useListViewStore().setMassUpdate(true)
        return false
    }

    public async executeMassUpdate() {
        const { massUpdateRows } = storeToRefs(useListViewStore())
        const data = { update_fields: {} as any }
        let result = {
            data: {
                success: false
            }
        }
        massUpdateRows.value.forEach((row) => {
            if (!this.inputsAreCorrect(row)) {
                return
            }

            if (row.field == 'parent_name' && row.inputs.length == 2) {
                data.update_fields['parent_type'] = row.inputs[0].value
                data.update_fields['parent_id'] = row.inputs[1].value
                return
            }

            data.update_fields[row.field] = row.inputs[0].value
        })

        if (Object.values(data.update_fields).length <= 0) {
            return result
        }

        result = await this.sendRequest(data)
        return result
    }

    protected inputsAreCorrect(row): boolean {
        for (let i = 0; i < row.inputs.length; i++) {
            if (!row.inputs[i] || row.inputs[i].value == undefined || Number.isNaN(row.inputs[i].value)) {
                return false
            }
        }
        return true
    }
}
