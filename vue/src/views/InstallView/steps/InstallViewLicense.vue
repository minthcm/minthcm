<template>
    <div class="install-view-license">
        <div class="install-view-license-content">
            {{ store.license }}
        </div>
    </div>
</template>

<script setup lang="ts">
import { useInstallViewStore } from '../InstallViewStore'

const store = useInstallViewStore()

defineExpose({
    nextBtn: {
        label: 'Accept',
    },
})
</script>

<style scoped lang="scss">
.install-view-license {
    border: thin solid rgba(var(--v-theme-on-surface), var(--v-border-opacity));
    border-radius: 4px;
    overflow: hidden;
    padding: 8px;
    display: flex;

    .install-view-license-content {
        min-height: 100%;
        max-height: 300px;
        overflow: auto;
        letter-spacing: 0.5px;
        white-space: pre-line;
    }
}
</style>
