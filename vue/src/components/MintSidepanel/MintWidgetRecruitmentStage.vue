<template>
    <div class="mint-widget-recruitment-stage">
        <div v-if="!store.bean.id || store.bean.isRetrieving" class="mint-widget-recruitment-stage__loading">
            <v-progress-circular indeterminate color="primary" size="32" width="3" />
        </div>

        <template v-else>
            <div class="mint-widget-recruitment-stage__title">
                <v-icon icon="mdi-account-search-outline" size="18" />
                <span>{{ languages.label('LBL_WIDGET_RECRUITMENT_STAGE_TITLE', 'Candidatures') }}</span>
            </div>

            <div class="mint-widget-recruitment-stage__content">
                <div
                    v-for="(stage, index) in stages"
                    :key="stage.key"
                    class="mint-widget-recruitment-stage__step"
                    :class="{
                        'mint-widget-recruitment-stage__step--done': isStepDone(index),
                        'mint-widget-recruitment-stage__step--active': isStepActive(index),
                        'mint-widget-recruitment-stage__step--rejected': isRejected && index === stages.length - 1,
                    }"
                >
                    <div class="mint-widget-recruitment-stage__indicator">
                        <v-icon v-if="isStepDone(index) && !(isRejected && index === stages.length - 1)" icon="mdi-check" size="12" />
                        <v-icon v-else-if="isRejected && index === stages.length - 1" icon="mdi-close" size="12" />
                        <span v-else class="mint-widget-recruitment-stage__dot" />
                    </div>
                    <div v-if="index < stages.length - 1" class="mint-widget-recruitment-stage__line" :class="{ 'mint-widget-recruitment-stage__line--done': isStepDone(index) }" />
                    <span class="mint-widget-recruitment-stage__label">{{ languages.label(stage.label, 'Candidatures') }}</span>
                </div>
            </div>
        </template>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'
import { useLanguagesStore } from '@/store/languages'
import { RECRUITMENT_STAGES } from './recruitmentStages'

const store = useRecordViewStore()
const languages = useLanguagesStore()

const stages = RECRUITMENT_STAGES

const currentStatus = computed<string>(() => store.bean.syncAttributes?.status ?? '')

const isRejected = computed(() =>
    ['Rejected', 'CandidateResignation'].includes(currentStatus.value),
)

const currentStageIndex = computed(() => {
    const idx = stages.findIndex(s => s.statuses.includes(currentStatus.value))
    return idx === -1 ? 0 : idx
})

function isStepDone(index: number): boolean {
    return index < currentStageIndex.value || (index === currentStageIndex.value && currentStageIndex.value === stages.length - 1)
}

function isStepActive(index: number): boolean {
    return index === currentStageIndex.value && index < stages.length - 1
}
</script>

<style scoped lang="scss">
.mint-widget-recruitment-stage {
    border-radius: 16px;
    background: rgb(var(--v-theme-surface));
    box-shadow: 0px 1px 12px #00997619;
    padding: 16px;

    &__title {
        display: flex;
        align-items: center;
        gap: 8px;
        color: rgb(var(--v-theme-secondary));
        font-size: 15px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.47px;
        margin-bottom: 16px;
    }

    &__loading {
        display: flex;
        justify-content: center;
        padding: 16px 0 12px;
    }

    &__content {
        display: flex;
        flex-direction: column;
        animation: mint-widget-fadein 0.18s ease-out;
    }

    &__step {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        position: relative;
    }

    &__indicator {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(var(--v-theme-on-surface), 0.1);
        color: rgba(var(--v-theme-on-surface), 0.35);
        border: 2px solid transparent;
        transition: background 0.2s, border-color 0.2s;
        position: relative;
        z-index: 1;
    }

    &__dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    &__line {
        position: absolute;
        left: 10px;
        top: 22px;
        width: 2px;
        height: calc(100% + 6px);
        background: rgba(var(--v-theme-on-surface), 0.12);
        z-index: 0;

        &--done {
            background: rgb(var(--v-theme-primary));
        }
    }

    &__label {
        font-size: 13px;
        color: rgba(var(--v-theme-on-surface), 0.5);
        padding: 3px 0 18px;
        transition: color 0.2s, font-weight 0.2s;
    }

    &__step--done &__indicator {
        background: rgb(var(--v-theme-primary));
        color: rgb(var(--v-theme-on-primary));
        border-color: rgb(var(--v-theme-primary));
    }

    &__step--done &__label {
        color: rgba(var(--v-theme-on-surface), 0.6);
    }

    &__step--active &__indicator {
        background: transparent;
        border-color: rgb(var(--v-theme-primary));
        color: rgb(var(--v-theme-primary));
    }

    &__step--active &__label {
        color: rgb(var(--v-theme-on-surface));
        font-weight: 600;
    }

    &__step--rejected &__indicator {
        background: rgb(var(--v-theme-error));
        border-color: rgb(var(--v-theme-error));
        color: rgb(var(--v-theme-on-error));
    }

    &__step--rejected &__label {
        color: rgb(var(--v-theme-error));
        font-weight: 600;
    }

    &__step:last-child &__label {
        padding-bottom: 0;
    }
}

</style>
