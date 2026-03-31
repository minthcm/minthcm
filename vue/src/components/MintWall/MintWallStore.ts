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
}

export const useMintWallStore = (key = 'mint') =>
    defineStore(`wall-${key}`, () => {
        const wallLoading = ref(true)
        const auth = useAuthStore()
        const alertsStore = useAlertsStore()
        const route = useRoute()

        const newsList = ref<NewsItem[]>([])

        async function loadNews() {
            wallLoading.value = true
            newsList.value = []
            const apiResponse = await mintApi.get('News/drawer/list')
            if (apiResponse.data) {
                for (const newsItem of apiResponse.data) {
                    newsItem.is_read = isRead(newsItem.id)
                    newsList.value.push(newsItem)
                }
            }
            wallLoading.value = false
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
        }

        return {
            wallLoading,
            newsList,
            loadNews,
            badge,
            reactToNews,
            deleteNewsReaction,
            readNewsAlertsFromLegacy,
            readNewsAlerts,
        }
    })()
