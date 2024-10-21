<template>
    <v-skeleton-loader type="card" class="kudos-skeleton"></v-skeleton-loader>
    <v-skeleton-loader type="card" class="kudos-skeleton"></v-skeleton-loader>
    <v-skeleton-loader type="card" class="kudos-skeleton"></v-skeleton-loader>
</template>

<script setup lang="ts"></script>

<style scoped lang="scss">
.kudos-skeleton {
    width: 400px;
    margin: 0 auto;
}
.kudos-skeleton:first-child {
    margin-top: 16px;
}
</style>
