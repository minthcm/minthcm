<template>
    <div class="mint-popup-image">
        <img :src="props.data.src" :alt="props.data.alt ?? ''" class="popup-image" />
    </div>
</template>

<script setup lang="ts">
interface Props {
    data: {
        src: string
        alt?: string
    }
}

const props = defineProps<Props>()
</script>

<style scoped lang="scss">
.mint-popup-image {
    display: flex;
    align-items: center;
    justify-content: center;

    .popup-image {
        max-width: 80vw;
        max-height: 80vh;
        object-fit: contain;
        border-radius: 4px;
    }
}
</style>
