<template>
    <button
        :class="[
            'mint-button',
            `mint-button-${props.variant}`,
            props.size && `mint-button-${props.size}`,
            isIcon && 'mint-button-icon',
            props.disabled && 'disabled',
            props.active && 'active',
        ]"
        v-ripple="!disabled"
        :disabled="disabled"
    >
        <v-progress-circular v-if="loading" :size="props.size" indeterminate />
        <v-icon v-if="props.icon" :icon="props.icon" :size="props.size" />
        <div v-if="props.text" v-text="props.text" class="mx-auto" />
        <v-icon v-if="props.appendIcon" :icon="props.appendIcon" :size="props.size" />
        <v-tooltip v-if="props.tooltip?.trim()" activator="parent" location="top">{{ props.tooltip }}</v-tooltip>
    </button>
</template>

<script setup lang="ts">
import { computed } from 'vue'

interface Props {
    icon?: string
    appendIcon?: string
    text?: string
    tooltip?: string
    variant?: 'text' | 'text-danger' | 'regular' | 'primary' | 'nav'
    size?: '24' | 'small' | 'medium' | 'large'
    disabled?: boolean
    active?: boolean
    loading?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    variant: 'regular',
    size: '24',
    disabled: false,
    active: false,
})

const isIcon = computed(() => (props.icon || props.appendIcon) && !props.text)
</script>

<style scoped lang="scss">
.mint-button {
    position: relative;
    border-radius: 50px;
    font-weight: 600;
    font-size: 15px;
    letter-spacing: 0.47px;
    transition: all 150ms ease-in-out;
    cursor: pointer;
    padding: 5px 14px 5px 14px;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;

    &:focus-visible {
        outline: 2px solid rgb(var(--v-theme-secondary));
        outline-offset: 2px;
    }
}

.mint-button-text {
    color: rgb(var(--v-theme-secondary));
    background: transparent;
    &:hover {
        color: rgb(var(--v-theme-secondary-dark));
        background: rgb(var(--v-theme-primary-light));
    }
    &.disabled {
        cursor: default;
        color: var(--mint-text-disabled);
        background: transparent;
    }
}
.mint-button-text-danger {
    color: var(--mint-danger);
    background: transparent;
    &:hover {
        background: rgb(var(--v-theme-primary-light));
    }
    &.disabled {
        cursor: default;
        color: var(--mint-text-disabled);
        background: transparent;
    }
}

.mint-button-regular {
    color: rgb(var(--v-theme-secondary));
    background: rgb(var(--v-theme-primary-light));
    &:hover {
        background: var(--mint-button-regular-hover);
    }
    &.disabled {
        cursor: default;
        color: var(--mint-text-disabled);
        background: var(--mint-bg-disabled);
    }
    &.active {
        color: rgb(var(--v-theme-primary-lighter));
        background: rgb(var(--v-theme-secondary));
    }
    &:focus-visible {
        outline: 2px solid rgb(var(--v-theme-secondary));
        outline-offset: 2px;
    }
}

.mint-button-primary {
    color: rgb(var(--v-theme-primary-lighter)) !important;
    background: rgb(var(--v-theme-secondary));
    &:hover {
        background: rgb(var(--v-theme-secondary-dark));
    }
    &.disabled {
        cursor: default;
        color: var(--mint-bg-disabled);
        background: var(--mint-text-disabled);
    }
}

.mint-button-nav {
    color: rgb(var(--v-theme-secondary));
    background: rgb(var(--v-theme-primary-lighter));
    &:hover {
        color: rgb(var(--v-theme-secondary-dark));
        background: rgb(var(--v-theme-primary-light));
    }
    &.disabled {
        cursor: default;
        color: var(--mint-text-disabled);
        background: var(--mint-bg-disabled);
    }
    &.active {
        color: rgb(var(--v-theme-primary-lighter));
        background: rgb(var(--v-theme-secondary));
    }
}

.mint-button-icon {
    padding: 8px;
    border-radius: 50%;
    color: rgb(var(--v-theme-secondary));
    
    &:hover {
        color: rgb(var(--v-theme-secondary-dark));
    }
}

.mint-button-small {
    &.mint-button-icon {
        padding: 3px;
    }
    &.mint-button-text,
    &.mint-button-primary,
    &.mint-button-regular {
        padding: 3px 9px 3px 9px;
        font-size: 12px;
    }
}
</style>
