<template>
    <div
        style="display: flex; gap: 8px; padding: 8px 16px 8px 8px; align-items: center; border-bottom: thin solid #0002"
    >
        <MintButton variant="nav" icon="mdi-arrow-left" @click="chat.view = 'default'" />
        <MintSearch
            v-model="chat.chatUsersSearchQuery"
            :label="languages.label('LBL_MINT4_CHAT_SEARCH_USER')"
            @clear="chat.chatUsersSearchQuery = ''"
        />
    </div>
    <!-- <div
        class="mint-chat-create-group mint-chat-list-item"
        v-ripple="{ class: 'text-primary' }"
        @click="chat.view = 'edit'"
    >
        <v-icon icon="mdi-account-group" />
        <span v-text="languages.label('LBL_MINT4_CHAT_CREATE_GROUP')" />
    </div> -->
    <div>
        <div
            class="mint-chat-user mint-chat-list-item"
            v-for="user in chat.usersList"
            :key="user.id"
            v-ripple="{ class: 'text-primary' }"
            @click="chat.openPrivateConversation(user)"
        >
            <img v-if="user.photo" :src="user.photo" />
            <v-icon v-else icon="mdi-account" />
            <div v-text="user.name" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { useMintChatStore } from './MintChatStore'
import MintButton from '@/components/MintButtons/MintButton.vue'
import MintSearch from '../MintSearch.vue'
import { useLanguagesStore } from '@/store/languages'

const chat = useMintChatStore()
const languages = useLanguagesStore()
</script>

<style scoped lang="scss">
.mint-chat-create-group {
    border-bottom: thin solid #0002;
}

.mint-chat-list-item {
    display: flex;
    gap: 16px;
    align-items: center;
    padding: 12px 16px;
    cursor: pointer;
    transition: all 150ms ease-in-out;
    color: rgb(var(--v-theme-secondary));
    font-weight: 600;
    font-size: 14px;

    &:hover {
        background: rgb(var(--v-theme-primary-light));
    }
    :first-child {
        object-fit: cover;
        width: 32px;
        height: 32px;
        font-size: 24px;
        border-radius: 50%;
    }
    :last-child {
    }
}

.mint-chat-user {
    position: relative;
    &::after {
        position: absolute;
        content: '';
        display: block;
        bottom: 0px;
        right: 12px;
        left: 12px;
        border-bottom: thin solid #0002;
    }
}
</style>
