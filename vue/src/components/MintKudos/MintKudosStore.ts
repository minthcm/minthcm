import { ref, watch } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/store/auth'
import { useAlertsStore } from '@/store/alerts'
import { MintKudos, MintKudosUser, Form, NavOption, Views, InitialResponse } from './types'

export const useMintKudosStore = defineStore('mint-kudos', () => {
    const isLoading = ref(false)
    const isRemovingLoading = ref(false)
    const kudos = ref<MintKudos[]>([])
    const users = ref<MintKudosUser[]>([])
    const auth = useAuthStore()
    const showUserList = ref(false)
    const form = ref<Form>({
        kudosId: '',
        text: '',
        user: { id: '', first_name: '', full_name: '', photo: '' } as MintKudosUser,
        error: false,
        private: false,
        loading: false,
    })
    const page = ref<number>(1)
    const fetchedAllKudos = ref<boolean>(false)
    const activeTab = ref<NavOption>('all')
    const router = useRouter()
    const navOptions = ['all', 'received', 'given'] as const
    const activeView = ref<Views>('kudos-list')
    const showSuccessMessage = ref<boolean>(false)
    async function fetchInitialData() {
        kudos.value = []
        isLoading.value = true
        const response = await axios.get<InitialResponse>(`api/kudos/init`)
        kudos.value = response.data.kudos
        users.value = response.data.users
        isLoading.value = false
    }
    async function fetchKudos(clear = false) {
        if (clear) {
            kudos.value = []
            page.value = 1
            fetchedAllKudos.value = false
        }
        if (!fetchedAllKudos.value && !isLoading.value) {
            if (!clear) {
                page.value++
            }
            isLoading.value = true
            const response = await axios.get<InitialResponse>(
                `api/kudos?listType=${activeTab.value}&page=${page.value}`,
            )
            if (response.data.kudos.length === 0) {
                fetchedAllKudos.value = true
            }
            kudos.value = [...kudos.value, ...response.data.kudos]
            isLoading.value = false
        }
    }

    async function addKudos(gifted_user_id: string, message: string, isPrivate: boolean, id: string) {
        try {
            isLoading.value = true
            const response = await axios.post(`api/kudos/add`, {
                id,
                gifted_user_id,
                message: message,
                private: isPrivate,
            })
            if (response.status === 200) {
                isLoading.value = false
                await fetchKudos(true)
                return true
            } else {
                return false
            }
        } catch (error) {
            return false
        } finally {
            isLoading.value = false
        }
    }

    async function deleteKudos(id: string) {
        try {
            isRemovingLoading.value = true
            const response = await axios.delete(`api/kudos/${id}`)
            if (response.status === 200) {
                isRemovingLoading.value = false
                await fetchKudos(true)
                return true
            } else {
                return false
            }
        } catch (error) {
            return false
        } finally {
            isRemovingLoading.value = false
        }
    }

    async function reactToKudos(id: string, reactionType: string) {
        const kudosItem = kudos.value.find((kudosItem) => kudosItem.id === id)
        if (!kudosItem) {
            return
        }
        if (!kudosItem.reactions) {
            kudosItem.reactions = []
        }
        const userReaction = kudosItem.reactions.find((reaction) => reaction.user.id === auth.user?.id)
        if (userReaction) {
            userReaction.type = reactionType
        } else if (auth.user) {
            kudosItem.reactions.push({
                type: reactionType,
                user: {
                    id: auth.user.id,
                    name: auth.user.full_name,
                },
            })
        }
        await axios.post(`api/reactions/Kudos/${id}`, {
            reaction_type: reactionType,
        })
    }

    async function deleteKudosReaction(id: string) {
        const kudosItem = kudos.value.find((kudosItem) => kudosItem.id === id)
        if (!kudosItem?.reactions) {
            return
        }
        kudosItem.reactions = kudosItem.reactions.filter((reaction) => reaction.user.id !== auth.user?.id)
        await axios.delete(`api/reactions/Kudos/${id}`)
    }

    watch(activeTab, async () => {
        fetchedAllKudos.value = false
        activeTab.value && (await fetchKudos(true))
    })

    function openEmployeeDetailView(userId: string) {
        const userUrl = router.resolve({
            name: 'module-view',
            params: {
                module: 'Employees',
                action: 'DetailView',
                record: userId,
            },
        })
        window.open(userUrl.href, '_self')
    }

    function showError() {
        form.value.loading = false
        form.value.error = true
    }

    function showSuccess() {
        activeView.value = 'success'
        showSuccessMessage.value = true
    }

    function formReset() {
        form.value.text = ''
        form.value.kudosId = ''
        form.value.user = { id: '', first_name: '', full_name: '', photo: '' }
        form.value.error = false
        form.value.private = false
        form.value.loading = false
        activeView.value = 'kudos-list'
    }

    function closeSuccessMessage() {
        formReset()
    }

    function badge() {
        const alerts = useAlertsStore()
        const not_readed_alerts = alerts.alerts.filter(
            (alert) => alert.parent_type === 'Kudos' && alert.is_read === false,
        )
        return not_readed_alerts.length ?? null
    }

    return {
        kudos,
        isLoading,
        isRemovingLoading,
        users,
        showUserList,
        form,
        activeTab,
        navOptions,
        showSuccessMessage,
        page,
        fetchedAllKudos,
        activeView,
        fetchInitialData,
        fetchKudos,
        addKudos,
        deleteKudos,
        reactToKudos,
        deleteKudosReaction,
        openEmployeeDetailView,
        showError,
        showSuccess,
        formReset,
        closeSuccessMessage,
        badge,
    }
})
