<template>
    <v-menu location="top" offset="8" open-on-hover :open-delay="50" :close-delay="300">
        <template v-slot:activator="{ props, isActive }">
            <MintButton
                size="small"
                v-bind="props"
                variant="text"
                :text="languages.label('LBL_MINT4_COMMENTS_REACT_BTN')"
                :active="isActive"
            />
        </template>
        <MintReactionsActions
            :active-reaction-type="currentUserReactionType"
            @react="handleReactAction"
            @delete-reaction="store.deleteNewsReaction(props.newsItem.id)"
        />
    </v-menu>
    <MintReactions v-if="props.newsItem.reactions?.length" :reactions="props.newsItem.reactions" />
</template>

<script setup lang="ts">
import { computed } from 'vue'
import MintReactions from '@/components/MintReactions/MintReactions.vue'
import MintReactionsActions from '@/components/MintReactions/MintReactionsActions.vue'
import MintButton from '../MintButtons/MintButton.vue'
import { useMintWallStore } from './MintWallStore'
import { useLanguagesStore } from '@/store/languages'
import { useAuthStore } from '@/store/auth'
import { MintReaction } from '../MintReactions/MintReactions'

const props = defineProps<{
    newsItem: { id: string; reactions?: MintReaction[]; comments_count?: number }
}>()
const auth = useAuthStore()
const languages = useLanguagesStore()
const store = useMintWallStore()

function handleReactAction(type: string) {
    if (type) {
        store.reactToNews(props.newsItem.id, type)
    }
}

const currentUserReactionType = computed(() => {
    return props.newsItem.reactions?.find((reaction: MintReaction) => reaction.user.id === auth.user?.id)?.type
})
</script>
