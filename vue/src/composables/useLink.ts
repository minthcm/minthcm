import { computed, ComputedRef, ref } from 'vue'
import { mintApi } from '@/api/api'
import { subpanelsApi } from '@/api/subpanels.api'
import { useBean } from './useBean'

interface RelationshipRecord {
    id: string
    additionalValues?: { [key: string]: string }
}

interface BeanData {
    id: string
    module: string
    fieldDefs: ComputedRef
}

export const useLink = (link: string, relationshipName: string, beanData: BeanData, fake: boolean = false) => {
    const beans = ref<Map<string, ReturnType<typeof useBean>>>(new Map())
    const beansToAdd = ref<Map<string, RelationshipRecord>>(new Map())
    const beansToRemove = ref<Set<string>>(new Set())
    const total = ref(0)
    const currentPage = ref(0)
    const isFake = ref(fake)

    const relateFieldName = computed<string | null>(() => {
        const relateField = Object.keys(beanData.fieldDefs.value).find(
            (fieldName) =>
                beanData.fieldDefs.value?.[fieldName]?.type === 'relate' &&
                (beanData.fieldDefs.value?.[fieldName]?.link === link ||
                    beanData.fieldDefs.value?.[fieldName]?.relationship === relationshipName),
        )
        return relateField || null
    })

    const idFieldName = computed<string | null>(() => {
        const idField = Object.keys(beanData.fieldDefs.value).find(
            (fieldName) =>
                beanData.fieldDefs.value?.[fieldName]?.type === 'id' &&
                (beanData.fieldDefs.value?.[fieldName]?.link === relationshipName ||
                    beanData.fieldDefs.value?.[fieldName]?.relationship === relationshipName),
        )
        return idField || null
    })

    const beansArray = computed(() => {
        return Array.from(beans.value.values())
    })

    function add(id: string, additionalValues?: { [key: string]: string }) {
        if (isFake.value || !id || beansToAdd.value.has(id)) return
        beansToAdd.value.set(id, { id, additionalValues })
        beansToRemove.value.delete(id)
    }

    function remove(id: string) {
        if (isFake.value || !id || beansToRemove.value.has(id)) return
        beansToRemove.value.add(id)
        beansToAdd.value.delete(id)
    }

    async function unlink(parentBean: any, link_name: string) {
        if (isFake.value) return
        await mintApi.post(`/${parentBean.module}/Unlink/${parentBean.id}`, {
                ids: [...beansToRemove.value],
                link_name: link_name,
        })
    }

    async function fetchRelatedRecords(subpanelKey: string, paginateBy: number = -1, page: number = 0, sortBy: string = '', sortOrder: string = '') {
        const result = await subpanelsApi.fetchSubpanelsData(
            beanData.module,
            subpanelKey,
            beanData.id,
            paginateBy,
            page,
            sortBy,
            sortOrder
        )
        if (!result.data) {
            return
        }
        const map = new Map<string, ReturnType<typeof useBean>>()
        Object.entries(result.data).forEach(([key, record]: any) => {
            if (key === 'total') { 
                total.value = record
                return 
            }
            if (key === 'page') { 
                currentPage.value = record
                return 
            }
            const bean = useBean(record.module ?? beanData.module, key)
            bean.setData(record)
            map.set(key, bean)
        })
        beans.value = new Map(map)
    }

    function hydrateFromBackend(payload: Record<string, any>) {
        const map = new Map<string, ReturnType<typeof useBean>>()
        Object.entries(payload).forEach(([key, record]: any) => {
            const bean = useBean(record.module ?? beanData.module, key)
            bean.setData(record)
            map.set(key, bean)
        })
        beans.value = new Map(map)
    }

    function getChanges() {
        return {
            beansToAdd: Object.fromEntries(beansToAdd.value),
            beansToRemove: Array.from(beansToRemove.value),
        }
    }

    return {
        link,
        relationshipName,
        beans,
        relateFieldName,
        idFieldName,
        isFake,
        add,
        remove,
        fetchRelatedRecords,
        getChanges,
        unlink,
        total,
        currentPage,
        beansArray,
        hydrateFromBackend
    }
}
