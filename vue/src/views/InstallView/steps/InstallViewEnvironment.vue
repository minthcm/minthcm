<template>
    <div class="install-view-environment">
        <div v-for="(item, key) in store.environment" :key="key" class="install-view-environment-item">
            <v-icon v-if="item.status === 1" icon="mdi-check-circle" color="#097c31" size="20" />
            <v-icon v-else icon="mdi-close-circle" color="#ac0221" size="20" />
            <div style="display: flex; gap: 8px; flex-wrap: wrap">
                <span class="install-view-environment-item-title">{{ item.label }}</span>
                <span v-if="item.message" class="install-view-environment-item-message">{{ item.message }}</span>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useInstallViewStore } from '../InstallViewStore'

const store = useInstallViewStore()

const isOk = computed(() => {
    return store.environment && Object.values(store.environment).every((item) => item.status === 1)
})

const nextBtnLabel = computed(() => (isOk.value ? 'Next' : 'Recheck'))

defineExpose({
    nextBtn: {
        label: nextBtnLabel,
        action: () => {
            if (isOk.value) {
                store.nextStep()
            } else {
                store.recheckEnvironment()
            }
        },
    },
})
</script>

<style scoped lang="scss">
.install-view-environment {
    display: flex;
    flex-direction: column;
    gap: 16px;

    .install-view-environment-item {
        display: flex;
        gap: 16px;
        align-items: center;
        letter-spacing: 0.5px;

        .install-view-environment-item-title {
            font-weight: 600;
        }

        .install-view-environment-item-message {
            color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
        }
    }
}
</style>
