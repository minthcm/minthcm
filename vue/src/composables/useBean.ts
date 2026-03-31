import { computed, ref, watch } from 'vue'
import { useLogic } from './useLogic'
import { useDebounceFn, useThrottleFn } from '@vueuse/core'
import { useRouter } from 'vue-router'
import { FieldVardef, useModulesStore } from '@/store/modules'
import { usePreferencesStore } from '@/store/preferences'
import { MintField, useField } from '@/components/Fields/useField'
import { useLink } from './useLink'
import { mintApi } from '@/api/api'
import { DateTime } from 'luxon'
import { MintDate, useMintDate } from '@/composables/useMintDate'

export type MintBean = ReturnType<typeof useBean>
interface MintBeanAttributes {
    [fieldName: string]: string | number | boolean | null | string[]
}

export const useBean = (module: string, id: string, fetch_links: Array<string> = []) => {
    const retrieveTimeoutTimeMs = 30000
    const router = useRouter()
    const modulesStore = useModulesStore()

    const fields = ref<{ [fieldName: string]: MintField<unknown> }>({})
    const attributes = ref<{ [key: string]: any }>({})
    const syncAttributes = ref<{ [key: string]: any }>({})
    const aclAccess = ref<{ [key: string]: boolean }>({})
    const dirtyFields = computed(() => {
        return Object.keys(fields.value).filter((fieldName) => fields.value[fieldName].isDirty)
    })
    const links = ref<Map<string, ReturnType<typeof useLink>>>(new Map())

    const logic = useLogic(module)

    const duplicateSkipFields = [
        'id',
        'date_entered',
        'date_modified',
        'modified_user_id',
        'modified_by_name',
        'created_by',
        'created_by_name',
        'date_indexed',
        'repeat',
        'repeat_type',
        'repeat_interval',
        'repeat_dow',
        'repeat_until',
        'repeat_count',
        'repeat_parent_id',
    ]

    const filesToSave = ref<{ [key: string]: File }>({})

    function getFieldDefs(fieldName: string): FieldVardef {
        if (fieldDefs.value[fieldName]) {
            return fieldDefs.value[fieldName]
        }
        // default field defs
        return {
            name: fieldName,
            type: 'varchar',
            label: 'LBL_' + fieldName.toUpperCase(),
        }
    }
    function setFields(attrs) {
        if (!attrs || typeof attrs !== 'object') {
            return
        }
        Object.keys(attrs).forEach((fieldName) => {
            const fieldDefs = getFieldDefs(fieldName)
            let value = attrs[fieldName]
            if (isNew.value && !value && Object.hasOwn(fieldDefs, 'default')) {
                value = fieldDefs.default
            }
            fields.value[fieldName] = useField(fieldDefs, value)
        })
    }
    function getAttributesToSave() {
        const attributesToSave: MintBeanAttributes = {}
        Object.keys(fields.value).forEach((fieldName) => {
            if (logic.hiddenFields.value.includes(fieldName)) {
                attributesToSave[fieldName] = null
                return
            }
            attributesToSave[fieldName] = fields.value[fieldName].formatted.server
        })
        return attributesToSave
    }

    const isRetrieving = ref(false)
    const isSaving = ref(false)
    const isDirty = ref(false)
    const loadingError = ref(false)

    const validationError = ref('')
    const isValid = computed(() => {
        if (logic.requiredFields.value) {
            for (const fieldName of logic.requiredFields.value) {
                if ((isDirty.value || fields.value[fieldName]?.isDirty) && !fields.value[fieldName].model) {
                    return false
                }
            }
        }
        if (Object.keys(errorMessages.value).length > 0) {
            return false
        }
        return true
    })

    const errorMessages = computed(() => {
        const formPanel = Object.values(modulesStore.modules[module]?.metadata.RecordView?.panels ?? {}).find( // FIXME: refactor - podobny kod w useLogic
            (panel) => panel.component === 'MintPanelRecordDetails',
        )
        
        const errors: { [key: string]: string } = {}
        if (!formPanel) {
            return errors
        }

        Object.values(formPanel?.data?.sections).forEach((section) => {
            const formFields = section?.fields?.flat() ?? []
            formFields.forEach((field) => {
                if (logic.hiddenFields.value.includes(field.name) || logic.readonlyFields.value.includes(field.name)) {
                    return
                }
                const fieldValidationResult = fields.value[field.name]?.validate()
                if (typeof fieldValidationResult === 'string') {
                    errors[field.name] = fieldValidationResult
                } else if (logic.errorMessages.value[field.name]) {
                    errors[field.name] = logic.errorMessages.value[field.name]
                }
            })
        })
        return errors
    })

    const fieldDefs = computed<{ [key: string]: any }>(() => {
        return modulesStore.modules[module]?.vardefs ?? {}
    })

    const name = computed(() => {
        if (syncAttributes.value.name) {
            return syncAttributes.value.name
        }
        if (syncAttributes.value.first_name || syncAttributes.value.last_name) {
            return `${syncAttributes.value.first_name} ${syncAttributes.value.last_name}`
        }
        return id
    })

    const isNew = computed(() => {
        return !id || attributes.value.new_with_id
    })

    const isChanged = computed(() => {
        return dirtyFields.value.some((f) => attributes.value[f] !== syncAttributes.value[f])
    })

    function restore() {
        attributes.value = { ...syncAttributes.value }
        filesToSave.value = {}
        setFields(attributes.value)
        isDirty.value = false
        validationError.value = ''
    }

    async function init() {
        return retrieve()
    }

    function updateFields(updatedFields: { [fieldName: string]: any }) {
        Object.entries(updatedFields || {}).forEach(([key, value]) => {
            if (value instanceof File) {
                filesToSave.value[key] = value
                value = value?.name ?? ''
            }
            if (fields.value[key]) {
                fields.value[key].model = value
            }
        })
    }

    function setAttributesFromQuery(query: { [key: string]: string | (string | null)[] | null | undefined }) {
        const fieldsToUpdate: { [fieldName: string]: any } = {}
        // const preferences = usePreferencesStore()
        Object.entries(query)
            .filter(([key]) => fieldDefs.value[key])
            .map(([key, value]) => {
                fieldsToUpdate[key] = parseRawFieldValueToField(key, value)
            })
        if (query?.return_relationship && query?.return_id) {
            const link = loadRelationship(query.return_relationship as string)
            if (link && !link.relateFieldName) link.add(query.return_id as string)
        }
        setFields(fieldsToUpdate)
        const triggerFields = logic.triggerFields.value.filter((f) => Object.hasOwn(fieldsToUpdate, f))
        if (triggerFields.length > 0) {
            fetchLogic(triggerFields)
        }
    }

    function parseRawFieldValueToField(fieldName: string, value: any) {
        const preferences = usePreferencesStore()
        let parsedValue = value;
        
        if(['date', 'datetime', 'datetimecombo'].includes(fieldDefs.value[fieldName].type)){
            // Try parsing as ISO format first
            const isoDate = DateTime.fromISO(value as string).setZone('UTC');
            if (isoDate.isValid) {
                const dateFormatted = isoDate.toFormat('yyyy-MM-dd');
                const timeFormatted = isoDate.toFormat('HH:mm:ss');
                parsedValue = `${dateFormatted} ${timeFormatted}`;
            } else {
                // Fallback to user format parsing
                const dateValueParts = (value as string).split(' ');
                const dateUserFormat = DateTime.fromFormat(dateValueParts[0], preferences.user?.date_format || 'yyyy-MM-dd');
                let timeUserFormat = '00:00';
                if(dateValueParts[1]){
                    timeUserFormat = DateTime.fromFormat(dateValueParts[1], preferences.user?.time_format).setZone('UTC').toFormat('HH:mm');
                }
                parsedValue = `${dateUserFormat.toFormat('yyyy-MM-dd')} ${timeUserFormat}:00`;
            }
            parsedValue = useMintDate(parsedValue)
        }
        
        return parsedValue;
    }

    const originalId = ref('')
    async function setAttributesFromBeanId(copy_id: string, excludedFields: string[] = []) {
        originalId.value = copy_id
        const fieldsToUpdate: { [fieldName: string]: any } = {}
        const copyBean = await useBean(module, copy_id).init()
        Object.entries(copyBean.data.attributes || {}).forEach(([fieldName, fieldDef]) => {
            if (
                duplicateSkipFields.includes(fieldName) 
                || (fieldDefs.value[fieldName] && ['file', 'image'].includes(fieldDefs.value[fieldName].type))
                || excludedFields.includes(fieldName)
            ) return
            if (copyBean.data.attributes[fieldName] !== undefined 
                && copyBean.data.attributes[fieldName] !== null
                && copyBean.data.attributes[fieldName] !== ''
            ) {

                fieldsToUpdate[fieldName] = parseRawFieldValueToField(fieldName, copyBean.data.attributes[fieldName])
            }
        })
        updateFields(fieldsToUpdate)
    }

    async function retrieve() {
        isRetrieving.value = true
        loadingError.value = false
        const timeout = new Promise((_, reject) =>
            setTimeout(() => reject(new Error('timeout')), retrieveTimeoutTimeMs),
        )

        const apiCall = mintApi
            .post(`${module}/Get${id ? `/${id}` : ''}`, {
                links: fetch_links
            }, { rawError: true })
            .then((response) => {
                if (response.status === 200 && response.data) {
                    setData(response.data)
                }
                return response
            })
            .finally(() => {
                isRetrieving.value = false
            })

        try {
            return await Promise.race([apiCall, timeout])
        } catch (error) {
            if (error?.message === 'timeout') {
                loadingError.value = true
                throw { response: { status: 408, data: { error: 'Request timed out' } } }
            }
            throw error
        } finally {
            isRetrieving.value = false
        }
    }

    function setData(data: { [key: string]: any }) {
        aclAccess.value = data.acl_access
        attributes.value = data.attributes
        syncAttributes.value = structuredClone(data.attributes)
        setFields(data.attributes)
        logic.rules.value = data.logic?.rules ?? []
        setFields(logic.getUpdatedFields())
        setLinks(data.related_records)
    }

    function setLinks(related_records: Record<string, any>) {
        Object.entries(related_records || {}).forEach(([linkName, records]) => {
            const link = useLink(
                linkName,
                fieldDefs.value[linkName]?.relationship,
                { module, id, fieldDefs }
            )
            link.hydrateFromBackend(records)
            links.value.set(linkName, link)
        })
    }

    async function fetchLogic(triggerFields: string[] = []) {
        const response = await mintApi.post(`${module}/Logic${id ? `/${id}` : ''}`, {
            attributes: fieldsValues.value,
            triggerFields
        })
        if (response.data.rules?.length) {
            response.data.rules.forEach((r: any) => {
                const rule = logic.rules.value.find((rule: any) => rule.key === r.key)
                if (rule) {
                    rule.trigger = r.trigger
                    rule.logic = r.logic
                }
            })

            setFields(logic.getUpdatedFields(response.data.rules))
        }
    }

    function createFakeLink(name: string): ReturnType<typeof useLink> {
        if (!links.value.has(name)) {
            links.value.set(name, useLink(name, name, { module, id, fieldDefs }, true))
        }
        return links.value.get(name)!
    }

    function loadRelationship(name: string): ReturnType<typeof useLink> | null {
        if (!name) return null
        if (!Object.keys(fieldDefs.value?.[name] || {}).length) {
            const relatedLinkField = Object.keys(fieldDefs.value)
                .find(fieldName => fieldDefs.value?.[fieldName]?.type === 'link'
                    && fieldDefs.value?.[fieldName]?.relationship === name)
            if (!relatedLinkField) return null
            name = relatedLinkField
        } else if (fieldDefs.value[name]?.type !== 'link' || !fieldDefs.value[name]?.relationship) return null
        if (!links.value.has(name)) links.value.set(name, useLink(name, fieldDefs.value[name].relationship, { module, id, fieldDefs }))
        return links.value.get(name)
    }

    async function save() {
        isDirty.value = true
        if (!isValid.value) {
            return {
                status: false,
                error: 'validation_failed',
                errorMessages: errorMessages.value,
                validationError: validationError.value,
            }
        }
        isSaving.value = true
        try {
            const files = {}
            for (const fileField in filesToSave.value) {
                files[fileField] = await new Promise((resolve, reject) => {
                    if (filesToSave.value[fileField]?.size === 0) {
                        resolve(null)
                        return
                    }
                    const reader = new FileReader()
                    reader.onload = () => resolve(reader.result)
                    reader.onerror = reject
                    reader.readAsDataURL(filesToSave.value[fileField])
                })
            }
            const response = await mintApi.patch(`${module}/Update${id ? `/${id}` : ''}`, {
                record_data: getAttributesToSave(),
                files,
                links: Object.fromEntries([...links.value].filter(([, link]) => !link.isFake.value).map(([name, link]) => [name, link.getChanges()]))
            })
            if (!id && response.data.id) {
                router.push({
                    name: 'record',
                    params: {
                        module,
                        id: response.data.id,
                    },
                })
            } else if ([200, 201].includes(response.status)) {
                await retrieve()
            }
            return response
        } catch (error) {
            if (error?.response?.data?.isValid === false && error.response.data.error) {
                validationError.value = error.response.data.error
            }
            return {
                status: false,
                error: 'save_request_failed',
                errorMessages: errorMessages.value,
                validationError: validationError.value,
            }
        } finally {
            isSaving.value = false
        }
    }

    async function markDeleted() {
        return await mintApi.delete(`${module}/${id}`)
    }

    const fieldsValues = computed(() => {
        const values: { [key: string]: any } = {}
        Object.keys(fields.value).forEach((fieldName) => {
            values[fieldName] = fields.value[fieldName].formatted.server
        })
        return values
    })
    const prevFieldsValues = ref<{ [key: string]: any }>({})

    const compareChangesThrottled = useThrottleFn(
        () => {
            const newFieldsValues = fieldsValues.value
            const updatedFields = {} as { [key: string]: any }

            Object.entries(prevFieldsValues.value).forEach(([key, value]) => {
                if (value !== newFieldsValues[key]) {
                    updatedFields[key] = value
                }
            })
            const triggerFields = logic.triggerFields.value.filter((f) => Object.hasOwn(updatedFields, f))
            if (triggerFields.length > 0) {
                fetchLogic(triggerFields)
            }
            prevFieldsValues.value = { ...newFieldsValues }
        },
        1000,
        true,
    )
    const compareChangesDebounced = useDebounceFn(
        () => {
            compareChangesThrottled()
        },
        200,
        { maxWait: 2000 },
    )

    watch(
        fields,
        () => {
            compareChangesDebounced()
        },
        { deep: true },
    )

    return {
        id,
        module,
        name,
        isNew,
        attributes,
        syncAttributes,
        fields,
        aclAccess,
        dirtyFields,
        logic,
        isDirty,
        validationError,
        isValid,
        isRetrieving,
        isSaving,
        isChanged,
        errorMessages,
        init,
        updateFields,
        restore,
        retrieve,
        setData,
        save,
        markDeleted,
        fieldDefs,
        setAttributesFromQuery,
        loadRelationship,
        createFakeLink,
        setAttributesFromBeanId,
        originalId,
    }
}
