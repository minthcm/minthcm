<template>
    <div class="setup-wizard-complete-container">
        <div class="setup-wizard-complete-title">
            {{ languages.label('LBL_MINT4_SETUP_WIZARD_COMPLETE_SCREEN_TITLE') }}
        </div>
        <div class="setup-wizard-complete-subtitle">
            {{ languages.label('LBL_MINT4_SETUP_WIZARD_COMPLETE_SCREEN_SUBTITLE') }}
        </div>
        <div class="setup-wizard-complete-link" @click="openInNew('https://minthcm.org')">
            <v-icon icon="mdi-earth" size="20" />
            <span>{{ languages.label('LBL_MINT4_SETUP_WIZARD_COMPLETE_SCREEN_MINTHCMORG') }}</span>
            <v-icon icon="mdi-open-in-new" class="ms-auto" size="20" />
        </div>
        <div class="setup-wizard-complete-link" @click="openInNew('https://minthcm.org/documentation')">
            <v-icon icon="mdi-file-document-multiple" size="20" />
            <span>{{ languages.label('LBL_MINT4_SETUP_WIZARD_COMPLETE_SCREEN_DOCUMENTATION') }}</span>
            <v-icon icon="mdi-open-in-new" class="ms-auto" size="20" />
        </div>
        <div class="setup-wizard-complete-link" @click="openInNew('https://minthcm.org/support')">
            <v-icon icon="mdi-forum" size="20" />
            <span>{{ languages.label('LBL_MINT4_SETUP_WIZARD_COMPLETE_SCREEN_SUPPORT') }}</span>
            <v-icon icon="mdi-open-in-new" class="ms-auto" size="20" />
        </div>
        <MintButton
            :text="languages.label('LBL_MINT4_SETUP_WIZARD_COMPLETE_SCREEN_BTN')"
            variant="primary"
            class="mt-6"
            @click="$router.push({ name: 'dashboard' })"
        />
    </div>
</template>

<script setup lang="ts">
import { useLanguagesStore } from '@/store/languages'
import MintButton from '@/components/MintButtons/MintButton.vue'

const languages = useLanguagesStore()

function openInNew(href: string) {
    window.open(href, '_blank')
}
</script>

<style scoped lang="scss">
.setup-wizard-complete-container {
    display: flex;
    flex-direction: column;
    gap: 24px;

    .setup-wizard-complete-title {
        font-weight: 600;
        text-align: center;
        font-size: 24px;
        letter-spacing: 0.18px;
    }

    .setup-wizard-complete-subtitle {
        text-align: center;
        font-size: 12px;
        letter-spacing: 0.4px;
        color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
    }

    .setup-wizard-complete-link {
        display: flex;
        align-items: center;
        gap: 16px;
        color: rgb(var(--v-theme-secondary));
        font-size: 16px;
        box-shadow: 0px 1px 8px #00000014;
        border-radius: 4px;
        padding: 16px;
        cursor: pointer;
    }
}
</style>
