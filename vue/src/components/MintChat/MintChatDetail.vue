<template>
    <div class="mint-chat-detail">
        <div class="mint-chat-detail-header">
            <MintButton variant="nav" icon="mdi-arrow-left" @click="chat.view = 'default'" />
            <img v-if="chat.activeConversation?.users[0].photo" :src="chat.activeConversation?.users[0].photo" />
            <v-icon v-else :icon="chat.activeConversation?.users.length! > 1 ? 'mdi-account-group' : 'mdi-account'" />
            <div class="mint-chat-detail-header-name" v-text="chat.activeConversation?.name" />
            <v-icon icon="mdi-dots-vertical" />
        </div>
        <div class="mint-chat-detail-content" ref="messagesContent">
            <div
                v-for="conv in conversationMessages"
                :key="conv.date"
                style="display: flex; flex-direction: column; gap: 4px"
            >
                <div class="mint-chat-detail-date-title" v-text="conv.date" />
                <div
                    v-for="messageGroup in conv.messageGroups"
                    :key="messageGroup.lastMessageId"
                    :class="{
                        'mint-chat-message-group': true,
                        'mint-chat-message-group-owner': messageGroup.messages[0].user_id === auth.user?.id,
                    }"
                >
                    <div class="mint-chat-messages">
                        <div
                            v-for="message in messageGroup.messages"
                            :key="message.id"
                            class="mint-chat-message"
                            v-text="message.text"
                        />
                    </div>
                    <div class="mint-chat-message-group-footer">
                        <span v-text="messageGroup.time" />
                    </div>
                </div>
            </div>
        </div>
        <div class="mint-chat-detail-footer">
            <v-textarea
                class="mint-chat-detail-input"
                v-model="messageText"
                variant="plain"
                hide-details
                placeholder="Message"
                auto-grow
                rows="1"
                max-rows="3"
                @keydown.enter.prevent="sendMessage"
            />
            <MintButton variant="nav" icon="mdi-send" @click="sendMessage" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { DateTime } from 'luxon'
import { useMintChatStore, ChatMessage } from './MintChatStore'
import { useAuthStore } from '@/store/auth'
import MintButton from '@/components/MintButtons/MintButton.vue'
import { nextTick } from 'vue'
import { onMounted } from 'vue'
import axios from 'axios'
import { onUnmounted } from 'vue'
import { onUpdated } from 'vue'

const chat = useMintChatStore()
const auth = useAuthStore()
const messageText = ref('')
const messagesContent = ref<HTMLDivElement | null>(null)

interface MessageGroup {
    lastMessageId: string
    messages: ChatMessage[]
    time: string | null
}

interface ConversationMessage {
    date: string
    messageGroups: MessageGroup[]
}

const conversationMessages = computed(() => {
    const conversationMessages: ConversationMessage[] = []
    chat.activeConversation?.messages?.forEach((message) => {
        const date = getRelativeDate(message.date_entered)
        let conv = conversationMessages.find((cm) => cm.date === date)
        if (!conv) {
            conv = {
                date,
                messageGroups: [],
            }
            conversationMessages.push(conv)
        }
        const lastMessage = conv.messageGroups.at(-1)?.messages.at(-1)
        if (!lastMessage || lastMessage.user_id !== message.user_id) {
            conv.messageGroups.push({
                messages: [message],
                time: DateTime.fromSQL(message.date_entered).toFormat('HH:mm'),
                lastMessageId: message.id,
            })
        } else {
            const dtPrev = DateTime.fromSQL(lastMessage.date_entered)
            const dt = DateTime.fromSQL(message.date_entered)
            if (dt.diff(dtPrev, 'seconds').seconds <= 30) {
                conv.messageGroups.at(-1)?.messages.push(message)
            } else {
                conv.messageGroups.push({
                    messages: [message],
                    time: DateTime.fromSQL(message.date_entered).toFormat('HH:mm'),
                    lastMessageId: message.id,
                })
            }
        }
    })
    return conversationMessages
})

function getRelativeDate(date: string) {
    return DateTime.fromSQL(date).toRelativeCalendar() || ''
}

