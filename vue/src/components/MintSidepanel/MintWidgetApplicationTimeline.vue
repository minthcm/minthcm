<template>
    <MintWidgetFrame
        :is-loading="isLoading"
        :has-error="hasError"
        icon="mdi-timeline-clock-outline"
        :title="languages.label('LBL_WIDGET_APPLICATION_TIMELINE_TITLE', 'Candidatures')"
        :error-label="languages.label('LBL_ERROR_LOADING_DATA', 'Candidatures')"
        :refresh-label="languages.label('LBL_REFRESH', 'Candidatures')"
        @refresh="fetchData(store.bean.id)"
    >
        <div class="mint-widget-application-timeline">
            <div v-if="history.length === 0" class="mint-widget-application-timeline__empty">
                {{ languages.label('LBL_WIDGET_APPLICATION_TIMELINE_EMPTY', 'Candidatures') }}
            </div>

            <div v-else class="mint-widget-application-timeline__list">
                <div v-for="(item, index) in history" :key="item.id" class="mint-widget-application-timeline__item">
                    <div class="mint-widget-application-timeline__node">
                        <div
                            class="mint-widget-application-timeline__node-dot"
                            :class="statusDotClass(item.status)"
                        />
                        <div
                            v-if="index < history.length - 1"
                            class="mint-widget-application-timeline__node-line"
                        />
                    </div>
                    <div class="mint-widget-application-timeline__details">
                        <span class="mint-widget-application-timeline__name">{{ item.name }}</span>
                        <span
                            class="mint-widget-application-timeline__status"
                            :class="statusTextClass(item.status)"
                            >{{ item.status }}</span
                        >
                        <span class="mint-widget-application-timeline__date">{{
                            formatDate(item.dateEntered)
                        }}</span>
                    </div>
                </div>
            </div>
        </div>
    </MintWidgetFrame>
</template>

<script setup lang="ts">
import { watch } from 'vue'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'
import { useLanguagesStore } from '@/store/languages'
import { usePreferencesStore } from '@/store/preferences'
import { mintApi } from '@/api/api'
import { DateTime } from 'luxon'
import { useWidgetFetch } from './useWidgetFetch'
import MintWidgetFrame from './MintWidgetFrame.vue'

interface ApplicationHistoryItem {
    id: string
    name: string
    status: string
    dateEntered: string
}

const store = useRecordViewStore()
const languages = useLanguagesStore()
const preferences = usePreferencesStore()

function formatDate(raw: string): string {
    const dt = DateTime.fromISO(raw, { zone: 'utc' }).setZone('local')
    if (!dt.isValid) return raw
    return dt.toFormat(preferences.userDateFormat)
}

function statusDotClass(status: string): string {
    if (status === 'Hired') return 'mint-widget-application-timeline__node-dot--success'
    if (['Rejected', 'CandidateResignation'].includes(status))
        return 'mint-widget-application-timeline__node-dot--error'
    return 'mint-widget-application-timeline__node-dot--neutral'
}

function statusTextClass(status: string): string {
    if (status === 'Hired') return 'mint-widget-application-timeline__status--success'
    if (['Rejected', 'CandidateResignation'].includes(status)) return 'mint-widget-application-timeline__status--error'
    return ''
}

const {
    data: history,
    isLoading,
    hasError,
    fetchData,
} = useWidgetFetch(
    async (id: string) => {
        const response = await mintApi.get(`Candidatures/${id}/sidepanel/history`, { rawError: true })
        return (response.data ?? []) as ApplicationHistoryItem[]
    },
    [] as ApplicationHistoryItem[],
    (id: string) => `mint-app-timeline-${id}`,
)

watch(
    () => store.bean.id,
    (id) => {
        if (id) fetchData(id)
    },
    { immediate: true },
)
</script>

<style scoped lang="scss">
.mint-widget-application-timeline {
    &__empty {
        font-size: 12px;
        color: rgba(var(--v-theme-on-surface), 0.45);
        text-align: center;
        padding: 8px 0;
    }

    &__list {
        display: flex;
        flex-direction: column;
    }

    &__item {
        display: flex;
        gap: 10px;
    }

    &__node {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex-shrink: 0;
    }

    &__node-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
        margin-top: 3px;

        &--success {
            background: rgb(var(--v-theme-success, 76, 175, 80));
        }

        &--error {
            background: rgb(var(--v-theme-error));
        }

        &--neutral {
            background: rgba(var(--v-theme-primary), 0.5);
        }
    }

    &__node-line {
        flex: 1;
        width: 2px;
        background: rgba(var(--v-theme-on-surface), 0.12);
        margin: 3px 0;
        min-height: 16px;
    }

    &__details {
        display: flex;
        flex-direction: column;
        gap: 1px;
        padding-bottom: 14px;
        min-width: 0;
    }

    &__name {
        font-size: 12px;
        font-weight: 600;
        color: rgb(var(--v-theme-on-surface));
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    &__status {
        font-size: 11px;
        color: rgba(var(--v-theme-on-surface), 0.55);

        &--success {
            color: rgb(var(--v-theme-success, 76, 175, 80));
            font-weight: 600;
        }

        &--error {
            color: rgb(var(--v-theme-error));
            font-weight: 600;
        }
    }

    &__date {
        font-size: 11px;
        color: rgba(var(--v-theme-on-surface), 0.4);
    }
}
</style>
