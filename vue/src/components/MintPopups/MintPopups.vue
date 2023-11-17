<template>
    <transition-group name="scale-transition">
        <MintPopup v-for="popup in popups" :key="popup" :popup="popup" />
    </transition-group>
</template>

<script setup lang="ts">
import { storeToRefs } from 'pinia'
import { usePopupsStore } from '@/store/popups'
import MintPopup from './MintPopup.vue'

const { popups } = storeToRefs(usePopupsStore())
</script>

<style scoped lang="scss"></style>
