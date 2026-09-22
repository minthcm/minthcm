<template>
    <div class="mint-widget-competencies">
        <div v-if="isLoading" class="mint-widget-competencies__loading">
            <v-progress-circular indeterminate color="primary" size="32" width="3" />
        </div>

        <div v-else-if="hasError" class="mint-widget-competencies__error">
            <v-icon icon="mdi-alert-circle-outline" size="18" color="error" />
            <span>{{ languages.label('LBL_ERROR_LOADING_DATA', 'Employees') }}</span>
            <button class="mint-widget-competencies__refresh-btn" @click="fetchData(store.bean.id)" :title="languages.label('LBL_REFRESH', 'Employees')">
                <v-icon icon="mdi-refresh" size="16" />
            </button>
        </div>

        <template v-else>
            <div class="mint-widget-competencies__title">
                <v-icon icon="mdi-star-half-full" size="18" />
                <span>{{ languages.label('LBL_WIDGET_COMPETENCIES_TITLE', 'Employees') }}</span>
            </div>

            <div class="mint-widget-competencies__content">
                <div v-if="competencies.length === 0" class="mint-widget-competencies__empty">
                    {{ languages.label('LBL_WIDGET_COMPETENCIES_EMPTY', 'Employees') }}
                </div>

                <div v-else class="mint-widget-competencies__bars">
                    <div
                        v-for="comp in competencies"
                        :key="comp.name"
                        class="mint-widget-competencies__bar-row"
                    >
                        <span class="mint-widget-competencies__bar-name" :title="comp.name">{{ comp.name }}</span>
                        <div class="mint-widget-competencies__bar-track">
                            <div
                                class="mint-widget-competencies__bar-fill"
                                :style="{ width: barWidth(comp) + '%' }"
                            />
                        </div>
                        <span class="mint-widget-competencies__bar-score">{{ comp.score }}<span class="mint-widget-competencies__bar-max">/{{ comp.max }}</span></span>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script setup lang="ts">
import { watch } from 'vue'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'
import { useLanguagesStore } from '@/store/languages'
import { mintApi } from '@/api/api'
import { useWidgetFetch } from './useWidgetFetch'

interface Competency {
    name: string
    score: number
    max: number
}

const store = useRecordViewStore()
const languages = useLanguagesStore()

function barWidth(comp: Competency): number {
    if (comp.max === 0) return 0
    return Math.min(100, Math.max(0, Math.round((comp.score / comp.max) * 100)))
}

const { data: competencies, isLoading, hasError, fetchData } = useWidgetFetch(
    async (id: string) => {
        const response = await mintApi.get(`Employees/${id}/sidepanel/competencies`, { rawError: true })
        return (response.data ?? []) as Competency[]
    },
    [] as Competency[],
    (id: string) => `mint-competencies-${id}`,
)

watch(() => store.bean.id, (id) => { if (id) fetchData(id) }, { immediate: true })
</script>

<style scoped lang="scss">
.mint-widget-competencies {
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
        animation: mint-widget-fadein 0.18s ease-out;
    }

    &__empty {
        font-size: 12px;
        color: rgba(var(--v-theme-on-surface), 0.45);
        text-align: center;
        padding: 8px 0;
    }

    &__bars {
        display: flex;
        flex-direction: column;
        gap: 10px;
        max-height: 240px;
        overflow-y: auto;
    }

    &__bar-row {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    &__bar-name {
        font-size: 11px;
        color: rgb(var(--v-theme-secondary));
        width: 72px;
        flex-shrink: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    &__bar-track {
        flex: 1;
        height: 6px;
        background: rgba(var(--v-theme-on-surface), 0.1);
        border-radius: 3px;
        overflow: hidden;
    }

    &__bar-fill {
        height: 100%;
        background: rgb(var(--v-theme-primary));
        border-radius: 3px;
        transition: width 0.5s ease;
        min-width: 4px;
    }

    &__bar-score {
        font-size: 12px;
        font-weight: 700;
        color: rgb(var(--v-theme-primary));
        width: 24px;
        text-align: right;
        flex-shrink: 0;
    }

    &__bar-max {
        font-weight: 400;
        color: rgba(var(--v-theme-on-surface), 0.45);
    }

    &__error {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: rgb(var(--v-theme-error));
        padding: 8px 0 12px;
    }

    &__refresh-btn {
        margin-left: auto;
        background: none;
        border: none;
        cursor: pointer;
        color: rgb(var(--v-theme-secondary));
        display: flex;
        align-items: center;
        padding: 2px;
        border-radius: 4px;
        transition: color 0.15s;

        &:hover {
            color: rgb(var(--v-theme-primary));
        }
    }
}

</style>
