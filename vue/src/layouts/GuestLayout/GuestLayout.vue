<template>
    <slot></slot>
    <footer>
        <a href="https://omnihr.org" target="_blank"> www.omnihr.org</a> | + 407-485-5622 
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
    color: rgb(var(--v-theme-secondary));

    a {
        text-decoration: none;
        color: rgb(var(--v-theme-secondary));

        &:hover {
            color: rgb(var(--v-theme-secondary-dark));
        }
    }
}
</style>
