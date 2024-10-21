<template>
    <div class="drawer">
        <div class="drawer-nav">
            <template v-for="drawer in bundle.drawers" :key="drawer.key">
                <v-badge v-if="drawer.isAvaliable?.()"
                    :content="drawer.badge?.()"
                    color="error"
                    location="bottom end"
                    :model-value="!!drawer.badge?.()"
                    @click="ux.drawer = ux.drawer === drawer.key ? null : drawer.key"
                >
                    <MintButton :icon="drawer.icon" variant="nav" :active="ux.drawer === drawer.key" />
                </v-badge>
            </template>
        </div>
        <v-slide-x-transition>
            <div v-if="ux.drawer" class="drawer-content" ref="drawerContentRef" @scroll="handleScroll">
                <template v-for="drawer in bundle.drawers" :key="drawer.key">
                    <component v-if="ux.drawer === drawer.key" :is="drawer.component" />
                </template>
            </div>
        </v-slide-x-transition>
    </div>
</template>

<script setup lang="ts">
import MintButton from '@/components/MintButtons/MintButton.vue'
import { useUxStore } from '@/store/ux'
import { computed, ref } from 'vue'
import bundle from '@/bundler'

const ux = useUxStore()
const drawerContentRef = ref<any>(null)

const activeDrawer = computed(() => bundle.drawers.find((drawer: any) => drawer.key === ux.drawer))

function handleScroll() {
    activeDrawer.value?.onScroll(drawerContentRef.value)
}
</script>

<style scoped lang="scss">
.drawer {
    position: fixed;
    z-index: 1000;
    top: var(--v-top-nav-height);
    right: 0px;
    height: calc(100vh - var(--v-top-nav-height));
    box-shadow: 0px 1px 32px #0099761a;

    .drawer-content {
        width: var(--v-drawer-width);
        background: rgb(var(--v-theme-surface));
        height: 100%;
        overflow: auto;
    }
}
.drawer-nav {
    padding: 8px;
    display: flex;
    flex-direction: column;
    position: absolute;
    // left: calc(100vw - 400px - 70px);
    top: 50%;
    transform: translate(calc(-100% - 12px), -50%);
    gap: 4px;
    background: rgb(var(--v-theme-surface));
    border-radius: 100px;
    box-shadow: 0px 3px 6px #00000029;
}

.v-badge {
    :deep(.v-badge__badge) {
        outline: 2px solid #fff;
        margin-top: -8px;
        margin-left: -8px;
        font-weight: 600;
    }
}
</style>
