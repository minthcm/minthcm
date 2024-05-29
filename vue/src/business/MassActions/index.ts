import { MassAction } from './MassAction'

const files = import.meta.glob('./Actions/*.ts', {
    eager: true,
})

interface Actions {
    [key: string]: new (module: string, ids: string[]) => MassAction
}

const actions: Actions = {}

for (const path in files) {
    const file = files[path] as Actions
    const name = path.match(/\.\/Actions\/(.*)\.ts$/)?.[1]
    if (name && file) {
        actions[name] = file.default ?? file[name]
    }
}

export default actions
