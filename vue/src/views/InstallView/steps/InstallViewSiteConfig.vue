<template>
    <div class="install-view-site-config">
        <v-text-field
            v-model="store.siteConfig.url"
            label="Site URL"
            variant="outlined"
            density="comfortable"
            :error-messages="errors.url"
            hide-details="auto"
        />
        <v-text-field
            v-model="store.siteConfig.username"
            label="Admin username"
            variant="outlined"
            density="comfortable"
            :error-messages="errors.username"
            hide-details="auto"
        />
        <v-text-field
            v-model="store.siteConfig.password"
            :type="showPassword ? 'text' : 'password'"
            label="Admin password"
            variant="outlined"
            density="comfortable"
            autocomplete="one-time-code"
            :append-inner-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
            @click:append-inner="showPassword = !showPassword"
            :error-messages="errors.password"
            hide-details="auto"
        />
        <div class="install-view-dummy-data">
            <v-checkbox v-model="store.siteConfig.demodata" color="secondary" label="Import dummy data" hide-details />
            <v-tooltip
                location="top start"
                text="A set of dummy records will be installed on your system for demonstration purposes."
            >
                <template v-slot:activator="{ props }">
                    <div class="install-view-dummy-data-help">
                        <v-icon v-bind="props" icon="mdi-help" size="14" color="white" />
                    </div>
                </template>
            </v-tooltip>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useInstallViewStore } from '../InstallViewStore'

const store = useInstallViewStore()

const showPassword = ref(false)

const errors = ref({
    url: '',
    username: '',
    password: '',
})

defineExpose({
    validate: () => {
        errors.value = {
            url: '',
            username: '',
            password: '',
        }
        let isValid = true
        if (!store.siteConfig.url?.trim()) {
            errors.value.url = 'Field is required'
            isValid = false
        }
        if (!store.siteConfig.username?.trim()) {
            errors.value.username = 'Field is required'
            isValid = false
        }
        if (!store.siteConfig.password?.trim()) {
            errors.value.password = 'Field is required'
            isValid = false
        }
        return isValid
    },
})
</script>

<style scoped lang="scss">
.install-view-site-config {
    display: flex;
    flex-direction: column;
    gap: 24px;

    .install-view-dummy-data {
        display: flex;
        gap: 16px;
        align-items: center;
        width: fit-content;

        .install-view-dummy-data-help {
            border-radius: 50%;
            background: #00000061;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    }
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
