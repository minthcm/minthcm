<template>
    <v-fade-transition>
        <div v-if="isOverlayVisible" class="mint-overlay" @click="popups.closeAll" />
    </v-fade-transition>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { usePopupsStore } from '@/store/popups'

const popups = usePopupsStore()

const isOverlayVisible = computed(() => popups.popups.length)
</script>

<style scoped lang="scss">
.mint-overlay {
    position: fixed;
    top: 0px;
    left: 0px;
    width: 100vw;
    height: 100vh;
    background: #0005;
    z-index: 9999;
    user-select: none;
}
</style>
