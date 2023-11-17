<template>
    <div class="setup-wizard-locale-settings-container">
        <v-select
            v-model="store.setupData.time_zone"
            :items="timezonesList"
            density="comfortable"
            variant="outlined"
            :label="languages.label('LBL_MINT4_SETUP_WIZARD_LOCALE_SETTINGS_TIME_ZONE')"
            hide-details
        />
        <v-select
            v-model="store.setupData.time_format"
            :items="timeFormats"
            density="comfortable"
            variant="outlined"
            :label="languages.label('LBL_MINT4_SETUP_WIZARD_LOCALE_SETTINGS_TIME_FORMAT')"
            hide-details
        />
        <v-select
            v-model="store.setupData.date_format"
            :items="dateFormats"
            density="comfortable"
            variant="outlined"
            :label="languages.label('LBL_MINT4_SETUP_WIZARD_LOCALE_SETTINGS_DATE_FORMAT')"
            hide-details
        />
        <v-select
            v-model="store.setupData.display_name_format"
            :items="nameFormats"
            density="comfortable"
            variant="outlined"
            :label="languages.label('LBL_MINT4_SETUP_WIZARD_LOCALE_SETTINGS_NAME_FORMAT')"
            hide-details
        />
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useBackendStore } from '@/store/backend'
import { useLanguagesStore } from '@/store/languages'
import { useSetupWizardStore } from '../SetupWizardStore'

const backend = useBackendStore()
const languages = useLanguagesStore()
const store = useSetupWizardStore()

const timezonesList = computed(() => Object.entries(backend.initData?.global?.time_zones ?? {}).map(([value, title]) => ({ value, title })))
const nameFormats = computed(() => Object.entries(backend.initData?.global?.name_formats ?? {}).map(([value, title]) => ({ value, title })))
const timeFormats = computed(() => Object.entries(backend.initData?.global?.time_formats ?? {}).map(([value, title]) => ({ value, title })))
const dateFormats = computed(() => Object.entries(backend.initData?.global?.date_formats ?? {}).map(([value, title]) => ({ value, title })))
</script>

<style scoped lang="scss">
.setup-wizard-locale-settings-container {
    display: flex;
    flex-direction: column;
    gap: 32px;
    margin-top: 8px;
}

.v-input {
    :deep(.v-field__outline__start),
    :deep(.v-field__outline__notch)::before,
    :deep(.v-field__outline__notch)::after,
    :deep(.v-field__outline__end) {
        opacity: 1;
    }

    :deep(.v-field__outline__start),
    :deep(.v-field__outline__notch)::before,
    :deep(.v-field__outline__notch)::after,
    :deep(.v-field__outline__end) {
        border-color: #dbdbdb;
    }

    &:hover {
        :deep(.v-field__outline__start),
        :deep(.v-field__outline__notch)::before,
        :deep(.v-field__outline__notch)::after,
        :deep(.v-field__outline__end) {
            border-color: rgb(var(--v-theme-primary));
        }
    }

    :deep(.v-field--focused .v-field__outline__start),
    :deep(.v-field--focused .v-field__outline__notch)::before,
    :deep(.v-field--focused .v-field__outline__notch)::after,
    :deep(.v-field--focused .v-field__outline__end) {
        border-color: rgb(var(--v-theme-primary));
    }

    :deep(.v-field-label.v-field-label--floating) {
        background: rgb(var(--v-theme-surface));
        color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
        opacity: 1;
        padding: 0px 2px;
    }
}
</style>
