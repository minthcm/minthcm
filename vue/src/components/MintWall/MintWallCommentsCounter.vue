<template>
    <div v-if="commentsCount" class="mint-news-comments-counter">
        <v-icon size="small" icon="mdi-comment-outline" />
        <span>{{ commentsCount }}</span>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    commentsCount?: number
}>()
</script>

<style scoped lang="scss">
.mint-news-comments-counter {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 13px;
    color: #8a8a8a;
    flex: 0 0 auto;
}
</style>
