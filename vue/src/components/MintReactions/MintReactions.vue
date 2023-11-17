<template>
    <div class="mint-reactions-container">
        <div class="mint-reactions">
            <v-tooltip v-for="(reactionType, index) in reactionTypes" :key="reactionType.type" location="top">
                <template v-slot:activator="{ props }">
                    <span
                        v-bind="props"
                        :class="{
                            'mint-reaction': true,
                            'mint-reaction-owner': currentUserReaction === reactionType.type,
                        }"
                        :style="{ zIndex: 50 - index }"
                    >
                        {{ reactionType.icon }}
                    </span>
                </template>
                <div>
                    <div>
                        <b>{{ reactionType.icon }} {{ reactionType.type }}:</b>
                    </div>
                    <div
                        v-for="user in reactionUsers[reactionType.type].slice(0, MAX_USERS_IN_TOOLTIP)"
                        :key="user.id"
                        v-text="user.name"
                    />
                    <div v-if="reactionUsers[reactionType.type].length > MAX_USERS_IN_TOOLTIP" v-text="'...'" />
                </div>
            </v-tooltip>
        </div>
        <div class="mint-reactions-count">
            <v-tooltip location="top" :disabled="!reactionsCount">
                <template v-slot:activator="{ props }">
                    <span v-bind="props">{{ reactionsCount }}</span>
                </template>
                <div>
                    <div
                        v-for="user in allReactedUsers.slice(0, MAX_USERS_IN_TOOLTIP)"
                        :key="user.id"
                        v-text="user.name"
                    />
                    <div v-if="allReactedUsers.length > MAX_USERS_IN_TOOLTIP" v-text="'...'" />
                </div>
            </v-tooltip>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useAuthStore } from '@/store/auth'
import { MintReaction, MintReactionUser, REACTION_TYPES, MAX_USERS_IN_TOOLTIP } from './MintReactions'

interface Props {
    reactions: null | MintReaction[]
}

const props = defineProps<Props>()
const auth = useAuthStore()

const reactionTypes = computed(() => {
    return REACTION_TYPES.filter((reactionType) =>
        props.reactions?.find((reaction) => reaction.type === reactionType.type),
    )
})

const currentUserReaction = computed(() => {
    if (!props.reactions?.length) {
        return null
    }
    return props.reactions.find((reaction) => reaction.user.id === auth.user?.id)?.type
})

interface ReactionUsers {
    [type: string]: MintReactionUser[]
}

const reactionUsers = computed<ReactionUsers>(() => {
    return reactionTypes.value.reduce<ReactionUsers>((reactionUsers, reactionType) => {
        reactionUsers[reactionType.type] =
            props.reactions
                ?.filter((reaction) => reaction.type === reactionType.type)
                .map((reaction) => reaction.user) ?? []
        return reactionUsers
    }, {})
})

const allReactedUsers = computed(() => {
    if (!reactionsCount.value) {
        return []
    }
    return props.reactions?.map((reaction) => reaction.user) ?? []
})

const reactionsCount = computed(() => props.reactions?.length ?? 0)
</script>

<style scoped lang="scss">
.mint-reactions-container {
    display: flex;
    align-items: center;
    gap: 10px;
}

.mint-reactions {
    display: flex;
    position: relative;
    justify-content: center;
    align-items: center;
    margin-left: 8px;

    .mint-reaction {
        border-radius: 50%;
        background: white;
        // position: absolute;
        width: 24px;
        height: 24px;
        font-size: 15px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-left: -8px;
        user-select: none;

        &.mint-reaction-owner {
            background: rgb(var(--v-theme-secondary));
        }
    }
}

.mint-reactions-count {
    font-size: 12px;
    font-weight: 600;
    color: rgb(var(--v-theme-secondary));
}
</style>
