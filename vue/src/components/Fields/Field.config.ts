const listFields = import.meta.glob('@/components/Fields/*/*.list.vue', { eager: true })
const editFields = import.meta.glob('@/components/Fields/*/*.edit.vue', { eager: true })
const detailFields = import.meta.glob('@/components/Fields/*/*.detail.vue', { eager: true })
const fields = import.meta.glob('@/components/Fields/*/*.ts', { eager: true })

const getFieldName = (path: string) => path.split('/').slice(-2, -1)[0]

export const fieldConfig = {
    allowedTypes: {
        list: Object.keys(listFields).map(getFieldName),
        edit: Object.keys(editFields).map(getFieldName),
        detail: Object.keys(detailFields).map(getFieldName),
    },

    fields: Object.fromEntries(
        Object.entries(fields).map(([path, module]) => {
            const name = getFieldName(path)
            const m = module as { default: any }
            return [name, m.default ?? m]
        }),
    ),

    defaultType: 'varchar',

    typeMap: {
        char: 'varchar',
        datetimecombo: 'datetime',
        ColoredActivityStatus: 'enum',
        ColoredEnum: 'enum',
        image: 'file',
        name: 'varchar',
    } as { [key: string]: string },
}
