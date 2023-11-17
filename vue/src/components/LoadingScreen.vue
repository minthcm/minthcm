<template>
    <div class="loading-screen">
        <v-progress-circular indeterminate color="primary" size="128" width="8" />
    </div>
</template>

<script setup lang="ts">
</script>

<style scoped lang="scss">
.loading-screen {
    z-index: 10000;
    position: fixed;
    width: 100vw;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-image: url('../bg.jpg');
    background-size: cover;
}
</style>
