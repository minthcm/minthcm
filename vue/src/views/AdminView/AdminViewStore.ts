import { ref, reactive } from 'vue'
import { defineStore } from 'pinia'
import { useField, MintField } from '@/components/Fields/useField'
import { FieldVardef } from '@/store/modules'
import { getAdminConfig, saveAdminConfig } from '@/api/adminApi'
import { filesApi } from '@/api/files.api'

export const useAdminViewStore = defineStore('adminView', () => {
    const meta = ref<any>(null)
    const record = reactive<Record<string, any>>({})
    const fieldStates = reactive<Record<string, MintField<any>>>({})
    const loading = ref(false)
    const saving = ref(false)
    const error = ref<string | null>(null)

    function loadMeta(adminViews: Record<string, any>, viewName: string) {
        meta.value = adminViews?.[viewName] ?? null
    }

    function initFieldStates(fields: FieldVardef[]) {
        // Clear stale entries from any previous view so saveConfig() does not
        // send leftover fields to the backend when switching between admin views.
        Object.keys(fieldStates).forEach((key) => delete fieldStates[key])
        fields.forEach((def) => {
            const initialValue = def.static_value !== undefined
                ? def.static_value
                : record[def.name] ?? ''
            fieldStates[def.name] = useField(def, initialValue) as unknown as MintField<any>
        })
    }

    function updateField(name: string, value: any) {
        record[name] = value
        if (fieldStates[name]) {
            fieldStates[name].model = value
        }
        resetFieldsHiddenBy(name)
    }

    // When a field's visibility depends on `name` and it becomes hidden as a result of this
    // change, clear its value too — otherwise it keeps its old value in fieldStates/record even
    // though it disappeared from view, and reappears with that stale value if shown again.
    function resetFieldsHiddenBy(name: string) {
        getAllFieldDefs().forEach((def) => {
            if (def.name === name || !def.visible_when?.some((dep) => dep.field === name)) return
            const stillVisible = def.visible_when.every(
                (dep) => Number(fieldStates[dep.field]?.model) === Number(dep.value)
            )
            if (stillVisible) return
            const emptyValue = def.type === 'bool' ? '0' : ''
            record[def.name] = emptyValue
            if (fieldStates[def.name]) {
                fieldStates[def.name].model = emptyValue
            }
        })
    }

    async function loadConfig(viewName: string) {
        loading.value = true
        error.value = null
        try {
            const response = await getAdminConfig(viewName)
            Object.assign(record, response.data)
        } catch (e: any) {
            error.value = e?.message ?? 'Failed to load config'
        } finally {
            loading.value = false
        }
    }

    async function saveConfig(viewName: string) {
        saving.value = true
        error.value = null
        try {
            // fieldStates[name].model is the source of truth — field components
            // update it directly via v-model without emitting update:modelValue.
            // Skip fields with static_value — they are display-only, not stored in config.
            const allDefs = getAllFieldDefs()
            const staticValueNames = new Set(
                allDefs.filter((d) => d.static_value !== undefined).map((d) => d.name)
            )
            const payload: Record<string, any> = {}
            Object.entries(fieldStates).forEach(([name, field]) => {
                if (staticValueNames.has(name)) return
                if (field.model instanceof File) return   // uploaded separately below
                payload[name] = field.model
            })
            await saveAdminConfig(viewName, payload)

            // Upload image files for fields with upload_handler
            for (const def of allDefs) {
                const handler = def.upload_handler
                if (!handler) continue
                const val = fieldStates[def.name]?.model
                if (val instanceof File) {
                    // rawError: true — the shared response-error-handler resolves unhandled
                    // errors instead of rejecting them, which would otherwise let this loop
                    // (and saveConfig) complete as if the upload succeeded.
                    await filesApi.saveFile('', '', val, `admin/config/${viewName}/image/${def.name}`, { rawError: true })
                }
            }
        } catch (e: any) {
            error.value = e?.response?.data?.error ?? e?.response?.data?.message ?? e?.message ?? 'Failed to save config'
            throw e
        } finally {
            saving.value = false
        }
    }

    function getAllFieldDefs(): FieldVardef[] {
        if (!meta.value) return []
        const defs: FieldVardef[] = []
        Object.values(meta.value.panels ?? {}).forEach((panel: any) => {
            if (!panel.data?.sections) return
            Object.values(panel.data.sections).forEach((section: any) => {
                ;(section.fields ?? []).forEach((row: FieldVardef[]) => {
                    row.forEach((field) => {
                        if (field && field.name) defs.push(field)
                    })
                })
            })
        })
        return defs
    }

    return {
        meta,
        record,
        fieldStates,
        loading,
        saving,
        error,
        loadMeta,
        initFieldStates,
        updateField,
        loadConfig,
        saveConfig,
        getAllFieldDefs,
    }
})
