<template>
    <div class="record-view">
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

const store = useRecordViewStore()
const languages = useLanguagesStore()
const backend = useBackendStore()

store.resetBean()
store.$reset()

onMounted(() => {
    store.fetchBean()
})

watch(
    () => store.bean.syncAttributes,
    (newVal) => {
        if (!newVal.name || !newVal.module_name) return
        document.title = `${newVal.name} | ${languages.label('LBL_MODULE_NAME', newVal.module_name)} | ${backend.initData?.systemName}`
    },
)
</script>

<style scoped lang="scss">
.record-view {
    padding: 32px;
}

.record-panels {
    display: flex;
    flex-direction: column;
    gap: 32px;
}
</style>
