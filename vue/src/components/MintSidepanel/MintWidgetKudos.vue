<template>
    <div class="mint-widget-kudos">
        <div v-if="isLoading" class="mint-widget-kudos__loading">
            <v-progress-circular indeterminate color="primary" size="32" width="3" />
        </div>

        <div v-else-if="hasError" class="mint-widget-kudos__error">
            <v-icon icon="mdi-alert-circle-outline" size="18" color="error" />
            <span>{{ languages.label('LBL_ERROR_LOADING_DATA', 'Employees') }}</span>
            <button class="mint-widget-kudos__refresh-btn" @click="fetchData(store.bean.id)" :title="languages.label('LBL_REFRESH', 'Employees')">
                <v-icon icon="mdi-refresh" size="16" />
            </button>
        </div>

        <template v-else>
            <div class="mint-widget-kudos__title">
                <v-icon icon="mdi-trophy-award" size="18" />
                <span>{{ languages.label('LBL_WIDGET_KUDOS_TITLE', 'Employees') }}</span>
            </div>

            <div class="mint-widget-kudos__content">
                <div class="mint-widget-kudos__counter">
                    <span class="mint-widget-kudos__value">{{ kudos.thisYear }}</span>
                    <span class="mint-widget-kudos__label">{{ languages.label('LBL_WIDGET_KUDOS_THIS_YEAR', 'Employees') }}</span>
                </div>

                <div class="mint-widget-kudos__icons">
                    <v-icon
                        v-for="i in filledStars"
                        :key="'f' + i"
                        icon="mdi-star-circle"
                        size="20"
                        class="mint-widget-kudos__star mint-widget-kudos__star--filled"
                    />
                    <v-icon
                        v-for="i in emptyStars"
                        :key="'e' + i"
                        icon="mdi-star-circle-outline"
                        size="20"
                        class="mint-widget-kudos__star mint-widget-kudos__star--empty"
                    />
                    <span v-if="overflow > 0" class="mint-widget-kudos__overflow">+{{ overflow }}</span>
                </div>

                <div v-if="kudos.lastGiver" class="mint-widget-kudos__last-giver">
                    <v-icon icon="mdi-account-heart-outline" size="14" />
                    <span>{{ kudos.lastGiver }}</span>
                </div>

                <div class="mint-widget-kudos__year">{{ currentYear }}</div>
            </div>
        </template>
    </div>
</template>

<script setup lang="ts">
import { computed, watch } from 'vue'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'
import { useLanguagesStore } from '@/store/languages'
import { mintApi } from '@/api/api'
import { useWidgetFetch } from './useWidgetFetch'

const MAX_STARS = 10

const store = useRecordViewStore()
const languages = useLanguagesStore()
const currentYear = new Date().getFullYear()

const { data: kudos, isLoading, hasError, fetchData } = useWidgetFetch(
    async (id: string) => {
        const response = await mintApi.get(`Employees/${id}/sidepanel/kudos`, { rawError: true })
        return {
            total: response.data?.total ?? 0,
            thisYear: response.data?.thisYear ?? 0,
            lastGiver: response.data?.lastGiver ?? '',
        }
    },
    { total: 0, thisYear: 0, lastGiver: '' },
    (id: string) => `mint-kudos-${id}-${new Date().getFullYear()}`,
)

const filledStars = computed(() => Math.min(kudos.value.thisYear, MAX_STARS))
const emptyStars = computed(() => Math.max(0, MAX_STARS - filledStars.value))
const overflow = computed(() => Math.max(0, kudos.value.thisYear - MAX_STARS))

watch(() => store.bean.id, (id) => { if (id) fetchData(id) }, { immediate: true })
</script>

<style scoped lang="scss">
.mint-widget-kudos {
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
        align-items: center;
        gap: 12px;
        animation: mint-widget-fadein 0.18s ease-out;
    }

    &__counter {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
    }

    &__value {
        font-size: 48px;
        font-weight: 700;
        color: rgb(var(--v-theme-primary));
        line-height: 1;
    }

    &__label {
        font-size: 12px;
        color: rgb(var(--v-theme-secondary));
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    &__icons {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 4px;
        align-items: center;
    }

    &__star {
        &--filled {
            color: rgb(var(--v-theme-primary));
        }

        &--empty {
            color: rgba(var(--v-theme-on-surface), 0.18);
        }
    }

    &__overflow {
        font-size: 12px;
        font-weight: 700;
        color: rgb(var(--v-theme-primary));
    }

    &__last-giver {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        color: rgba(var(--v-theme-on-surface), 0.55);
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

    &__year {
        font-size: 12px;
        color: rgba(var(--v-theme-on-surface), 0.45);
    }
}

</style>
