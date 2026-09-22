<template>
    <div class="mint-calendar-day-grid">
        <div v-for="(label, hour) in hourLabels" :key="hour" class="mint-calendar-day-grid__hour-row">
            <span class="mint-calendar-day-grid__hour-label">{{ label }}</span>
        </div>

        <div
            v-for="ws in timedWorkschedules"
            :key="ws.id"
            class="mint-calendar-day-grid__workschedule-band"
            :style="ws.style"
        >
            {{ ws.label }}
        </div>

        <div
            v-if="nowOffset !== null"
            class="mint-calendar-day-grid__now-line"
            :style="{ top: nowOffset + 'px' }"
        />

        <div class="mint-calendar-day-grid__activities-layer">
            <component
                :is="activity.hasAccess ? 'router-link' : 'div'"
                v-for="activity in positionedActivities"
                :key="activity.id"
                :to="activity.hasAccess ? activity.url : undefined"
                target="_blank"
                rel="noopener noreferrer"
                class="mint-calendar-day-grid__activity-block"
                :style="activity.style"
                :title="activity.tooltip"
            >
                <v-icon :icon="activity.icon" size="10" />
                <span class="mint-calendar-day-grid__activity-time">{{ activity.timeLabel }}</span>
                <span class="mint-calendar-day-grid__activity-name">{{ activity.name }}</span>
            </component>
        </div>
    </div>
</template>

<script setup lang="ts">
interface TimedWorkschedule {
    id: string
    label: string
    style: Record<string, string>
}

interface PositionedActivity {
    id: string
    name: string
    hasAccess: boolean
    url: string
    icon: string
    timeLabel: string
    tooltip: string
    style: Record<string, string>
}

defineProps<{
    hourLabels: string[]
    timedWorkschedules: TimedWorkschedule[]
    positionedActivities: PositionedActivity[]
    nowOffset: number | null
}>()
</script>

<style scoped lang="scss">
.mint-calendar-day-grid {
    position: relative;

    &__hour-row {
        display: flex;
        height: 44px;
        border-top: 1px solid rgba(var(--v-theme-on-surface), 0.08);

        &:first-child {
            border-top: none;
        }
    }

    &__hour-label {
        width: 36px;
        min-width: 36px;
        padding-right: 6px;
        text-align: right;
        font-size: 10px;
        line-height: 1;
        color: rgba(var(--v-theme-on-surface), 0.4);
        transform: translateY(-5px);
        user-select: none;
    }

    &__workschedule-band {
        position: absolute;
        left: 42px;
        right: 2px;
        border-radius: 4px;
        border-left: 3px solid transparent;
        font-size: 10px;
        font-weight: 600;
        padding: 2px 6px;
        box-sizing: border-box;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    &__now-line {
        position: absolute;
        left: 42px;
        right: 0;
        border-top: 2px solid rgb(var(--v-theme-error));
        z-index: 3;

        &::before {
            content: '';
            position: absolute;
            left: -4px;
            top: -4px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgb(var(--v-theme-error));
        }
    }

    &__activities-layer {
        position: absolute;
        top: 0;
        left: 42px;
        right: 2px;
        height: 100%;
    }

    &__activity-block {
        position: absolute;
        display: flex;
        align-items: center;
        gap: 3px;
        box-sizing: border-box;
        background: rgb(var(--v-theme-activity));
        color: rgb(var(--v-theme-on-activity));
        border-radius: 4px;
        padding: 2px 5px;
        font-size: 10px;
        line-height: 1.2;
        box-shadow: 0px 1px 4px #00000029;
        text-decoration: none;
        overflow: hidden;
        z-index: 2;
        transition: box-shadow 150ms ease-in-out;

        &:hover {
            z-index: 5;
            box-shadow: 0px 3px 8px #00000040;
        }
    }

    &__activity-time {
        font-weight: 600;
        opacity: 0.85;
        white-space: nowrap;
    }

    &__activity-name {
        flex: 1;
        min-width: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-weight: 600;
    }
}
</style>
