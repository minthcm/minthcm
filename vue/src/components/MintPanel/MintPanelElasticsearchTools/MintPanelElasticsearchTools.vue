<template>
    <div class="es-tools-panel">
        <div class="panel-header">
            <div class="panel-title">{{ languages.label('LBL_ELASTIC_SEARCH_TOOLS', 'Administration') }}</div>
        </div>
        <div class="section">
            <div class="actions-row">
                <MintButton
                    variant="regular"
                    :loading="testing"
                    :text="languages.label('LBL_ELASTIC_SEARCH_TEST_CONNECTION', 'Administration')"
                    icon="mdi-connection"
                    @click="testConnection"
                />
                <MintButton
                    variant="primary"
                    :loading="scheduling"
                    :text="languages.label('LBL_ELASTIC_SEARCH_INDEX_SCHEDULE_FULL', 'Administration')"
                    icon="mdi-refresh"
                    @click="scheduleFullIndex"
                />
                <MintButton
                    variant="regular"
                    icon="mdi-refresh"
                    :loading="loadingModules"
                    :tooltip="languages.label('LBL_ESLIST_REFRESH', 'Administration')"
                    class="ml-auto"
                    @click="loadModules"
                />
            </div>
            <v-alert
                v-if="testResult"
                :type="testResult.status === 'success' ? 'success' : 'error'"
                density="compact"
                :text="testResultText"
            />

            <v-skeleton-loader v-if="loadingModules" type="table-row@5" />
            <v-data-table
                v-else
                density="compact"
                :headers="tableHeaders"
                :items="modules"
                item-value="module"
                must-sort
                :sort-by="[{ key: 'label', order: 'asc' }]"
            >
                <template v-slot:item.total="{ item }">
                    <span class="text-right d-block">{{ item.total }}</span>
                </template>
                <template v-slot:item.unindexed="{ item }">
                    <span class="text-right d-block">{{ item.unindexed }}</span>
                </template>
                <template v-slot:item.enabled="{ item }">
                    <v-chip :color="item.enabled ? 'success' : 'default'" size="small">
                        {{
                            item.enabled
                                ? languages.label('LBL_ENABLED', 'Administration')
                                : languages.label('LBL_DISABLED', 'Administration')
                        }}
                    </v-chip>
                </template>
                <template v-slot:item.actions="{ item }">
                    <div class="action-icons">
                        <v-tooltip
                            location="top"
                            :text="
                                item.enabled
                                    ? languages.label('LBL_REMOVE_FROM_INDEXATION', 'Administration')
                                    : languages.label('LBL_ADD_TO_INDEXATION', 'Administration')
                            "
                        >
                            <template #activator="{ props }">
                                <v-icon
                                    v-bind="props"
                                    color="secondary"
                                    size="small"
                                    :icon="item.enabled ? 'mdi-database-minus' : 'mdi-database-plus'"
                                    class="action-icon"
                                    v-ripple
                                    @click="openToggleDialog(item)"
                                />
                            </template>
                        </v-tooltip>
                        <v-tooltip
                            v-if="item.enabled"
                            location="top"
                            :text="languages.label('LBL_REPAIR_ELASTICSEARCH_INDEX', 'Administration')"
                        >
                            <template #activator="{ props }">
                                <v-icon
                                    v-bind="props"
                                    color="secondary"
                                    size="small"
                                    :icon="indexingModules[item.module] ? 'mdi-loading mdi-spin' : 'mdi-wrench'"
                                    class="action-icon"
                                    v-ripple
                                    @click="indexNow(item)"
                                />
                            </template>
                        </v-tooltip>
                        <span v-if="indexErrors[item.module]" class="text-error text-caption ml-2">
                            {{ indexErrors[item.module] }}
                        </span>
                    </div>
                </template>
            </v-data-table>
        </div>

        <!-- Toggle confirm dialog removed — handled via MintPopup -->
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed, markRaw } from 'vue'
import { useLanguagesStore } from '@/store/languages'
import { useAdminViewStore } from '@/views/AdminView/AdminViewStore'
import MintButton from '@/components/MintButtons/MintButton.vue'
import { useStatusBoxesStore } from '@/store/statusBoxes'
import { usePopupsStore } from '@/store/popups'
import MintPopupEsModuleToggle from '@/components/MintPopups/MintPopupEsModuleToggle.vue'
import {
    testElasticsearchConnection,
    scheduleElasticsearchIndex,
    getElasticsearchModules,
    indexElasticsearchModule,
    toggleElasticsearchModule,
    EsModule,
    EsConnectionResult,
} from '@/api/adminElasticsearchApi'

