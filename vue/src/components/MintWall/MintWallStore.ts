import { ref } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios'
import { useAuthStore } from '@/store/auth'

interface NewsAutor {
    id: string
    name: string
    photo?: string //url
}

interface Reaction {
    id: string
    type: string
    user_id: string
}

interface NewsItem {
    id: string
    name: string
    publication_date: string
    photo?: string //url
    author: NewsAutor
    content_of_announcement: string
    reactions: Array<Reaction>
    liked: boolean
}

export const useMintWallStore = (key = 'mint') =>
    defineStore(`wall-${key}`, () => {
        const wallLoading = ref(true)
        const auth = useAuthStore()

        const newsList = ref<NewsItem[]>([])

        async function loadNews() {
            wallLoading.value = true
            newsList.value = []
            const apiResponse = await axios.get('api/News/drawer/list')
            if (apiResponse.data) {
                for (const newsItem of apiResponse.data) {
                    newsItem.liked = getReactionIndex(newsItem, 'like') !== -1
                    newsList.value.push(newsItem)
                }
            }
            wallLoading.value = false
        }

        function getReactionIndex(newsItem: NewsItem, reactionType: string) {
            return newsItem?.reactions?.findIndex((x) => x?.type === reactionType && x?.user_id === auth?.user?.id)
        }

        function addReaction(newsItem: NewsItem, reactionType: string) {
            axios
                .post('api/Reactions/Create', {
                    record_data: {
                        parent_type: 'News',
                        parent_id: newsItem.id,
                        assigned_user_id: auth?.user?.id,
                        reaction_type: reactionType,
                    },
                })
                .then((response) => {
                    if (response?.data?.id) {
                        newsItem.reactions.push({
                            id: response.data.id,
                            type: response.data.reaction_type,
                            user_id: response.data.assigned_user_id,
                        })
                    }
                })
        }

        function deleteReaction(newsItem: NewsItem, reactionType: string) {
            const reactionIndex = getReactionIndex(newsItem, reactionType)
            if (reactionIndex !== -1) {
                axios
                    .delete('api/Reactions/' + newsItem.reactions[reactionIndex].id)
                    .then(() => newsItem?.reactions?.splice(reactionIndex, 1))
            }
        }

        function likeClicked(newsId: string) {
            const news = newsList.value.find((n) => n.id === newsId)
            if (news) {
                if (news.liked) {
                    deleteReaction(news, 'like')
                } else {
                    addReaction(news, 'like')
                }
                news.liked = !news.liked
            }
        }

        return {
            wallLoading,
            newsList,
            loadNews,
            likeClicked,
        }
    })()
