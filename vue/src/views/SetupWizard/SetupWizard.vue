<template>
    <div class="setup-wizard">
        <div class="setup-wizard-container">
            <v-fade-transition>
                <div v-if="store.isLoading" class="setup-wizard-loader">
                    <v-progress-circular indeterminate color="primary" size="64" />
                </div>
            </v-fade-transition>
            <SetupWizardComplete v-if="store.isFinished" />
            <template v-else>
                <div class="setup-wizard-title">{{ languages.label(store.currentStep.title) }}</div>
                <v-slide-x-transition hide-on-leave>
                    <component :is="store.currentStep?.component" ref="setupWizardStepComponent" />
                </v-slide-x-transition>
                <div class="setup-wizard-buttons">
                    <MintButton
                        variant="text"
                        :text="languages.label(store.currentStep?.backLabel ?? 'LBL_MINT4_BACK')"
                        @click="store.prevStep"
                    />
                    <MintButton variant="primary" :text="languages.label(nextBtnLabel)" @click="handleNextStep" />
                </div>
                <div class="setup-wizard-footer">
                    <div
                        v-for="n in store.steps.length"
                        :key="n"
                        :class="{
                            'setup-wizard-footer-dot': true,
                            'setup-wizard-footer-dot-active': store.currentStepNumber === n - 1,
                        }"
                    />
                </div>
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import MintButton from '@/components/MintButtons/MintButton.vue'
import { useLanguagesStore } from '@/store/languages'
import { useSetupWizardStore } from './SetupWizardStore'
import SetupWizardComplete from './SetupWizardComplete.vue'

const store = useSetupWizardStore()
const languages = useLanguagesStore()
const setupWizardStepComponent = ref<any>()

function handleNextStep() {
    if (!setupWizardStepComponent.value?.validate || setupWizardStepComponent.value.validate()) {
        store.nextStep()
    }
}

const nextBtnLabel = computed(() => {
    if (store.currentStep.nextLabel) {
        return store.currentStep.nextLabel
    }
    return store.currentStepNumber === store.steps.length - 1 ? 'LBL_MINT4_FINISH' : 'LBL_MINT4_NEXT'
})
</script>

<style lang="scss">
.setup-wizard {
    position: fixed;
    width: 100vw;
    height: 100vh;
    top: 0px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: auto;
}

.setup-wizard-container {
    position: relative;
    width: 456px;
    padding: 64px;
    background: rgb(var(--v-theme-surface));
    box-shadow: 0px 1px 12px #00997619;
    border-radius: 16px;
    display: flex;
    flex-direction: column;
    gap: 24px;

    .setup-wizard-loader {
        position: absolute;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        border-radius: 16px;
        background: rgba(var(--v-theme-on-surface), var(--v-idle-opacity));
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .setup-wizard-title {
        text-align: center;
        font-weight: 600;
        font-size: 24px;
        letter-spacing: 0.18px;
    }

    .setup-wizard-buttons {
        width: 100%;
        display: flex;
        gap: 16px;
        justify-content: flex-end;
    }

    .setup-wizard-footer {
        display: flex;
        gap: 16px;
        margin: 24px auto 0px auto;

        .setup-wizard-footer-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(var(--v-theme-on-surface), var(--v-border-opacity));

            &-active {
                background: rgba(var(--v-theme-on-surface), var(--v-disabled-opacity));
            }
        }
    }
}
</style>
