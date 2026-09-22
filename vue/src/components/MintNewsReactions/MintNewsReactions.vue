<template>
    <div class="mint-news-reactions">
        <MintReactionsActions
            :active-reaction-type="currentUserReactionType"
            @react="handleReact"
            @delete-reaction="handleDeleteReaction"
        />
        <MintReactions v-if="reactions.length" :reactions="reactions" />
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { mintApi } from '@/api/api'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'
import { useAuthStore } from '@/store/auth'
import MintReactions from '@/components/MintReactions/MintReactions.vue'
import MintReactionsActions from '@/components/MintReactions/MintReactionsActions.vue'
import type { MintReaction } from '@/components/MintReactions/MintReactions'

const recordViewStore = useRecordViewStore()
const auth = useAuthStore()

const reactions = ref<MintReaction[]>([])

const currentUserReactionType = computed(() => {
    const userReaction = reactions.value.find((r) => r.user.id === auth.user?.id)
    return userReaction?.type ?? undefined
})

watch(
    () => recordViewStore.bean.id,
    async (beanId) => {
        if (!beanId) return
        try {
            const result = await mintApi.get<MintReaction[]>(`reactions/${recordViewStore.bean.module}/${beanId}`)
            reactions.value = result.data ?? []
        } catch {
            reactions.value = []
        }
    },
    { immediate: true },
)

// FIXME - AI CR - Optimistic update persists on API error — UI state diverges from the backend until refreshed. Snapshot the state before mutating and restore it in the catch block (or re-fetch after an error).
const handleReact = async (type: string) => {
    const existingIndex = reactions.value.findIndex((r) => r.user.id === auth.user?.id)
    if (existingIndex !== -1) {
        reactions.value[existingIndex] = { type, user: reactions.value[existingIndex].user }
    } else {
        reactions.value.push({ type, user: { id: auth.user?.id ?? '', name: auth.user?.full_name ?? '' } })
    }
    try {
        await mintApi.post(`reactions/${recordViewStore.bean.module}/${recordViewStore.bean.id}`, { reaction_type: type })
    } catch {
        // noop — optimistic update stays
    }
}

const handleDeleteReaction = async () => {
    reactions.value = reactions.value.filter((r) => r.user.id !== auth.user?.id)
    try {
        await mintApi.delete(`reactions/${recordViewStore.bean.module}/${recordViewStore.bean.id}`)
    } catch {
        // noop — optimistic update stays
    }
}
</script>

<style scoped lang="scss">
.mint-news-reactions {
    display: flex;
    align-items: center;
    gap: 8px;
}
</style>
