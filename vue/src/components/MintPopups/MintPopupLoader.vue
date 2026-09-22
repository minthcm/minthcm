<template>
    <div class="mint-popup-loader">
        <v-progress-circular indeterminate color="secondary" size="64" width="6" />
        <span v-if="props.data.text" class="mint-popup-loader-text" v-text="props.data.text" />
    </div>
</template>

<script setup lang="ts">
interface Props {
    data: {
        text?: string
    }
}

const props = defineProps<Props>()
</script>

<style scoped lang="scss">
.mint-popup-loader {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
    min-width: 280px;
    padding: 8px 16px;
    text-align: center;

    .mint-popup-loader-text {
        font-size: 14px;
        font-weight: 600;
        color: rgb(var(--v-theme-secondary-dark));
    }
}
</style>
