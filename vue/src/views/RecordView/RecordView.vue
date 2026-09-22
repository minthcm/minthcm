<template>
    <div class="record-view" :class="{'record-view-railed': mdAndDown}">
        <div v-if="store.hasSidepanel && smAndDown" class="record-view-mobile-switch">
            <button
                class="record-view-mobile-switch-btn"
                :class="{ 'record-view-mobile-switch-btn--active': mobileView === 'record' }"
                @click="mobileView = 'record'"
            >
                <v-icon icon="mdi-file-document-outline" size="16" />
                {{ languages.label('LBL_MINT_RECORDVIEW_MOBILE_SWITCH_RECORD') }}
            </button>
            <button
                class="record-view-mobile-switch-btn"
                :class="{ 'record-view-mobile-switch-btn--active': mobileView === 'sidepanel' }"
                @click="mobileView = 'sidepanel'"
            >
                <v-icon icon="mdi-view-dashboard-outline" size="16" />
                {{ languages.label('LBL_MINT_RECORDVIEW_MOBILE_SWITCH_SIDEPANEL') }}
            </button>
        </div>
        <div class="record-view-layout">
            <div v-if="!store.hasSidepanel || !smAndDown || mobileView === 'record'" class="record-panels">
                <MintPanel v-for="panel in store.panels" :key="panel.key" :component="panel.component" :data="panel.data" />
            </div>
            <template v-if="store.hasSidepanel && !smAndDown">
                <div
                    class="record-view-resize-handle"
                    :class="{
                        'record-view-resize-handle--collapsed': isCollapsed,
                        'record-view-resize-handle--at-limit': isAtLimit,
                    }"
                    @mousedown.prevent="isCollapsed ? undefined : onResizeStart($event)"
                >
                    <button
                        class="record-view-collapse-btn"
                        @click.stop="toggleCollapse"
                        :title="isCollapsed ? languages.label('LBL_EXPAND') : languages.label('LBL_COLLAPSE')"
                    >
                        <v-icon :icon="isCollapsed ? 'mdi-chevron-left' : 'mdi-chevron-right'" size="14" />
                    </button>
                    <div class="record-view-resize-bar" />
                </div>
                <MintSidepanel v-if="!isCollapsed" :widgets="store.sidepanelWidgets" :width="sidepanelWidth" />
            </template>
            <MintSidepanel
                v-else-if="store.hasSidepanel && smAndDown && mobileView === 'sidepanel'"
                class="record-view-sidepanel--mobile"
                :widgets="store.sidepanelWidgets"
                :width="sidepanelWidth"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, onUnmounted, ref, computed, watch } from 'vue'
import { useDisplay } from 'vuetify'
import MintPanel from '@/components/MintPanel/MintPanel.vue'
import MintSidepanel from '@/components/MintSidepanel/MintSidepanel.vue'
import { useRecordViewStore } from './RecordViewStore'
import { useLanguagesStore } from '@/store/languages'
import { useBackendStore } from '@/store/backend'
import { useRoute } from 'vue-router'
import { useStatusBoxesStore } from '@/store/statusBoxes'
import { useRouter } from 'vue-router'
import { useACL, ACLView } from '@/composables/useACL'
import { mintApi } from '@/api/api'

const SIDEPANEL_WIDTH_KEY = 'mint-sidepanel-width'
const SIDEPANEL_COLLAPSED_KEY = 'mint-sidepanel-collapsed'
const MIN_SIDEPANEL_WIDTH = 200
const MAX_SIDEPANEL_WIDTH = 600

const { mdAndDown, smAndDown } = useDisplay()

const sidepanelWidth = ref(320)
const isCollapsed = ref(false)
const isAtLimit = computed(
    () => sidepanelWidth.value <= MIN_SIDEPANEL_WIDTH || sidepanelWidth.value >= MAX_SIDEPANEL_WIDTH,
)

// smAndDown (mobile/tablet) doesn't use isCollapsed/resize at all — it exclusively
// shows either the record or the sidepanel, picked via this switch, defaulting to 'record'.
const mobileView = ref<'record' | 'sidepanel'>('record')

function toggleCollapse() {
    isCollapsed.value = !isCollapsed.value
    localStorage.setItem(SIDEPANEL_COLLAPSED_KEY, isCollapsed.value ? '1' : '0')
}

let _startX = 0
let _startWidth = 0
let _isResizing = false

