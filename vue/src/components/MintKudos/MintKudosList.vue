<template>
    <ul ref="kudos-list">
        <MintKudosItem v-for="kudosItem in store.kudos" :key="kudosItem.id" :kudos="kudosItem" />
        <MintKudosListLoadingSkeletons v-if="store.isLoading" />
    </ul>
</template>

<script setup lang="ts">
import { useMintKudosStore } from './MintKudosStore'
import MintKudosItem from './MintKudosItem.vue'
import MintKudosListLoadingSkeletons from './MintKudosListLoadingSkeletons.vue'

const store = useMintKudosStore()
</script>

<style scoped lang="scss">
ul {
    list-style-type: none;
}
</style>
