import { BeanAction } from './BeanAction'

const files = import.meta.glob('./Actions/*.ts', {
    eager: true,
})

interface Actions {
    [key: string]: new (bean: any) => BeanAction
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
