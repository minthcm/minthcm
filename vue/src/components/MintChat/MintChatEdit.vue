<template>
    <MintButton variant="nav" icon="mdi-arrow-left" @click="chat.view = 'list'" />
    <h1>Mint Chat Edit</h1>
</template>

<script setup lang="ts">
import { useMintChatStore } from './MintChatStore'
import MintButton from '@/components/MintButtons/MintButton.vue'


const chat = useMintChatStore()
</script>

<style scoped lang="scss">

</style>
