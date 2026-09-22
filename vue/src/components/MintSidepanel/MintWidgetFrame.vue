<template>
    <div class="mint-widget-frame">
        <div v-if="isLoading" class="mint-widget-frame__loading">
            <v-progress-circular indeterminate color="primary" size="32" width="3" />
        </div>

        <div v-else-if="hasError" class="mint-widget-frame__error">
            <v-icon icon="mdi-alert-circle-outline" size="18" color="error" />
            <span>{{ errorLabel }}</span>
            <button class="mint-widget-frame__refresh-btn" @click="$emit('refresh')" :title="refreshLabel">
                <v-icon icon="mdi-refresh" size="16" />
            </button>
        </div>

        <template v-else>
            <div class="mint-widget-frame__title">
                <v-icon :icon="icon" size="18" />
                <span>{{ title }}</span>
            </div>

            <div class="mint-widget-frame__content">
                <slot />
            </div>
        </template>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    isLoading: boolean
    hasError: boolean
    icon: string
    title: string
    errorLabel: string
    refreshLabel: string
}>()

defineEmits<{ (e: 'refresh'): void }>()
</script>

<style scoped lang="scss">
.mint-widget-frame {
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

    &__content {
        animation: mint-widget-fadein 0.18s ease-out;
    }
}
</style>
