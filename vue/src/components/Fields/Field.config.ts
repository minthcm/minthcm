const listFields = import.meta.glob('@/components/Fields/**/*.list.vue')
const editFields = import.meta.glob('@/components/Fields/**/*.edit.vue')
const detailFields = import.meta.glob('@/components/Fields/**/*.detail.vue')

export const fieldConfig = {
    allowedTypes: {
        list: Object.keys(listFields).map((path) => path.match(/Fields\/(\w*)/)?.[1]),
        edit: Object.keys(editFields).map((path) => path.match(/Fields\/(\w*)/)?.[1]),
        detail: Object.keys(detailFields).map((path) => path.match(/Fields\/(\w*)/)?.[1]),
    },
    defaultType: 'varchar',
    typeMap: {
        char: 'varchar',
        datetimecombo: 'datetime',
        ColoredActivityStatus: 'enum',
    } as { [key: string]: string },
}
