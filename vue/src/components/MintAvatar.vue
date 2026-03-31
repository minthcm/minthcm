<template>
    <div class="mint-avatar">
        <img v-if="photoSrc" :src="photoSrc" />
        <v-icon v-else :icon="props.icon ?? 'mdi-account'" />
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

interface Props {
    photo?: string | null
    icon?: string
}

const props = defineProps<Props>()

const photoSrc = computed(() => {
    if (!props.photo?.trim()) {
        return null
    }
    return `legacy/index.php?entryPoint=download&type=Users&id=${props.photo.trim()}`
})
</script>

<style scoped lang="scss">
.mint-avatar {
    width: 32px;
    min-width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;

    img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }
}
</style>
