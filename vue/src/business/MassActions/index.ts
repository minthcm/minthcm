import { MassAction } from './MassAction'
import { registerActionModules } from '../registerActionModules'

const coreFiles = import.meta.glob('./Actions/*.ts', {
    eager: true,
})
const customFiles = import.meta.glob('@/custom/business/MassActions/Actions/*.ts', {
    eager: true,
})

interface Actions {
    [key: string]: new (module: string, ids: string[], filters: any) => MassAction
}

const actions: Actions = {}

// Custom is registered last on purpose -- it overrides the core action of the same name.
registerActionModules(actions, coreFiles)
registerActionModules(actions, customFiles)

export default actions
