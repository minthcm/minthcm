<template>
    <div class="scheduler-timeline">
        <div class="left-col"></div>
        <div class="scheduler-timeline-hours">
            <MintSchedulerCurrentlyPlanning :scheduler="props.scheduler" />
            <div v-for="hour in props.scheduler.schedulerHours.value" :key="hour">
                <div class="scheduler-timeline-hour">{{ hour }}</div>
                <div />
                <div />
                <div />
                <div />
            </div>
        </div>
        <div style="display: block; width: 50px"></div>
    </div>
</template>

<script setup lang="ts">
import MintSchedulerCurrentlyPlanning from './MintSchedulerCurrentlyPlanning.vue'
import { useMintScheduler } from './useMintScheduler'

interface Props {
    scheduler: ReturnType<typeof useMintScheduler>
}

const props = defineProps<Props>()
</script>

<style scoped lang="scss">
.scheduler-timeline {
    font-size: 0.75em;
    display: flex;
    width: 100%;
    position: absolute;
    height: 100%;
    user-select: none;
    -webkit-user-select: none;
}

.scheduler-timeline-hours {
    position: relative;
    flex-grow: 1;
    display: flex;
    border-left: thin solid #dbdbdb;
    > div {
        flex: 1;
        display: flex;
        border-right: thin solid #dbdbdb;
        .scheduler-timeline-hour {
            position: absolute;
            padding-left: 4px;
            color: #00000099;
        }
        > div:not(.scheduler-timeline-hour) {
            flex: 1;
            &:not(:last-child) {
                border-right: thin dashed #dbdbdb;
            }
        }
    }
}

.left-col {
    width: 250px;
    min-width: 250px;
    max-width: 250px;
}
</style>