function onResizeStart(e: MouseEvent) {
    _isResizing = true
    _startX = e.clientX
    _startWidth = sidepanelWidth.value
    document.addEventListener('mousemove', onResizeMove)
    document.addEventListener('mouseup', onResizeEnd)
    document.body.style.cursor = 'col-resize'
    document.body.style.userSelect = 'none'
}

function onResizeMove(e: MouseEvent) {
    if (!_isResizing) return
    const delta = _startX - e.clientX
    sidepanelWidth.value = Math.min(MAX_SIDEPANEL_WIDTH, Math.max(MIN_SIDEPANEL_WIDTH, _startWidth + delta))
}

function onResizeEnd() {
    _isResizing = false
    document.removeEventListener('mousemove', onResizeMove)
    document.removeEventListener('mouseup', onResizeEnd)
    document.body.style.cursor = ''
    document.body.style.userSelect = ''
    localStorage.setItem(SIDEPANEL_WIDTH_KEY, String(sidepanelWidth.value))
}

onUnmounted(() => {
    document.removeEventListener('mousemove', onResizeMove)
    document.removeEventListener('mouseup', onResizeEnd)
})

const store = useRecordViewStore()
const languages = useLanguagesStore()
const backend = useBackendStore()
const route = useRoute()
const router = useRouter()

store.resetBean()

onMounted(async () => {
    const savedWidth = localStorage.getItem(SIDEPANEL_WIDTH_KEY)
    if (savedWidth) {
        const w = parseInt(savedWidth, 10)
        if (!isNaN(w)) sidepanelWidth.value = Math.min(MAX_SIDEPANEL_WIDTH, Math.max(MIN_SIDEPANEL_WIDTH, w))
    }
    isCollapsed.value = localStorage.getItem(SIDEPANEL_COLLAPSED_KEY) === '1'

    await store.bean.init().catch(recordAccessError)

    const pathSegments = route.path.split('/')
    if (pathSegments.includes('EditView')) {
        store.view = 'edit'
    }

    if (store.bean.isNew) {
        if (Object.keys(route.query).includes('copy_id')) {
            // Clear virtual fields set by INIT logic (fields not in vardefs) so they
            // don't interfere with explicit values provided by the duplicate action.
            const initRule = store.bean.logic.rules.find(
                (rule: any) => rule.key === 'init' && rule.trigger
            )
            if (initRule?.logic?.update) {
                const clearMap: Record<string, null> = {}
                Object.keys(initRule.logic.update).forEach((field) => {
                    if (!store.bean.fieldDefs[field]) {
                        clearMap[field] = null
                    }
                })
                store.bean.updateFields(clearMap)
            }
        }

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
    } else {
        await mintApi.post('Trackers', {
            record: store.bean?.id,
            module_name: store.bean?.module
        }, { rawError: true});
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

.record-view-layout {
    display: flex;
    flex-direction: row;
    align-items: flex-start;
}

.record-view-resize-handle {
    flex-shrink: 0;
    width: 24px;
    align-self: stretch;
    cursor: col-resize;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-top: 8px;
    gap: 8px;

    &--collapsed {
        cursor: default;
    }

    &--at-limit .record-view-resize-bar {
        background: rgba(var(--v-theme-primary), 0.45) !important;
    }
}

.record-view-collapse-btn {
    flex-shrink: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: rgba(0, 0, 0, 0.07);
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.15s;

    &:hover {
        background: rgba(0, 0, 0, 0.16);
    }
}

.record-view-resize-bar {
    flex-shrink: 0;
    width: 3px;
    height: 40px;
    border-radius: 2px;
    background: rgba(0, 0, 0, 0.13);
    transition: background-color 0.2s;

    .record-view-resize-handle:not(.record-view-resize-handle--collapsed):hover &,
    .record-view-resize-handle:not(.record-view-resize-handle--collapsed):active & {
        background: rgba(0, 0, 0, 0.45);
    }
}

.record-panels {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 32px;
}

.record-view-mobile-switch {
    display: flex;
    gap: 4px;
    width: 100%;
    padding: 4px;
    margin-bottom: 16px;
    background: rgba(0, 0, 0, 0.05);
    border-radius: 8px;
}

.record-view-mobile-switch-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    border: none;
    background: transparent;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    line-height: 1;
    cursor: pointer;
    color: rgba(var(--v-theme-secondary), 0.6);
    transition: background 0.15s, color 0.15s;

    &--active {
        background: rgb(var(--v-theme-surface));
        color: rgb(var(--v-theme-secondary));
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
    }
}

.record-view-sidepanel--mobile {
    width: 100% !important;
    top: 0 !important;
}
</style>
