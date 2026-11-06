import { BeanAction } from './BeanAction'
import { registerActionModules } from '../registerActionModules'

const coreFiles = import.meta.glob('./Actions/*.ts', {
    eager: true,
})
const customFiles = import.meta.glob('@/custom/business/BeanActions/Actions/*.ts', {
    eager: true,
})

interface Actions {
    [key: string]: new (bean: any) => BeanAction
}

const actions: Actions = {}

// Custom is registered last on purpose -- it overrides the core action of the same name.
registerActionModules(actions, coreFiles)
registerActionModules(actions, customFiles)

export default actions