const languages = useLanguagesStore()
const adminStore = useAdminViewStore()
const statusBoxes = useStatusBoxesStore()
const popups = usePopupsStore()

// -- Test connection state
const testing = ref(false)
const testResult = ref<EsConnectionResult | null>(null)

const testResultText = computed(() => {
    if (!testResult.value) return ''
    if (testResult.value.status === 'success') {
        return `OK — ${testResult.value.ping ?? 0}ms`
    }
    return testResult.value.error ?? 'Connection failed'
})

async function testConnection() {
    testing.value = true
    testResult.value = null
    try {
        // Read current field values directly — do NOT call loadConfig() which
        // would overwrite the form with server-masked '***' password values.
        const res = await testElasticsearchConnection({
            host: String(adminStore.fieldStates['host']?.model ?? adminStore.record.host ?? ''),
            user: String(adminStore.fieldStates['user']?.model ?? adminStore.record.user ?? '') || undefined,
            pass: String(adminStore.fieldStates['pass']?.model ?? adminStore.record.pass ?? '') || undefined,
        })
        testResult.value = res.data
    } catch (e: any) {
        testResult.value = { status: 'fail', error: e?.message ?? 'Unknown error' }
    } finally {
        testing.value = false
    }
}

// -- Schedule full index
const scheduling = ref(false)

async function scheduleFullIndex() {
    scheduling.value = true
    try {
        await scheduleElasticsearchIndex()
        statusBoxes.showStatus('es-schedule-success', {
            type: 'success',
            message: languages.label('LBL_ELASTIC_SEARCH_INDEX_SCHEDULE_FULL_SUCCESS', 'Administration'),
            autoClose: true,
            autoCloseDelay: 8000,
        })
    } finally {
        scheduling.value = false
    }
}

// -- Module table
const modules = ref<EsModule[]>([])
const loadingModules = ref(false)
const indexingModules = ref<Record<string, boolean>>({})
const indexErrors = ref<Record<string, string>>({})

const tableHeaders = computed(() => [
    { title: languages.label('LBL_MODULE', 'Administration'), key: 'label' },
    { title: languages.label('LBL_TOTAL_RECORDS', 'Administration'), key: 'total', align: 'end' as const },
    { title: languages.label('LBL_UNINDEXED', 'Administration'), key: 'unindexed', align: 'end' as const },
    { title: languages.label('LBL_INDEXATION', 'Administration'), key: 'enabled' },
    { title: languages.label('LBL_ACTIONS', 'Administration'), key: 'actions', sortable: false },
])

async function loadModules() {
    loadingModules.value = true
    try {
        const res = await getElasticsearchModules()
        modules.value = res.data as EsModule[]
    } finally {
        loadingModules.value = false
    }
}

async function indexNow(mod: EsModule) {
    indexingModules.value[mod.module] = true
    delete indexErrors.value[mod.module]
    try {
        const res = await indexElasticsearchModule(mod.module)
        const indexed = (res.data as { indexed: number }).indexed ?? 0
        const idx = modules.value.findIndex((m) => m.module === mod.module)
        if (idx !== -1) {
            modules.value[idx] = {
                ...modules.value[idx],
                unindexed: Math.max(0, modules.value[idx].unindexed - indexed),
            }
        }
    } catch (e: any) {
        indexErrors.value[mod.module] = e?.response?.data?.message ?? e?.message ?? 'Failed'
    } finally {
        indexingModules.value[mod.module] = false
    }
}

