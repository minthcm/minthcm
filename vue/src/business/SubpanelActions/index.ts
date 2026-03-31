import { SubpanelAction } from './SubpanelAction'

const files = import.meta.glob('./Actions/*.ts', {
    eager: true,
})
const inlineFiles = import.meta.glob('./InlineActions/*.ts', { 
    eager: true 
})

interface Actions {
    [key: string]: new (bean: any) => SubpanelAction
}

const actions: Actions = {}

for (const path in files) {
    const file = files[path] as Actions
    const name = path.match(/\.\/Actions\/(.*)\.ts$/)?.[1]
    if (name && file) {
        actions[name] = file.default ?? file[name]
    }
}

for (const path in inlineFiles) {
    const file = inlineFiles[path] as Actions
    const name = path.match(/\.\/InlineActions\/(.*)\.ts$/)?.[1]
    if (name && file) {
        actions[name] = file.default ?? file[name]
    }
}

export default actions
