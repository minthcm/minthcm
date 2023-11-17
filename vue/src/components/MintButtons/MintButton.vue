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
import { defineProps, withDefaults, computed } from 'vue'

interface Props {
    icon?: string
    appendIcon?: string
    text?: string
    tooltip?: string
    variant?: 'text' | 'regular' | 'primary' | 'nav'
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
        outline: thin solid #0002;
    }
}

.mint-button-text {
    color: rgb(var(--v-theme-secondary));
    background: transparent;
    &:hover {
        color: rgb(var(--v-theme-secondary-dark));
        background: #e0ece9;
    }
    &.disabled {
        cursor: default;
        color: #8b8b8b;
        background: transparent;
    }
}

.mint-button-regular {
    color: rgb(var(--v-theme-secondary-dark));
    background: #e0ece9;
    &:hover {
        background: #9ec4bc;
    }
    &.disabled {
        cursor: default;
        color: #8b8b8b;
        background: #e0e0e0;
    }
    &.active {
        color: #f5fbfa;
        background: rgb(var(--v-theme-secondary));
    }
}

.mint-button-primary {
    color: #f5fbfa;
    background: rgb(var(--v-theme-secondary));
    &:hover {
        background: rgb(var(--v-theme-secondary-dark));
    }
    &.disabled {
        cursor: default;
        color: #e0e0e0;
        background: #8b8b8b;
    }
}

.mint-button-nav {
    color: rgb(var(--v-theme-secondary));
    background: #f5fbfa;
    &:hover {
        color: rgb(var(--v-theme-secondary-dark));
        background: #e0ece9;
    }
    &.disabled {
        cursor: default;
        color: #8b8b8b;
        background: #e0e0e0;
    }
    &.active {
        color: #f5fbfa;
        background: rgb(var(--v-theme-secondary));
    }
}

.mint-button-icon {
    padding: 8px;
    border-radius: 50%;
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
