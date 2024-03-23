import { computed, ref } from 'vue'
import { defineStore } from 'pinia'
import MintChatDefault from './MintChatDefault.vue'
import MintChatEdit from './MintChatEdit.vue'
import MintChatList from './MintChatList.vue'
import MintChatDetail from './MintChatDetail.vue'
import { useAuthStore } from '@/store/auth'
import { DateTime } from 'luxon'

interface ChatUser {
    id: string
    name: string
    first_name?: string
    last_name: string
    photo?: string //url
    date_read?: string
}

export interface ChatMessage {
    id: string
    text: string
    date_entered: string
    user_id: string
}

interface Conversation {
    id: string
    name: string
    type: 'private' | 'group'
    messages?: ChatMessage[] // last message
    date_active: string // db format datetime
    date_read?: string // current user date read
    users: ChatUser[]
}

export const useMintChatStore = (key = 'mint') =>
    defineStore(`chat-${key}`, () => {
        const auth = useAuthStore()
        
        // Views
        const views = {
            default: MintChatDefault,
            edit: MintChatEdit,
            list: MintChatList,
            detail: MintChatDetail,
        }
        const view = ref<'default' | 'edit' | 'list' | 'detail'>('default')
        const currentView = computed(() => views[view.value] || views['default'])
        function openDetailView(conversationId: string) {
            activeConversationId.value = conversationId
            view.value = 'detail'
        }

        // Chat Users
        const chatUsersLoading = ref(false)
        const chatUsersSearchQuery = ref('')
        const chatUsers = ref<ChatUser[]>([
            {
                id: '1',
                name: 'Administrator',
                first_name: '',
                last_name: 'Administrator',
                photo: 'legacy/index.php?entryPoint=download&type=Users&id=1_photo',
            },
            {
                id: '9c32d170-f872-01d4-868b-64703e9d54bf',
                name: 'John Smith',
                first_name: 'John',
                last_name: 'Smith',
                photo: 'legacy/index.php?entryPoint=download&type=Users&id=9c32d170-f872-01d4-868b-64703e9d54bf_photo',
            },
            {
                id: '14a7c3af-44fc-4e11-ef11-5ce3c5778501',
                name: 'Julia Lee',
                first_name: 'Julia',
                last_name: 'Lee',
                photo: 'legacy/index.php?entryPoint=download&type=Users&id=14a7c3af-44fc-4e11-ef11-5ce3c5778501_photo',
            },
            {
                id: 'd95da4be-7b55-f646-c721-64703ff94f65',
                name: 'Eva Hoffman',
                first_name: 'Eva',
                last_name: 'Hoffman',
                photo: 'legacy/index.php?entryPoint=download&type=Users&id=d95da4be-7b55-f646-c721-64703ff94f65_photo',
            },
        ])
        const usersList = computed(() => {
            const usersList = chatUsers.value.filter(
                (u) => !chatUsersSearchQuery.value || u.name.toLowerCase().includes(chatUsersSearchQuery.value.toLowerCase()),
            )
            return usersList.sort((a, b) => a.name.localeCompare(b.name, 'pl'))
        })

        // Conversations
        const activeConversationId = ref<string | null>(null)
        const activeConversation = computed(() =>
            conversationsList.value.find((c) => c.id === activeConversationId.value),
        )
        const conversationsSearchQuery = ref('')
        const conversations = ref<Conversation[]>([
            {
                id: 'abcd',
                name: 'Administrator',
                type: 'private',
                messages: [{ id: 'm1', text: 'Test message', date_entered: '2023-05-23 10:00:00', user_id: '1' }],
                date_active: '2023-05-22 08:00:00',
                date_read: '2023-05-22 07:50:00',
                users: [
                    {
                        id: '1',
                        name: 'Administrator',
                        first_name: '',
                        last_name: 'Administrator',
                        photo: 'legacy/index.php?entryPoint=download&type=Users&id=1_photo',
                    },
                ],
            },
            {
                id: 'aaaa',
                name: 'John Smith',
                type: 'private',
                messages: [
                    {
                        id: 'm1',
                        text: 'Perfect. Once you’re done, let’s have a quick meeting to go over it together.',
                        date_entered: '2023-05-25 10:00:00',
                        user_id: '1',
                    },
                    { id: 'm2', text: 'Sounds like a plan. How about tomorrow morning at 10 am?', date_entered: '2023-05-25 10:00:20', user_id: '9c32d170-f872-01d4-868b-64703e9d54bf' },
                    { id: 'm22', text: 'Works for me.', date_entered: '2023-05-25 10:10:23', user_id: '1' },
                    { id: 'm3', text: 'I’ll block off the time on my calendar..', date_entered: '2023-05-25 10:10:40', user_id: '1' },
                    { id: 'm33', text: 'Excellent. I’ll send you an invite shortly.', date_entered: '2023-05-25 13:51:26', user_id: '9c32d170-f872-01d4-868b-64703e9d54bf' },
                    { id: 'm34', text: 'Thanks!', date_entered: '2023-05-25 14:54:05', user_id: '1' },
                    {
                        id: 'm35',
                        text: 'Looking forward to reviewing the final report.',
                        date_entered: '2023-05-25 14:54:26',
                        user_id: '1',
                    },
                ],
                date_active: '2023-04-21 09:00:00',
                date_read: '2023-05-22 07:50:00',
                users: [
                    {
                        id: '9c32d170-f872-01d4-868b-64703e9d54bf',
                        name: 'John Smith',
                        first_name: 'John',
                        last_name: 'Smith',
                        photo: 'legacy/index.php?entryPoint=download&type=Users&id=9c32d170-f872-01d4-868b-64703e9d54bf_photo',
                    },
                ],
            },
            // {
            //     id: 'qwerty',
            //     name: 'Szkolenie BHP',
            //     type: 'group',
            //     messages: [{ id: 'm1', text: 'testowy message', date_entered: '2023-05-23 10:00:00', user_id: '1' }],
            //     date_active: '2023-05-20 08:00:00',
            //     users: [
            //         {
            //             id: '1',
            //             name: 'Michał T',
            //             first_name: 'Michał',
            //             last_name: 'T',
            //             photo: 'legacy/index.php?entryPoint=download&type=Users&id=1_photo',
            //         },
            //         {
            //             id: '9c32d170-f872-01d4-868b-64703e9d54bf',
            //             name: 'John Smith',
            //             first_name: 'John',
            //             last_name: 'Smith',
            //             photo: '9c32d170-f872-01d4-868b-64703e9d54bf_photo',
            //         },
            //     ],
            // },
            {
                id: 'vvvv',
                name: 'Julia Lee',
                type: 'private',
                messages: [
                    {
                        id: 'm1',
                        text: 'Hello 😊',
                        date_entered: '2023-05-23 10:00:00',
                        user_id: '14a7c3af-44fc-4e11-ef11-5ce3c5778501',
                    },
                ],
                date_active: '2023-05-20 08:00:00',
                users: [
                    {
                        id: '14a7c3af-44fc-4e11-ef11-5ce3c5778501',
                        name: 'Julia Lee',
                        first_name: 'Julia',
                        last_name: 'Lee',
                        photo: 'legacy/index.php?entryPoint=download&type=Users&id=14a7c3af-44fc-4e11-ef11-5ce3c5778501_photo',
                    },
                ],
            },
            // {
            //     id: 'bbbb',
            //     name: 'Eva Hoffman',
            //     type: 'private',
            //     messages: [
            //         {
            //             id: 'm1',
            //             text: 'Hi 😊',
            //             date_entered: '2023-05-23 12:00:00',
            //             user_id: 'd95da4be-7b55-f646-c721-64703ff94f65',
            //         },
            //     ],
            //     date_active: '2023-05-23 12:00:00',
            //     users: [
            //         {
            //             id: 'd95da4be-7b55-f646-c721-64703ff94f65',
            //             name: 'Eva Hoffman',
            //             first_name: 'Eva',
            //             last_name: 'Hoffman',
            //             photo: 'legacy/index.php?entryPoint=download&type=Users&id=d95da4be-7b55-f646-c721-64703ff94f65_photo',
            //         },
            //     ],
            // },
        ])
        const unreadConversationsCount = computed(
            () => conversations.value.filter((c) => {
                const lastMessage = c.messages?.at(-1)
                return (
                    lastMessage
                    && lastMessage.user_id !== auth.user?.id
                    && (!c.date_read || lastMessage.date_entered > c.date_read)
                )
            }).length || 0,
        )
        const conversationsList = computed(() => {
            const conversationsList = conversations.value.filter(
                (c) => !conversationsSearchQuery.value || c.name.toLowerCase().includes(conversationsSearchQuery.value?.toLowerCase()),
            )
            return conversationsList.sort((a, b) => {
                const aLastMessage = a.messages?.at(-1)
                const bLastMessage = b.messages?.at(-1)
                if (!aLastMessage) {
                    return 1
                }
                if (!bLastMessage) {
                    return -1
                }
                return aLastMessage.date_entered > bLastMessage.date_entered ? -1 : 1
            })
        })

        function openPrivateConversation(user: ChatUser) {
            const conv = conversations.value.find((c) => c.users[0].id === user.id)
            if (conv) {
                view.value = 'detail'
                activeConversationId.value = conv.id
            } else {
                const now = DateTime.now()
                const newConv: Conversation = {
                    id: new Date().getTime().toString(),
                    date_active: now.toSQL()!,
                    date_read: now.toSQL()!,
                    type: 'private',
                    users: [{ ...user }],
                    messages: [],
                    name: user.name,
                }
                conversations.value.push(newConv)
                view.value = 'detail'
                activeConversationId.value = newConv.id
            }
        }

        return {
            view,
            currentView,
            openDetailView,
            chatUsersSearchQuery,
            usersList,
            chatUsersLoading,
            activeConversationId,
            activeConversation,
            conversationsSearchQuery,
            conversations,
            conversationsList,
            unreadConversationsCount,
            openPrivateConversation,
        }
    })()
