import { SubpanelAction } from './SubpanelAction'
import { registerActionModules } from '../registerActionModules'

const coreFiles = import.meta.glob('./Actions/*.ts', {
    eager: true,
})
const customFiles = import.meta.glob('@/custom/business/SubpanelActions/Actions/*.ts', {
    eager: true,
})
const coreInlineFiles = import.meta.glob('./InlineActions/*.ts', {
    eager: true,
})
const customInlineFiles = import.meta.glob('@/custom/business/SubpanelActions/InlineActions/*.ts', {
    eager: true,
})

interface Actions {
    [key: string]: new (bean: any) => SubpanelAction
}

const actions: Actions = {}

// Both registries share one map, so every core set has to be registered before any custom one --
// otherwise a core inline action would overwrite a custom action of the same name.
registerActionModules(actions, coreFiles)
registerActionModules(actions, coreInlineFiles)
registerActionModules(actions, customFiles)
registerActionModules(actions, customInlineFiles)

export default actions
