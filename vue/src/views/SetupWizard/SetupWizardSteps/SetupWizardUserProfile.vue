<template>
    <div class="setup-wizard-user-profile-container">
        <v-text-field
            v-model="store.setupData.first_name"
            density="comfortable"
            variant="outlined"
            :label="languages.label('LBL_MINT4_SETUP_WIZARD_USER_PROFILE_FIRST_NAME')"
            hide-details
        />
        <v-text-field
            v-model="store.setupData.last_name"
            density="comfortable"
            variant="outlined"
            :label="languages.label('LBL_MINT4_SETUP_WIZARD_USER_PROFILE_LAST_NAME')"
            hide-details="auto"
            :error-messages="lastNameError ? languages.label('LBL_MINT4_ERROR_REQUIRED_FIELD') : ''"
        />
        <v-text-field
            v-model="store.setupData.email"
            density="comfortable"
            variant="outlined"
            :label="languages.label('LBL_MINT4_SETUP_WIZARD_USER_PROFILE_EMAIL')"
            hide-details
        />
    </div>
</template>

<script setup lang="ts">
import { defineExpose, ref } from 'vue'
import { useSetupWizardStore } from '../SetupWizardStore'
import { useLanguagesStore } from '@/store/languages'

const store = useSetupWizardStore()
const languages = useLanguagesStore()

const lastNameError = ref(false)

function validate() {
    lastNameError.value = false
    if (!store.setupData.last_name?.trim()) {
        lastNameError.value = true
        return false
    }
    return true
}

defineExpose({
    validate,
})
</script>

<style scoped lang="scss">
.setup-wizard-user-profile-container {
    display: flex;
    flex-direction: column;
    gap: 32px;
    margin-top: 8px;
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
