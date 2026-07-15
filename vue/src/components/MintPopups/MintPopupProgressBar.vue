<template>
    <div class="mint-progress-bar-popup-body">
        <v-progress-linear
            :model-value="percentage"
            color="secondary-dark"
            bg-color="#c8e6c9"
            rounded
            striped
            height="10"
        />
        <div class="mint-progress-bar-popup-counter">
            <span>{{ data.progress.value }} / {{ data.progress.max }}</span>
            <span class="mint-progress-bar-popup-percentage">{{ percentage }}%</span>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

interface Props {
    data: { progress: { value: number; max: number } }
}

const props = defineProps<Props>()

const percentage = computed(() =>
    props.data.progress.max > 0
        ? Math.round((props.data.progress.value / props.data.progress.max) * 100)
        : 0,
)
</script>

<style scoped lang="scss">
.mint-progress-bar-popup-body {
    min-width: 320px;
    padding-bottom: 4px;

    .mint-progress-bar-popup-counter {
        display: flex;
        justify-content: space-between;
        margin-top: 8px;
        font-size: 13px;
        color: rgb(var(--v-theme-secondary-dark));

        .mint-progress-bar-popup-percentage {
            font-weight: 600;
        }
    }
}
</style>

