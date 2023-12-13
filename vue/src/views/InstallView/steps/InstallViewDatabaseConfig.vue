<template>
    <div class="install-view-database-config">
        <v-text-field
            v-model="store.databaseConfig.dbname"
            label="Database name"
            variant="outlined"
            density="comfortable"
            :error-messages="errors.dbname"
            hide-details="auto"
        />
        <v-text-field
            v-model="store.databaseConfig.host"
            label="Database host"
            variant="outlined"
            density="comfortable"
            :error-messages="errors.host"
            hide-details="auto"
        />
        <v-text-field
            v-model="store.databaseConfig.port"
            label="Database port"
            variant="outlined"
            density="comfortable"
            :error-messages="errors.port"
            hide-details="auto"
        />
        <v-text-field
            v-model="store.databaseConfig.username"
            label="Username"
            variant="outlined"
            density="comfortable"
            :error-messages="errors.username"
            hide-details="auto"
        />
        <v-text-field
            v-model="store.databaseConfig.password"
            type="password"
            label="Password (optional)"
            variant="outlined"
            density="comfortable"
            autocomplete="one-time-code"
            hide-details="auto"
        />
        <v-autocomplete
            v-model="store.databaseConfig.collation"
            :items="INSTALL_CONFIG.db_collations"
            label="Database collation"
            variant="outlined"
            density="comfortable"
            hide-details="auto"
        />
    </div>
</template>

<script setup lang="ts">
import { useInstallViewStore } from '../InstallViewStore'
import { INSTALL_CONFIG } from '../InstallViewConfig'
import { ref } from 'vue'

const store = useInstallViewStore()

const errors = ref({
    dbname: '',
    host: '',
    port: '',
    username: '',
})

defineExpose({
    nextBtn: {
        action: async () => {
            if (await store.validateDb()) {
                store.nextStep()
            }
        },
    },
    validate: () => {
        errors.value = {
            dbname: '',
            host: '',
            port: '',
            username: '',
        }
        let isValid = true
        if (!store.databaseConfig.dbname?.trim()) {
            errors.value.dbname = 'Field is required'
            isValid = false
        }
        if (!store.databaseConfig.host?.trim()) {
            errors.value.host = 'Field is required'
            isValid = false
        }
        if (!store.databaseConfig.port) {
            errors.value.port = 'Field is required'
            isValid = false
        }
        if (!store.databaseConfig.username?.trim()) {
            errors.value.username = 'Field is required'
            isValid = false
        }
        return isValid
    },
})
</script>

<style scoped lang="scss">
.install-view-database-config {
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
