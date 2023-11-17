<template>
    <LoadingScreen v-if="store.isInitialLoading" />
    <div v-else class="install-view">
        <div class="install-view-title">
            <h1>MintHCM Setup Wizard</h1>
            <span>Version {{ store.version }}</span>
        </div>
        <div class="install-view-container">
            <InstallViewCompleted v-if="store.isInstallationCompleted" />
            <template v-else>
                <v-fade-transition>
                    <div v-if="store.isLoading" class="install-view-loader">
                        <v-progress-circular indeterminate color="primary" size="64" />
                    </div>
                </v-fade-transition>
                <div class="install-view-container-title">{{ store.currentStep.title }}</div>
                <MintStatusBox v-if="store.errorMsg" type="error">
                    {{ store.errorMsg }}
                </MintStatusBox>
                <v-slide-x-transition hide-on-leave>
                    <component :is="store.currentStep?.component" ref="stepComponent" />
                </v-slide-x-transition>
                <div class="install-view-buttons">
                    <MintButton
                        v-if="store.currentStep.prevBtn !== false && store.currentStepNumber !== 0"
                        variant="text"
                        :text="'Back'"
                        @click="store.prevStep"
                    />
                    <MintButton
                        v-if="store.currentStep.nextBtn !== false"
                        variant="primary"
                        :text="store.currentStep.nextBtn?.label ?? 'Next'"
                        @click="handleNextBtnClick"
                    />
                </div>
                <div class="install-view-footer">
                    <div
                        v-for="n in INSTALL_CONFIG.steps.length"
                        :key="n"
                        :class="{
                            'install-view-footer-dot': true,
                            'install-view-footer-dot-active': store.currentStepNumber === n - 1,
                        }"
                    />
                </div>
            </template>
        </div>
        <div class="install-view-footer"><a href="https://minthcm.org" target="_blank"> www.minthcm.org </a></div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useInstallViewStore } from './InstallViewStore'
import { INSTALL_CONFIG } from './InstallViewConfig'
import MintButton from '@/components/MintButtons/MintButton.vue'
import MintStatusBox from '@/components/MintStatusBox.vue'
import InstallViewCompleted from './steps/InstallViewCompleted.vue'
import LoadingScreen from '@/components/LoadingScreen.vue'

const store = useInstallViewStore()

const stepComponent = ref(null)

onMounted(() => {
    store.fetchInitialData()
})

function handleNextBtnClick() {
    if (typeof stepComponent.value?.validate === 'function' && !stepComponent.value.validate()) {
        return
    }
    if (store.currentStep.nextBtn && typeof store.currentStep.nextBtn?.action === 'function') {
        store.currentStep.nextBtn.action()
    } else {
        store.nextStep()
    }
}
</script>

<style scoped lang="scss">
.install-view {
    // position: fixed;
    width: 100vw;
    min-height: 100vh;
    top: 0px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    gap: 32px;
    padding: 40px 0px 16px 0px;
    overflow: auto;
    background-image: url('../bg.jpg');
    background-size: cover;

    .install-view-title {
        display: flex;
        justify-content: center;
        flex-direction: column;
        align-items: center;

        h1 {
            font-size: 24px;
        }
        span {
            font-size: 12px;
            opacity: 0.6;
        }
    }

    .install-view-footer a {
        color: rgb(var(--v-theme-secondary));
        font-size: 12px;
        text-decoration: none;
    }
}

.install-view-container {
    position: relative;
    width: 456px;
    padding: 64px;
    background: rgb(var(--v-theme-surface));
    box-shadow: 0px 1px 12px #00997619;
    border-radius: 16px;
    display: flex;
    flex-direction: column;
    gap: 32px;
    // max-height: 80vh;
    overflow: hidden;

    .install-view-loader {
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

    .install-view-container-title {
        text-align: center;
        font-weight: 600;
        font-size: 24px;
        letter-spacing: 0.18px;
    }

    .install-view-buttons {
        width: 100%;
        display: flex;
        gap: 16px;
        justify-content: flex-end;
    }

    .install-view-footer {
        display: flex;
        gap: 16px;
        margin: 16px auto 0px auto;

        .install-view-footer-dot {
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
