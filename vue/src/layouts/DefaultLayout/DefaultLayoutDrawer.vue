<template>
    <div class="drawer">
        <div class="drawer-nav">
            <MintButton icon="mdi-thumb-up" variant="nav" />
            <v-badge
                :content="chat.unreadConversationsCount"
                color="error"
                location="bottom end"
                :model-value="chat.unreadConversationsCount > 0"
                @click="ux.drawer = !ux.drawer"
            >
                <MintButton icon="mdi-chat" variant="nav" :active="ux.drawer" />
            </v-badge>
            <MintButton icon="mdi-newspaper-variant" variant="nav" />
        </div>
        <v-slide-x-transition hide-on-leave>
            <div v-if="ux.drawer" class="drawer-content">
                <MintChat />
            </div>
        </v-slide-x-transition>
    </div>
</template>

<script setup lang="ts">
import MintButton from '@/components/MintButtons/MintButton.vue'
import MintChat from '@/components/MintChat/MintChat.vue'
import { useMintChatStore } from '@/components/MintChat/MintChatStore'
import { useUxStore } from '@/store/ux'

const ux = useUxStore()
const chat = useMintChatStore()
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
