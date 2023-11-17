<template>
    <div
        style="display: flex; gap: 8px; padding: 4px 16px 4px 4px; align-items: center; border-bottom: thin solid #0002"
    >
        <MintSearch
            v-model="chat.conversationsSearchQuery"
            :label="languages.label('LBL_MINT4_CHAT_SEARCH_CONVERSATION')"
            @clear="chat.conversationsSearchQuery = ''"
        />
        <MintButton variant="nav" icon="mdi-square-edit-outline" @click="chat.view = 'list'" />
    </div>
    <div class="mint-chat-conversations">
        <div
            :class="{
                'mint-chat-conversation': true,
                'mint-chat-conversation-unread': (conv.messages?.at(-1)?.user_id !== auth.user?.id) && (!conv.date_read || conv.date_read < (conv.messages?.at(-1)?.date_entered || conv.date_active)),
            }"
            v-for="conv in chat.conversationsList"
            :key="conv.id"
            v-ripple="{ class: 'text-primary' }"
            @click="chat.openDetailView(conv.id)"
        >
            <div class="mint-chat-conversation-avatar">
                <img v-if="conv.users[0]?.photo" :src="conv.users[0].photo" />
                <v-icon v-else :icon="conv.users?.length > 1 ? 'mdi-account-group' : 'mdi-account'" />
            </div>
            <div class="mint-chat-conversation-content">
                <div class="mint-chat-conversation-header">
                    <div class="mint-chat-conversation-name" v-text="conv.name" />
                    <div class="mint-chat-conversation-status">
                        <div v-text="toRelativeDate(conv.messages?.at(-1)?.date_entered || conv.date_active)" />
                        <div
                            v-if="(conv.messages?.at(-1)?.user_id !== auth.user?.id) && (!conv.date_read || conv.date_read < (conv.messages?.at(-1)?.date_entered || conv.date_active))"
                            class="mint-chat-conversation-unread-dot"
                        />
                    </div>
                </div>
                <div
                    class="mint-chat-conversation-message"
                    v-if="conv.messages?.at(-1)"
                    v-text="conv.messages.at(-1)?.text"
                />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { DateTime } from 'luxon'
import { useMintChatStore } from './MintChatStore'
import MintButton from '@/components/MintButtons/MintButton.vue'
import MintSearch from '../MintSearch.vue'
import { useLanguagesStore } from '@/store/languages'
import { useAuthStore } from '@/store/auth'

const chat = useMintChatStore()
const languages = useLanguagesStore()
const auth = useAuthStore()

function toRelativeDate(date: string) {
    const dt = DateTime.fromSQL(date)
    if (dt.diffNow('days').days >= -5) {
        return dt.toRelativeCalendar()
    }
    return dt.toFormat('dd.MM.yyyy')
}
</script>

<style scoped lang="scss">
.mint-chat-conversations {
    display: flex;
    flex-direction: column;

    .mint-chat-conversation {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 12px;
        transition: all 150ms ease-in-out;
        cursor: pointer;
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

        &:hover {
            background: rgb(var(--v-theme-primary-light));
        }

        &-unread {
            background: rgb(var(--v-theme-primary-lighter));
        }

        .mint-chat-conversation-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            min-width: 40px;
            width: 40px;
            height: 40px;

            img {
                object-fit: cover;
                width: 40px;
                height: 40px;
                border-radius: 50%;
            }
        }

        .mint-chat-conversation-content {
            display: flex;
            flex-direction: column;
            width: 100%;
            gap: 4px;
            overflow: hidden;

            .mint-chat-conversation-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 16px;

                .mint-chat-conversation-name {
                    font-weight: 600;
                    color: rgb(var(--v-theme-secondary));
                    font-size: 14px;
                    letter-spacing: 0.43px;
                }

                .mint-chat-conversation-status {
                    display: flex;
                    align-items: center;
                    gap: 16px;
                    font-size: 12px;
                    color: rgb(var(--v-theme-primary));
                    font-weight: 600;
                    letter-spacing: 0.4px;
                    white-space: nowrap;

                    .mint-chat-conversation-unread-dot {
                        background: rgb(var(--v-theme-error));
                        border-radius: 50%;
                        width: 12px;
                        height: 12px;
                    }
                }
            }

            .mint-chat-conversation-message {
                font-size: 12px;
                letter-spacing: 0.4px;
                white-space: nowrap;
                overflow-x: hidden;
                text-overflow: ellipsis;
            }
        }
    }
}
</style>
