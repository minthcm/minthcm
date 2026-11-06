type ActionModule = Record<string, unknown>

/**
 * Registers modules returned by an eager `import.meta.glob` under the name of the file defining them.
 *
 * The name is taken from the file's basename, so it works for every key shape the glob returns --
 * a relative one (`./Actions/Audit.ts`) as well as an aliased one
 * (`/src/custom/business/BeanActions/Actions/Audit.ts`).
 *
 * Call it for the core modules first and the custom ones second: registering custom last is what
 * makes a custom action deterministically override the core action of the same name.
 */
export function registerActionModules<T>(registry: Record<string, T>, files: Record<string, unknown>): void {
    for (const path in files) {
        const name = path.split('/').pop()?.replace(/\.ts$/, '')
        const module = files[path] as ActionModule | undefined
        if (!name || !module) {
            continue
        }

        const action = (module.default ?? module[name]) as T | undefined
        if (!action) {
            continue
        }

        registry[name] = action
    }
}