// -- Toggle module dialog
const toggleSaving = ref(false)

function openToggleDialog(mod: EsModule) {
    const title = mod.enabled
        ? `${languages.label('LBL_REMOVE_FROM_INDEXATION', 'Administration')} ${mod.label}`
        : `${languages.label('LBL_ADD_TO_INDEXATION', 'Administration')} ${mod.label}`

    popups.showPopup({
        title,
        component: markRaw(MintPopupEsModuleToggle),
        data: {
            // Adding: show checkbox if ES list view exists (ask to enable new Vue list view)
            // Removing: show checkbox if legacy list view exists (ask to enable legacy list view)
            showCheckbox: mod.enabled ? mod.has_list_view : mod.has_es_list_view,
            checkboxLabel: languages.label(
                mod.enabled ? 'LBL_ENABLE_LEGACY_LIST_VIEW' : 'LBL_ENABLE_NEW_LIST_VIEW',
                'Administration',
            ),
            onCancel: () => {},
            onConfirm: async (checkboxValue: boolean) => {
                const newEnabled = !mod.enabled
                toggleSaving.value = true
                if (newEnabled) {
                    // Enabling triggers a synchronous reindex of the module on the backend
                    indexingModules.value[mod.module] = true
                }
                delete indexErrors.value[mod.module]
                try {
                    const payload: { enabled: boolean; enable_legacy_view?: boolean } = {
                        enabled: newEnabled,
                    }
                    if (newEnabled) {
                        // Adding: legacy = true unless user opted into new ES list view
                        payload.enable_legacy_view = mod.has_es_list_view ? !checkboxValue : true
                    } else if (checkboxValue) {
                        // Removing + checkbox checked: enable legacy list view
                        payload.enable_legacy_view = true
                    }
                    // Removing + checkbox unchecked: omit enable_legacy_view (no change to legacy_views.php)
                    const res = await toggleElasticsearchModule(mod.module, payload)
                    const idx = modules.value.findIndex((m) => m.module === mod.module)
                    if (idx !== -1) {
                        const indexed = res.data.indexed ?? 0
                        modules.value[idx] = {
                            ...modules.value[idx],
                            enabled: newEnabled,
                            unindexed: newEnabled
                                ? Math.max(0, modules.value[idx].unindexed - indexed)
                                : modules.value[idx].unindexed,
                        }
                    }
                    if (res.data.indexError) {
                        indexErrors.value[mod.module] = res.data.indexError
                    }
                    popups.closeAll()
                } catch (e: any) {
                    statusBoxes.showStatus('es-module-toggle-error', {
                        type: 'error',
                        message: languages.label('LBL_ELASTIC_SEARCH_MODULE_TOGGLE_ERROR', 'Administration'),
                        autoClose: true,
                    })
                } finally {
                    toggleSaving.value = false
                    indexingModules.value[mod.module] = false
                }
            },
        },
    })
}

onMounted(() => {
    loadModules()
})
</script>

<style scoped lang="scss">
.es-tools-panel {
    background: rgb(var(--v-theme-surface));
    border-radius: 16px;
    box-shadow: 0px 1px 12px #00997619;
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.panel-header {
    display: flex;
    align-items: center;
    margin: -24px -24px 0;
    padding: 24px 24px 16px;
    border-bottom: 1px solid #dbdbdb;

    .panel-title {
        flex: 1;
        font-size: 15px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.47px;
        color: rgb(var(--v-theme-secondary));
    }
}

.section {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.actions-row {
    display: flex;
    gap: 16px;
    align-items: center;
    flex-wrap: wrap;
}

.action-icons {
    display: flex;
    align-items: center;
    gap: 8px;
}

.action-icon {
    cursor: pointer;
    border-radius: 50%;
    padding: 8px;
    transition: background-color 0.2s cubic-bezier(0.4, 0, 0.2, 1);

    &:hover {
        background-color: rgba(var(--v-theme-on-surface), 0.04);
    }
}

.text-error {
    color: rgb(var(--v-theme-error));
}
</style>
