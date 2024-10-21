<template>
    <div class="record-view">
        <div class="record-panels">
            <MintPanel v-for="panel in store.panels" :key="panel.key" :component="panel.component" :data="panel.data" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import MintPanel from '@/components/MintPanel/MintPanel.vue'
import { useRecordViewStore } from './RecordViewStore'

const store = useRecordViewStore()

store.resetBean()
store.$reset()

onMounted(() => {
    store.fetchBean()
})
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
