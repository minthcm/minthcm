<template>
    <div class="admin-view">
        <div v-if="!viewMeta" class="admin-view-not-found">
            <v-alert type="warning" :text="languages.label('LBL_RECORD_NOT_FOUND')" />
        </div>
        <template v-else>
            <div class="admin-panels">
                <MintPanel
                    v-for="panelKey in panelOrder"
                    :key="panelKey"
                    :component="viewMeta.panels[panelKey].component"
                    :data="getPanelData(panelKey)"
                />
            </div>
        </template>
    </div>
</template>

<script setup lang="ts">
import { computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import MintPanel from '@/components/MintPanel/MintPanel.vue'
import { useAdminViewStore } from './AdminViewStore'
import { useBackendStore } from '@/store/backend'
import { useLanguagesStore } from '@/store/languages'

const route = useRoute()
const store = useAdminViewStore()
const backend = useBackendStore()
const languages = useLanguagesStore()
const viewName = computed(() => route.params.viewName as string)
const viewMeta = computed(() => store.meta)

const panelOrder = computed<string[]>(() => {
    if (!viewMeta.value) return []
    return viewMeta.value.order ?? Object.keys(viewMeta.value.panels ?? {})
})

function getPanelData(panelKey: string) {
    const panel = viewMeta.value?.panels?.[panelKey]
    if (!panel?.data) return undefined
    return {
        ...panel.data,
        fieldStates: store.fieldStates,
        record: store.record,
        onUpdateField: store.updateField,
    }
}

onMounted(async () => {
    const adminViews = backend.initData?.adminViews ?? {}
    store.loadMeta(adminViews, viewName.value)

    if (store.meta) {
        await store.loadConfig(viewName.value)
        store.initFieldStates(store.getAllFieldDefs())
    }
})
</script>

<style scoped lang="scss">
.admin-view {
    padding: 32px;
    display: flex;
    flex-direction: column;
    gap: 32px;
}

.admin-panels {
    display: flex;
    flex-direction: column;
    gap: 32px;
}
</style>
