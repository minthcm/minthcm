import { Component } from 'vue'
import { FieldInterface } from './Field.model'

type ComponentModule = { default: Component }
type ComponentLoader = () => Promise<ComponentModule>
type FieldConfigModule = { default?: FieldInterface }

const CUSTOM_FIELDS_DIR = '/custom/components/Fields/'

// Folder names become object keys — these would reach Object.prototype instead of a plain entry.
const RESERVED_FIELD_NAMES = ['__proto__', 'constructor', 'prototype']

const listFields = import.meta.glob<ComponentModule>([
    '@/components/Fields/*/*.list.vue',
    '@/custom/components/Fields/*/*.list.vue',
])
const editFields = import.meta.glob<ComponentModule>([
    '@/components/Fields/*/*.edit.vue',
    '@/custom/components/Fields/*/*.edit.vue',
])
const detailFields = import.meta.glob<ComponentModule>([
    '@/components/Fields/*/*.detail.vue',
    '@/custom/components/Fields/*/*.detail.vue',
])
const fields = import.meta.glob<FieldConfigModule>(['@/components/Fields/*/*.ts', '@/custom/components/Fields/*/*.ts'], {
    eager: true,
})

const getFieldName = (path: string) => path.split('/').slice(-2, -1)[0]
const isCustomPath = (path: string) => path.includes(CUSTOM_FIELDS_DIR)

const isSafeFieldName = (name: string, path: string) => {
    if (RESERVED_FIELD_NAMES.includes(name)) {
        console.error(`Field.config: ignoring "${path}" — "${name}" is a reserved name and cannot be used as a field type`)
        return false
    }
    return true
}

// Core paths sort first, custom paths last, so when the same key is written twice the custom entry overwrites core.
const byCustomLast = (a: string, b: string) => Number(isCustomPath(a)) - Number(isCustomPath(b))

const sortedPaths = (globResult: Record<string, unknown>) => Object.keys(globResult).sort(byCustomLast)

const uniqueFieldNames = (globResult: Record<string, unknown>) =>
    [...new Set(Object.keys(globResult).map(getFieldName))].filter((name) => !RESERVED_FIELD_NAMES.includes(name))

const buildComponentLoaders = (globResult: Record<string, ComponentLoader>, view: string) => {
    const loaders: Record<string, ComponentLoader> = Object.create(null)
    for (const path of sortedPaths(globResult)) {
        const name = getFieldName(path)
        if (isSafeFieldName(name, path)) {
            loaders[`${name}/${view}`] = globResult[path]
        }
    }
    return loaders
}

export const componentLoaders: Record<string, ComponentLoader> = Object.assign(Object.create(null), {
    ...buildComponentLoaders(listFields, 'list'),
    ...buildComponentLoaders(editFields, 'edit'),
    ...buildComponentLoaders(detailFields, 'detail'),
})

const buildMergedFields = () => {
    const merged: Record<string, FieldInterface> = Object.create(null)
    for (const path of sortedPaths(fields)) {
        const name = getFieldName(path)
        if (!isSafeFieldName(name, path)) {
            continue
        }
        const fieldInterface = fields[path].default
        if (!fieldInterface) {
            console.error(`Field.config: ignoring "${path}" — a field config module must \`export default\` a FieldInterface`)
            continue
        }
        merged[name] = fieldInterface
    }
    return merged
}

const mergedFields = buildMergedFields()

const typeMap: Record<string, string> = Object.assign(Object.create(null), {
    char: 'varchar',
    datetimecombo: 'datetime',
    ColoredActivityStatus: 'enum',
    ColoredEnum: 'enum',
    image: 'file',
    html: 'richtext',
    name: 'varchar',
})

// Every field type name (component folder or {type}.ts folder) — an alias must not shadow one of them,
// otherwise Field.vue (component by type name) and useField (config via typeMap) would resolve different types.
const fieldTypeNames = new Set(
    [listFields, editFields, detailFields, fields].flatMap((globResult) => Object.keys(globResult).map(getFieldName)),
)

const isAvailableAlias = (alias: string, type: string) => {
    const source = `${type}.aliases`
    if (!isSafeFieldName(alias, source)) {
        return false
    }
    if (fieldTypeNames.has(alias)) {
        console.error(`Field.config: ignoring alias "${alias}" in "${source}" — it is already a field type name; to override that type use a custom/components/Fields/${alias}/ folder`)
        return false
    }
    if (alias in typeMap) {
        console.error(`Field.config: ignoring alias "${alias}" in "${source}" — it is already mapped to "${typeMap[alias]}"`)
        return false
    }
    return true
}

for (const [type, field] of Object.entries(mergedFields)) {
    for (const alias of field.aliases ?? []) {
        if (isAvailableAlias(alias, type)) {
            typeMap[alias] = type
        }
    }
}

export const fieldConfig = {
    allowedTypes: {
        list: uniqueFieldNames(listFields),
        edit: uniqueFieldNames(editFields),
        detail: uniqueFieldNames(detailFields),
    },

    fields: mergedFields,

    defaultType: 'varchar',

    typeMap,
}
