<template>
    <div
        :class="{
            drawer: true,
            closed: !ux.drawer,
        }"
    >
        <div 
            :class="{
                'drawer-scrim': $vuetify.display.mdAndDown, 
                'drawer-scrim-open': ux.drawer
            }" 
            @click="ux.drawer = null"
        />
        <div :class="{'drawer-nav': true, 'drawer-nav-railed': $vuetify.display.mdAndDown}">
            <template v-for="drawer in bundle.drawers" :key="drawer.key">
                <v-badge
                    v-if="drawer.isAvaliable?.()"
                    :content="drawer.badge?.()"
                    color="error"
                    location="bottom start"
                    :model-value="!!drawer.badge?.()"
                    @click="ux.drawer = ux.drawer === drawer.key ? null : drawer.key"
                >
                    <MintButton :icon="drawer.icon" variant="nav" :active="ux.drawer === drawer.key" />
                </v-badge>
            </template>
            <MintButton v-if="ux.drawer" @click="ux.drawer = null" icon="mdi-close" variant="nav" />
        </div>
        <v-slide-x-transition hide-on-leave>
            <div 
                v-if="ux.drawer" 
                :class="{
                    'drawer-content': true,
                    'drawer-content-railed': $vuetify.display.mdAndDown
                }"
                ref="drawerContentRef" 
                @scroll="handleScroll"
            >
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
const drawerContentRef = ref<HTMLElement | null>(null)

const activeDrawer = computed(() => bundle.drawers.find((drawer: any) => drawer.key === ux.drawer))

function handleScroll() {
    if (typeof activeDrawer.value?.onScroll === 'function') {
        activeDrawer.value.onScroll(drawerContentRef.value)
    }
}
</script>

<style scoped lang="scss">
.drawer {
    position: fixed;
    z-index: 1000;
    top: var(--v-top-nav-height);
    right: 0px;
    height: calc(100vh - var(--v-top-nav-height));
    box-shadow: 0px 1px 6px #00000029;

    .drawer-content {
        width: var(--v-drawer-width);
        background: rgb(var(--v-theme-surface));
        height: 100%;
        overflow: auto;

        &.drawer-content-railed {
            width: calc(100vw - 76px);
        }
    }

    .drawer-nav {
        padding: 8px 0px 8px 8px;
        display: flex;
        flex-direction: column;
        position: absolute;
        top: 50%;
        transform: translate(-100%, -50%);
        gap: 4px;
        z-index: -1;
        background: rgb(var(--v-theme-surface));
        border-radius: 32px 0px 0px 32px;
        box-shadow: 0px 1px 6px #00000029;

        &::before,
        &::after {
            content: '';
            width: 32px;
            height: 32px;
            position: absolute;
            background: inherit;
            right: 0px;
            mask: radial-gradient(circle at center, transparent 16px, black 0%);
            -webkit-mask: radial-gradient(circle at center, transparent 16px, black 0%);
        }
        &::before {
            top: -32px;
            border-radius: 50% 50% 0px 50%;
        }
        &::after {
            bottom: -32px;
            border-radius: 50% 0px 50% 50%;
        }
    }
}

.drawer.closed {
    .drawer-nav {
        padding-right: 8px;
        transition: transform 0.3s ease;
        transform: translate(calc(-100% + 28px), -50%);
        &:hover {
            transform: translate(-100%, -50%);
        }
    }
}

.v-badge {
    :deep(.v-badge__badge) {
        outline: 2px solid #fff;
        margin-top: -8px;
        font-weight: 600;
    }
}

.drawer-scrim {
    background-color: rgba(0, 0, 0, 0);
    position: fixed;
    top: var(--v-top-nav-height);
    left: 0;
    right: 0;
    height: calc(100vh - var(--v-top-nav-height));
    z-index: -2;
    transition: left 0.2s ease, background-color 0.2s ease;
    pointer-events: none;

    &.drawer-scrim-open {
        right: var(--v-drawer-width);
        width: 76px;
        background-color: rgba(0, 0, 0, 0.3);
        pointer-events: auto;
    }
}
</style>
