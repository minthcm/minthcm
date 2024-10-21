<template>
    <DefaultLayoutTopbar />
    <DefaultLayoutSidebar />
    <DefaultLayoutDrawer />
    <slot></slot>
</template>

<script setup lang="ts">
import DefaultLayoutTopbar from './DefaultLayoutTopbar.vue'
import DefaultLayoutSidebar from './DefaultLayoutSidebar.vue'
import DefaultLayoutDrawer from './DefaultLayoutDrawer.vue'
</script>

<style scoped lang="scss"></style>
