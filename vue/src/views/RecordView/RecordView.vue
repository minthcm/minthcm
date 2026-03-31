<template>
    <div class="record-view" :class="{'record-view-railed': $vuetify.display.mdAndDown}">
        <div class="record-panels">
            <MintPanel v-for="panel in store.panels" :key="panel.key" :component="panel.component" :data="panel.data" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, watch } from 'vue'
import MintPanel from '@/components/MintPanel/MintPanel.vue'
import { useRecordViewStore } from './RecordViewStore'
import { useLanguagesStore } from '@/store/languages'
import { useBackendStore } from '@/store/backend'
import { useRoute } from 'vue-router'
import { useStatusBoxesStore } from '@/store/statusBoxes'
import { useRouter } from 'vue-router'
import { useACL, ACLView } from '@/composables/useACL'

const store = useRecordViewStore()
const languages = useLanguagesStore()
const backend = useBackendStore()
const route = useRoute()
const router = useRouter()

store.resetBean()

onMounted(async () => {
    await store.bean.init().catch(recordAccessError)

    const pathSegments = route.path.split('/')
    if (pathSegments.includes('EditView')) {
        store.view = 'edit'
    }

    if (store.bean.isNew) {
        if (Object.keys(route.query).length) {
            store.bean.setAttributesFromQuery(route.query)
        }
        
        if (Object.keys(route.query).includes('copy_id')) {
            // Parse excludedFields from JSON string in query param
            let excludedFields: string[] = []
            if (route.query.excludedFields && typeof route.query.excludedFields === 'string') {
                try {
                    excludedFields = JSON.parse(route.query.excludedFields)
                    if (!Array.isArray(excludedFields)) {
                        excludedFields = []
                    }
                } catch (e) {
                    console.warn('Failed to parse excludedFields from query', e)
                }
            }
            await store.bean.setAttributesFromBeanId(route.query.copy_id as string, excludedFields)
        }
    }
})

function recordAccessError(error: any): Promise<any> {
    if (error.response && [403, 404].includes(error.response.status)) {
        useStatusBoxesStore().showStatus('record_access_error', {
            type: 'error',
            message: languages.label('ERROR_NO_RECORD'),
            autoClose: true,
        })
        redirect('list')
    }

    if (error.response && 408 == error.response.status) {
        useStatusBoxesStore().showStatus('access_timed_out', {
            type: 'error',
            message: languages.label('LBL_DETAIL_VIEW_LOADING_TIMEOUT'),
            autoClose: true,
        })
    }
    if (error.response && 500 == error.response.status) {
        useStatusBoxesStore().showStatus('access_timed_out', {
            type: 'error',
            message: error.response.statusText + ': ' + error.response.data.message,
            autoClose: false,
        })
    }
    return Promise.reject(error)
}

function redirect(where: ACLView) {
    useACL().hasAccess(store.bean.module, where, true, true)
        ? router.push({ name: where, params: { module: store.bean.module } })
        : router.push({ name: 'dashboard' })
}

watch(
    () => store.bean.syncAttributes,
    (newVal) => {
        if (!newVal.name || !newVal.module_name) return
        document.title = `${newVal.name} | ${languages.label('LBL_MODULE_NAME', newVal.module_name)} | ${
            backend.initData?.systemName
        }`
    },
)
</script>

<style scoped lang="scss">
.record-view {
    padding: 32px;

    &.record-view-railed {
        padding: 8px;
    }
}

.record-panels {
    display: flex;
    flex-direction: column;
    gap: 32px;
}
</style>
