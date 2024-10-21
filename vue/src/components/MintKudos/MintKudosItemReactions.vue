<template>
    <v-menu location="top" offset="8">
        <template v-slot:activator="{ props, isActive }">
            <MintButton
                size="small"
                v-bind="props"
                variant="text"
                :text="languages.label('LBL_MINT4_COMMENTS_REACT_BTN')"
                :active="isActive"
                class="mr-auto"
            />
        </template>
        <MintReactionsActions
            :active-reaction-type="currentUserReactionType"
            @react="handleReactAction"
            @delete-reaction="store.deleteKudosReaction(props.kudos.id)"
        />
    </v-menu>
    <MintReactions v-if="props.kudos.reactions?.length" :reactions="props.kudos.reactions" class="ml-auto" />
</template>

<script setup lang="ts">
import { computed } from 'vue'
import MintReactions from '@/components/MintReactions/MintReactions.vue'
import MintReactionsActions from '@/components/MintReactions/MintReactionsActions.vue'
import MintButton from '../MintButtons/MintButton.vue'
import { useMintKudosStore } from './MintKudosStore'
import { useLanguagesStore } from '@/store/languages'
import { useAuthStore } from '@/store/auth'
import { MintReaction } from '../MintReactions/MintReactions'

const props = defineProps(['kudos'])
const auth = useAuthStore()
const languages = useLanguagesStore()
const store = useMintKudosStore()

function handleReactAction(type: string) {
    if (type) {
        store.reactToKudos(props.kudos.id, type)
    }
}

const currentUserReactionType = computed(() => {
    return props.kudos.reactions?.find((reaction: MintReaction) => reaction.user.id === auth.user?.id)?.type
})
</script>

<style scoped lang="scss">
.mint-kudos-reactions {
    display: flex;
    flex-direction: row-reverse;
    gap: 8px;
}
</style>
