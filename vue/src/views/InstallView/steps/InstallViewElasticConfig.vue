<template>
    <div class="install-view-elastic-config">
        <v-text-field
            v-model="store.elasticConfig.host"
            label="Elastic host"
            variant="outlined"
            density="comfortable"
            :error-messages="errors.host"
            hide-details="auto"
        />
        <v-text-field
            v-model="store.elasticConfig.port"
            label="Elastic port"
            variant="outlined"
            density="comfortable"
            :error-messages="errors.port"
            hide-details="auto"
        />
        <v-text-field
            v-model="store.elasticConfig.username"
            label="Username (optional)"
            variant="outlined"
            density="comfortable"
            hide-details
        />
        <v-text-field
            v-model="store.elasticConfig.password"
            type="password"
            label="Password (optional)"
            variant="outlined"
            density="comfortable"
            autocomplete="one-time-code"
            hide-details
        />
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useInstallViewStore } from '../InstallViewStore'

const store = useInstallViewStore()

const errors = ref({
    host: '',
    port: '',
})

defineExpose({
    nextBtn: {
        action: async () => {
            if (await store.validateElastic()) {
                store.nextStep()
            }
        },
    },
    validate: () => {
        errors.value = {
            host: '',
            port: '',
        }
        let isValid = true
        if (!store.elasticConfig.host?.trim()) {
            errors.value.host = 'Field is required'
            isValid = false
        }
        if (!store.elasticConfig.port) {
            errors.value.port = 'Field is required'
            isValid = false
        }
        return isValid
    },
})
</script>

<style scoped lang="scss">
.install-view-elastic-config {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.v-input {
    :deep(.v-field:not(.v-field--error) .v-field__outline__start),
    :deep(.v-field:not(.v-field--error) .v-field__outline__notch)::before,
    :deep(.v-field:not(.v-field--error) .v-field__outline__notch)::after,
    :deep(.v-field:not(.v-field--error) .v-field__outline__end) {
        opacity: 1;
    }

    :deep(.v-field:not(.v-field--error) .v-field__outline__start),
    :deep(.v-field:not(.v-field--error) .v-field__outline__notch)::before,
    :deep(.v-field:not(.v-field--error) .v-field__outline__notch)::after,
    :deep(.v-field:not(.v-field--error) .v-field__outline__end) {
        border-color: #dbdbdb;
    }

    &:hover {
        :deep(.v-field:not(.v-field--error) .v-field__outline__start),
        :deep(.v-field:not(.v-field--error) .v-field__outline__notch)::before,
        :deep(.v-field:not(.v-field--error) .v-field__outline__notch)::after,
        :deep(.v-field:not(.v-field--error) .v-field__outline__end) {
            border-color: rgb(var(--v-theme-primary));
        }
    }

    :deep(.v-field:not(.v-field--error) .v-field--focused .v-field__outline__start),
    :deep(.v-field:not(.v-field--error) .v-field--focused .v-field__outline__notch)::before,
    :deep(.v-field:not(.v-field--error) .v-field--focused .v-field__outline__notch)::after,
    :deep(.v-field:not(.v-field--error) .v-field--focused .v-field__outline__end) {
        border-color: rgb(var(--v-theme-primary));
    }

    :deep(.v-field:not(.v-field--error) .v-field-label.v-field-label--floating) {
        background: rgb(var(--v-theme-surface));
        color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
        opacity: 1;
        padding: 0px 2px;
    }
}
</style>
