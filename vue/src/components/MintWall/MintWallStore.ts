import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { useAuthStore } from '@/store/auth'
import { useAlertsStore } from '@/store/alerts'
import { MintReaction } from '../MintReactions/MintReactions'
import { useRoute } from 'vue-router'
import { mintApi } from '@/api/api'

interface NewsAutor {
    id: string
    name: string
    photo?: string //url
}
interface NewsItem {
    id: string
    name: string
    publication_date: string
    photo?: string //url
    author: NewsAutor
    content_of_announcement: string
    reactions: MintReaction[]
    liked: boolean
    is_read: boolean | undefined
    comments_count?: number
}

export interface NewsComment {
    id: string
    description: string
    date_entered: string
    reply_to_id: string | null
    removed: boolean
    assigned_user: {
        id: string
        full_name: string
        photo: string | null
    }
}

export const useMintWallStore = (key = 'mint') =>
    defineStore(`wall-${key}`, () => {
        const wallLoading = ref(true)
        const auth = useAuthStore()
        const alertsStore = useAlertsStore()
        const route = useRoute()

        const newsList = ref<NewsItem[]>([])
        const commentsByNewsId = ref<Record<string, NewsComment[]>>({})
        const commentsLoading = ref<Set<string>>(new Set())

        async function loadNews() {
            wallLoading.value = true
            newsList.value = []
            try {
                const apiResponse = await mintApi.get('News/drawer/list')
                if (apiResponse?.data) {
                    for (const newsItem of apiResponse.data) {
                        newsItem.is_read = isRead(newsItem.id)
                        newsList.value.push(newsItem)
                    }
                }
            } finally {
                wallLoading.value = false
            }
        }

        async function fetchComments(newsId: string) {
            commentsLoading.value = new Set(commentsLoading.value.add(newsId))
            try {
                const response = await mintApi.get(`comments/News/${newsId}`)
                const all: NewsComment[] = response.data ?? []
                commentsByNewsId.value = {
                    ...commentsByNewsId.value,
                    [newsId]: all
                        .filter((c) => c.description && c.assigned_user)
                        .sort((a, b) => a.date_entered.localeCompare(b.date_entered)),
                }
            } finally {
                commentsLoading.value.delete(newsId)
                commentsLoading.value = new Set(commentsLoading.value)
            }
        }

        function isRead(newsItemId: string) {
            return alertsStore.alerts.find((alert) => alert.parent_id === newsItemId)?.is_read
        }

        const badge = computed(() => {
            const unreadAlerts = alertsStore.alerts.filter(
                (alert) => alert.parent_type === 'News' && alert.is_read === false,
            )
            return unreadAlerts.length ?? null
        })

        async function reactToNews(id: string, reactionType: string) {
            const newsItem = newsList.value.find((newsItem) => newsItem.id === id)
            if (!newsItem) {
                return
            }
            if (!newsItem.reactions) {
                newsItem.reactions = []
            }
            const userReaction = newsItem.reactions.find((reaction) => reaction.user.id === auth.user?.id)
            if (userReaction) {
                userReaction.type = reactionType
            } else if (auth.user) {
                newsItem.reactions.push({
                    type: reactionType,
                    user: {
                        id: auth.user.id,
                        name: auth.user.full_name,
                    },
                })
            }
            await mintApi.post(`reactions/News/${id}`, {
                reaction_type: reactionType,
            })
        }

        async function deleteNewsReaction(id: string) {
            const newsItem = newsList.value.find((newsItem) => newsItem.id === id)
            if (!newsItem?.reactions) {
                return
            }
            newsItem.reactions = newsItem.reactions.filter((reaction) => reaction.user.id !== auth.user?.id)
            await mintApi.delete(`Reactions/${id}`)
        }

        async function readNewsAlertsFromLegacy() {
            if (route.params?.module !== 'News' && route.params?.action !== 'DetailView' && !route.params?.record) {
                return
            }
            const newsId = route.params.record
            await readNewsAlerts(newsId as string)
        }

        async function readNewsAlerts(newsId: string) {
            if (!newsId) {
                return
            }
            const result = await mintApi.patch('News/update/readAlerts', { news_id: newsId })
            alertsStore.alerts = result.data?.alerts ?? []
            alertsStore.moreResults = result.data?.moreResults ?? false
            const newsItem = newsList.value.find((item) => item.id === newsId)
            if (newsItem) {
                newsItem.is_read = true
            }
        }

        async function addComment(newsId: string, description: string, replyToId?: string) {
            const tempId = `temp-${Date.now()}`
            const optimistic: NewsComment = {
                id: tempId,
                description,
                date_entered: new Date().toISOString().replace('T', ' ').substring(0, 19),
                reply_to_id: replyToId ?? null,
                removed: false,
                assigned_user: {
                    id: auth.user?.id ?? '',
                    full_name: auth.user?.full_name ?? '',
                    photo: auth.user?.photo ?? null,
                },
            }
            const existing = commentsByNewsId.value[newsId] ?? []
            commentsByNewsId.value = {
                ...commentsByNewsId.value,
                [newsId]: [...existing, optimistic],
            }

            try {
                await mintApi.post(`comments/News/${newsId}`, {
                    description,
                    reply_to_id: replyToId ?? null,
                })
            } finally {
                const response = await mintApi.get(`comments/News/${newsId}`)
                const all: NewsComment[] = response.data ?? []
                commentsByNewsId.value = {
                    ...commentsByNewsId.value,
                    [newsId]: all
                        .filter((c) => c.description && c.assigned_user)
                        .sort((a, b) => a.date_entered.localeCompare(b.date_entered)),
                }
            }
        }

        return {
            wallLoading,
            newsList,
            commentsByNewsId,
            commentsLoading,
            loadNews,
            fetchComments,
            badge,
            reactToNews,
            deleteNewsReaction,
            readNewsAlertsFromLegacy,
            readNewsAlerts,
            addComment,
        }
    })()
