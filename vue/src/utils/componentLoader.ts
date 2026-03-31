import { defineAsyncComponent } from 'vue'

export default class ComponentLoader {
    private static customComponents = import.meta.glob('@/custom/components/**/*.vue')
    private static appComponents = import.meta.glob('@/components/**/*.vue')
    private static viewComponents = import.meta.glob('@/views/**/*.vue')

    public static async loadComponent(componentPath: string) {
        const normalized = componentPath.replace(/^\/|\.vue$/g, '')

        const allModules = {
        ...this.customComponents,
        ...this.appComponents,
        ...this.viewComponents,
        }

        for (const [path, loader] of Object.entries(allModules)) {
            if (path.endsWith(`${normalized}.vue`)) {
                return defineAsyncComponent(loader as any)
            }
        }

        console.error(`Component not found: ${componentPath}`)
        return null
    }
}