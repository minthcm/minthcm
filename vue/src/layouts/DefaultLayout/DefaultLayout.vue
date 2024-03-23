<template>
    <DefaultLayoutTopbar />
    <DefaultLayoutSidebar />
    <slot></slot>
</template>

<script setup lang="ts">
import DefaultLayoutTopbar from './DefaultLayoutTopbar.vue'
import DefaultLayoutSidebar from './DefaultLayoutSidebar.vue'
</script>

<style scoped lang="scss"></style>
