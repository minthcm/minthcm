<template>
    <slot></slot>
    <footer>
        <a href="https://minthcm.org" target="_blank"> www.minthcm.org </a>
    </footer>
</template>

<script setup lang="ts"></script>

<style scoped lang="scss">
footer {
    width: 100%;
    text-align: center;
    font-size: 12px;
    gap: 16px;
    align-items: center;
    padding: 8px 32px;
    z-index: 1000;

    a {
        color: rgb(var(--v-theme-secondary));
        text-decoration: none;

        &:hover {
            color: rgb(var(--v-theme-secondary-dark));
        }
    }
}
</style>
