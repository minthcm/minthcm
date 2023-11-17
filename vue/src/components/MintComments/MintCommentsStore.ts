import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios'
import { useRoute } from 'vue-router'
import { useAuthStore, User } from '@/store/auth'
import { MintReaction } from '@/components/MintReactions/MintReactions'
import { useLanguagesStore, Languages } from '@/store/languages'

interface InitialResponse {
    user: User
    languages: Languages
    access: MintCommentsAccess
    comments: MintComment[]
    users: MintCommentUser[]
}

interface MintCommentsAccess {
    pin: boolean
    add: boolean
}

interface MintCommentUser {
    id: string
    name: string
    photo: string | null
    user_name: string
    status: string
}

export interface MintComment {
    id: string
    description: string
    pinned: boolean
    removed: boolean
    date_edited: string
    assigned_user: MintCommentUser
    reply_to_id: string
    date_entered: string
    reactions: MintReaction[]
}

export const useMintCommentsStore = defineStore('mint-comments', () => {
    const route = useRoute()

    const isInitialLoading = ref(true)
    const isLoading = ref(false)
    const comments = ref<MintComment[]>([])
    const users = ref<MintCommentUser[]>([])
    const access = ref<MintCommentsAccess>({
        pin: false,
        add: false,
    })
    const auth = useAuthStore()
    const languages = useLanguagesStore()

    const threads = computed<MintComment[]>(() => {
        return comments.value.filter((comment) => !comment.reply_to_id)
    })

    const pinnedThreads = computed<MintComment[]>(() => {
        return threads.value.filter((thread) => thread.pinned && !thread.removed)
    })

    async function fetchInitialData() {
        const response = await axios.get<InitialResponse>(
            `api/comments/${route.params.module}/${route.params.record}/init`,
        )
        auth.user = response.data.user
        languages.languages = {
            app_strings: response.data.languages?.app_strings ?? {},
            app_list_strings: response.data.languages?.app_list_strings ?? {},
            modules: {},
        }
        comments.value = response.data.comments
        access.value = response.data.access
        users.value = response.data.users
        isInitialLoading.value = false
    }

    async function fetchComments() {
        const response = await axios.get(`api/comments/${route.params.module}/${route.params.record}`)
        comments.value = response.data ?? []
    }

    async function addComment(description: string, replyTo?: string) {
        isLoading.value = true
        await axios.post(`api/comments/${route.params.module}/${route.params.record}`, {
            description,
            reply_to_id: replyTo,
        })
        isLoading.value = false
    }

    async function updateComment(id: string, attributes: { [field: string]: unknown }) {
        isLoading.value = true
        await axios.patch(`api/comments/${route.params.module}/${route.params.record}/${id}`, {
            attributes,
        })
        isLoading.value = false
    }

    async function pinComment(id: string) {
        const comment = comments.value.find((comment) => comment.id === id)
        if (!comment || comment.pinned) {
            return
        }
        comment.pinned = true
        await updateComment(id, {
            pinned: true,
        })
    }

    async function unpinComment(id: string) {
        const comment = comments.value.find((comment) => comment.id === id)
        if (!comment || !comment.pinned) {
            return
        }
        comment.pinned = false
        await updateComment(id, {
            pinned: false,
        })
    }

    async function editCommentDescription(id: string, description: string) {
        const comment = comments.value.find((comment) => comment.id === id)
        if (!comment) {
            return
        }
        comment.description = description
        await updateComment(id, { description })
    }

    async function deleteComment(id: string) {
        const comment = comments.value.find((comment) => comment.id === id)
        if (!comment) {
            return
        }
        comment.removed = true
        await updateComment(id, {
            removed: true,
        })
    }

    async function reactToComment(id: string, reactionType: string) {
        const comment = comments.value.find((comment) => comment.id === id)
        if (!comment) {
            return
        }
        if (!comment.reactions) {
            comment.reactions = []
        }
        const userReaction = comment.reactions.find((reaction) => reaction.user.id === auth.user?.id)
        if (userReaction) {
            userReaction.type = reactionType
        } else if (auth.user) {
            comment.reactions.push({
                type: reactionType,
                user: {
                    id: auth.user.id,
                    name: auth.user.full_name,
                },
            })
        }
        await axios.post(`api/reactions/Comments/${id}`, {
            reaction_type: reactionType,
        })
    }

    async function deleteCommentReaction(id: string) {
        const comment = comments.value.find((comment) => comment.id === id)
        if (!comment?.reactions) {
            return
        }
        comment.reactions = comment.reactions.filter((reaction) => reaction.user.id !== auth.user?.id)
        await axios.delete(`api/reactions/Comments/${id}`)
    }

    return {
        access,
        comments,
        isInitialLoading,
        isLoading,
        users,
        threads,
        pinnedThreads,
        fetchInitialData,
        fetchComments,
        addComment,
        updateComment,
        pinComment,
        unpinComment,
        editCommentDescription,
        deleteComment,
        reactToComment,
        deleteCommentReaction,
    }
})
