<template>
    <div class="mint-reactions-actions">
        <div
            v-for="reactionType in REACTION_TYPES"
            :key="reactionType.type"
            :class="{
                'mint-reactions-action': true,
                'mint-reactions-action-active': reactionType.type === props.activeReactionType,
            }"
            v-text="reactionType.icon"
            v-ripple
            @click="handleReactionClick(reactionType.type)"
        />
    </div>
</template>

<script setup lang="ts">
import { REACTION_TYPES } from './MintReactions'

interface Props {
    activeReactionType?: string
}

const props = defineProps<Props>()
const emit = defineEmits(['react', 'delete-reaction'])

function handleReactionClick(type: string) {
    if (type === props.activeReactionType) {
        emit('delete-reaction')
    } else {
        emit('react', type)
    }
}
</script>

<style scoped lang="scss">
.mint-reactions-actions {
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgb(var(--v-theme-primary-light));
    box-shadow: 0px 3px 6px #00000029;
    border-radius: 100px;
    gap: 8px;
    padding: 8px;

    .mint-reactions-action {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 20px;
        width: 32px;
        height: 32px;
        cursor: pointer;
        user-select: none;

        &:hover {
            background: #0001;
        }

        &.mint-reactions-action-active {
            background: rgb(var(--v-theme-secondary));
        }
    }
}
</style>
