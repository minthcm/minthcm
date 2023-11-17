<template>
    <component :is="chat.currentView" />
</template>

<script setup lang="ts">
import { useMintChatStore } from './MintChatStore'

const chat = useMintChatStore()
</script>

<style scoped lang="scss">
</style>