async function sendMessage() {
    if (!messageText.value.trim()) {
        return
    }
    chat.conversations
        .find((c) => c.id === chat.activeConversationId)
        ?.messages?.push({
            id: Math.random().toString(),
            user_id: auth.user?.id || '1',
            text: messageText.value.trim(),
            date_entered: DateTime.now().toSQL() || '',
        })
    messageText.value = ''
    await nextTick()
    if (messagesContent.value) {
        messagesContent.value.scrollTo(0, messagesContent.value.scrollHeight)
    }
    setTimeout(() => {
        sendAutoMessage(chat.activeConversationId || '')
    }, 3000)
}

onMounted(async () => {
    await nextTick()
    if (messagesContent.value) {
        messagesContent.value.scrollTo(0, messagesContent.value.scrollHeight)
    }
    chat.conversations.find(c => c.id === chat.activeConversationId).date_read = DateTime.now().toSQL()
    
    initTimeout(chat.activeConversationId)
})

const autotimeout = ref<any>(null)
function initTimeout(convId) {
    autotimeout.value = setTimeout(() => {
        sendAutoMessage(convId)
        initTimeout(convId)
    }, Math.round(Math.random() * 60) * 1000)
}

onUnmounted(() => {
    clearTimeout(autotimeout.value)
})

onUpdated(() => {
    chat.conversations.find(c => c.id === chat.activeConversationId).date_read = DateTime.now().toSQL()
})

async function sendAutoMessage(convId: string) {
    return
    try {
        const message = (await axios.get(`https://fakerapi.it/api/v1/texts?_locale=pl_PL&_quantity=1&_characters=${Math.round(Math.random() * 50 + 10)}`))?.data?.data?.[0]?.content || 'asd'
        if (!message) {
            return
        }
        chat.conversations
            .find((c) => c.id === convId)
            ?.messages?.push({
                id: Math.random().toString(),
                user_id: 'x',
                text: message,
                date_entered: DateTime.now().toSQL() || '',
            })
        await nextTick()
        if (messagesContent.value) {
            messagesContent.value.scrollTo(0, messagesContent.value.scrollHeight)
        }
    } catch {}
}
</script>

<style scoped lang="scss">
.mint-chat-detail {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.mint-chat-detail-header {
    display: flex;
    gap: 16px;
    align-items: center;
    padding: 8px 16px 8px 16px;
    border-bottom: thin solid #0002;
    font-weight: 600;
    color: rgb(var(--v-theme-secondary));
    font-size: 14px;

    .mint-chat-detail-header-name {
        flex-grow: 1;
    }

    img {
        object-fit: cover;
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }
}

.mint-chat-detail-content {
    flex-grow: 1;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 2px;
    overflow: scroll;
    scroll-behavior: smooth;

    .mint-chat-detail-date-title {
        font-weight: 600;
        text-align: center;
        padding: 8px 0px;
    }

    .mint-chat-message-group {
        display: flex;
        flex-direction: column;
        gap: 2px;
        .mint-chat-messages {
            display: flex;
            flex-direction: column;
            gap: 2px;

            :first-child {
                border-top-left-radius: 16px !important;
            }
            :last-child {
                border-bottom-left-radius: 16px !important;
            }
            .mint-chat-message {
                max-width: 75%;
                background: rgb(var(--v-theme-primary-light));
                color: #000d;
                font-size: 16px;
                width: fit-content;
                padding: 8px 16px;
                border-top-right-radius: 16px;
                border-bottom-right-radius: 16px;
                border-radius: 0px 16px 16px 0px;
                letter-spacing: 0.5px;
            }
        }
        .mint-chat-message-group-footer {
            font-size: 10px;
            padding: 0px 12px;
            color: #00000099;
            letter-spacing: 0.33px;
        }
    }

    .mint-chat-message-group-owner {
        .mint-chat-messages {
            align-items: flex-end;
            :first-child {
                border-top-right-radius: 16px !important;
            }
            :last-child {
                border-bottom-right-radius: 16px !important;
            }
            .mint-chat-message {
                background: #00654ec9;
                color: white;
                border-radius: 16px 0px 0px 16px;
            }
        }
        .mint-chat-message-group-footer {
            text-align: end;
        }
    }
}

.mint-chat-detail-footer {
    border-top: thin solid #0002;
    display: flex;
    gap: 16px;
    align-items: center;
    padding: 4px 16px;

    .mint-chat-detail-input {
        :deep(.v-field__input) {
            padding-top: 12px;
            mask-image: none !important;
            -webkit-mask-image: none !important;
        }
    }
}
</style>
