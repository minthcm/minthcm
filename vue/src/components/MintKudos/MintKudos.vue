<template>
    <div v-if="store.activeView === 'kudos-list'">
        <div>
            <MintKudosNav />
            <v-divider />
        </div>
        <v-window v-model="store.activeTab">
            <v-window-item value="all">
                <MintKudosList />
            </v-window-item>
            <v-window-item value="received">
                <MintKudosList />
            </v-window-item>
            <v-window-item value="given">
                <MintKudosList />
            </v-window-item>
        </v-window>
    </div>
    <MintKudosUserList v-else-if="store.activeView === 'user-list'" />
    <MintKudosForm v-else-if="store.activeView === 'form'" />
    <MintKudosSuccessMessage v-else-if="store.activeView === 'success'" />
</template>

<script setup lang="ts">
import MintKudosList from '@/components/MintKudos/MintKudosList.vue'
import MintKudosUserList from '@/components/MintKudos/MintKudosUserList.vue'
import MintKudosForm from '@/components/MintKudos/MintKudosForm.vue'
import MintKudosNav from '@/components/MintKudos/MintKudosNav.vue'
import MintKudosSuccessMessage from '@/components/MintKudos/MintKudosSuccessMessage.vue'
import { useMintKudosStore } from './MintKudosStore'
import { onMounted } from 'vue'

const store = useMintKudosStore()

onMounted(() => {
    store.fetchInitialData()
})
</script>

<style scoped lang="scss"></style>
