<template>
    <router-view v-if="$route.meta?.entryPoint" :key="$route.fullPath" />
    <v-app v-else>
        <MintOverlay />
        <MintPopups />
        <v-fade-transition>
            <LoadingScreen v-if="backend.initialLoading" />
        </v-fade-transition>
        <component :is="ux.layout">
            <v-main
                class="mint-content"
                :style="{
                    marginRight: ux.drawer && $vuetify.display.xlAndUp ? 'var(--v-drawer-width)' : '0px',
                }"
            >
                <router-view :key="$route.fullPath" />
            </v-main>
        </component>
    </v-app>
</template>

<script setup lang="ts">
import { useBackendStore } from '@/store/backend'
import { useUxStore } from '@/store/ux'
import MintPopups from '@/components/MintPopups/MintPopups.vue'
import LoadingScreen from '@/components/LoadingScreen.vue'
import MintOverlay from './components/MintOverlay.vue'
import '/node_modules/flag-icons/css/flag-icons.min.css'

const backend = useBackendStore()
const ux = useUxStore()
</script>

<style scoped lang="scss">
.mint-content {
    margin-top: var(--v-top-nav-height);
    margin-right: var(--v-drawer-width);
}
</style>
