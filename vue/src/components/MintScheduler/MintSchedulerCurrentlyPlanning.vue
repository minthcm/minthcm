<template>
    <div
        :class="{
            'currently-planning': true,
            disabled: !props.scheduler.isEditable.value,
        }"
        ref="currently-planning"
        :style="{
            ...style,
            pointerEvents: props.scheduler.bean.isSaving ? 'none' : 'auto',
        }"
    >
        <div class="currently-planning-header">
            <div v-show="props.scheduler.isEditable.value" class="currently-planning-edge left" />
            <div v-show="props.scheduler.isEditable.value" class="currently-planning-edge right" />
            <div class="currently-planning-header-content">
                <div class="currently-planning-header-time">{{ currentlyPlanningTimeText }}</div>
                <div class="currently-planning-header-title">{{ props.scheduler.bean.fields.name?.model }} ({{ language.label('LBL_SCHEDULER_CURRENTLY_PLANNING') }})</div>
            </div>
            <v-fade-transition>
                <v-progress-circular
                    v-if="props.scheduler.bean.isSaving"
                    size="16"
                    width="2"
                    class="mr-2"
                    indeterminate
                    color="on-activity"
                />
            </v-fade-transition>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useDataPosition } from '@/composables/useDataPosition'
import { useMintScheduler } from './useMintScheduler'
import { computed, ref, useTemplateRef, watch } from 'vue'
import { DateTime } from 'luxon'
import { useMove } from '@/composables/useMove'
import { useLanguagesStore } from '@/store/languages'
import { usePreferencesStore } from '@/store/preferences'
import { useStatusBoxesStore } from '@/store/statusBoxes'

interface Props {
    scheduler: ReturnType<typeof useMintScheduler>
}

const props = defineProps<Props>()

const language = useLanguagesStore()
const preferences = usePreferencesStore()

// date from
const dateFrom = ref(props.scheduler.activityFrom.value)
const dateFromDt = computed(() => DateTime.fromSQL(dateFrom.value, { zone: 'UTC' }))
const positionDtFrom = computed(() => {
    let dt = dateFromDt.value
    if (!dt || !dt.isValid) {
        return null
    }
    if (move.movedSteps.value && ['both', 'left'].includes(move.moveEdge.value)) {
        dt = dt.plus({ minutes: move.movedSteps.value * 15 })
    }
    if (move.moveEdge.value == 'left' && dt >= dateToDt.value) {
        dt = dateToDt.value.minus({ minutes: 15 })
    }
    return dt
})
const positionDateFrom = computed(() => {
    return positionDtFrom.value?.toFormat('yyyy-MM-dd HH:mm:ss') ?? ''
})
watch(
    () => props.scheduler.activityFrom.value,
    (newVal) => {
        dateFrom.value = newVal
    },
)

// date to
const dateTo = ref(props.scheduler.activityTo.value)
const dateToDt = computed(() => DateTime.fromSQL(dateTo.value, { zone: 'UTC' }))
const positionDtTo = computed(() => {
    let dt = dateToDt.value
    if (!dt || !dt.isValid) {
        return null
    }
    if (move.movedSteps.value && ['both', 'right'].includes(move.moveEdge.value)) {
        dt = dt.plus({ minutes: move.movedSteps.value * 15 })
    }
    if (move.moveEdge.value == 'right' && dt <= dateFromDt.value) {
        dt = dateFromDt.value.plus({ minutes: 15 })
    }
    return dt
})
const positionDateTo = computed(() => {
    return positionDtTo.value?.toFormat('yyyy-MM-dd HH:mm:ss') ?? ''
})
watch(
    () => props.scheduler.activityTo.value,
    (newVal) => {
        dateTo.value = newVal
    },
)

const updatedFields = computed(() => {
    if (!positionDtFrom.value?.isValid || !positionDtTo.value?.isValid || !move.movedSteps.value) {
        return null
    }
    return {
        date_start: positionDateFrom.value,
        date_end: positionDateTo.value,
        duration_hours: Math.floor(positionDtTo.value.diff(positionDtFrom.value, 'hours').hours),
        duration_minutes: Math.floor(positionDtTo.value.diff(positionDtFrom.value, 'minutes').minutes % 60),
    }
})

const steps = computed(() => {
    return props.scheduler.schedulerHours.value.length * 4
})
const move = useMove({
    element: useTemplateRef<HTMLElement>('currently-planning'),
    steps,
    handleSelector: '.currently-planning-header',
    leftHandleSelector: '.currently-planning-edge.left',
    rightHandleSelector: '.currently-planning-edge.right',
    onMoveEnd: () => {
        if (!updatedFields.value || !props.scheduler.isEditable.value) {
            return
        }
        const date_start = updatedFields.value.date_start
        const date_end = updatedFields.value.date_end
        if (props.scheduler.bean.fields.date_start?.model) {
            props.scheduler.bean.fields.date_start.model.set(date_start)
        }
        if (props.scheduler.bean.fields.date_end?.model) {
            props.scheduler.bean.fields.date_end.model.set(date_end)
        }
        if (props.scheduler.bean.fields.duration_hours) {
            props.scheduler.bean.fields.duration_hours.model = updatedFields.value.duration_hours
        }
        if (props.scheduler.bean.fields.duration_minutes) {
            props.scheduler.bean.fields.duration_minutes.model = updatedFields.value.duration_minutes
        }
        if (props.scheduler.bean.id) {
            props.scheduler.bean.save().then(() => {
                useStatusBoxesStore().showStatus('scheduler-save-success', {
                    type: 'success',
                    autoClose: true,
                    autoCloseDelay: 3000,
                    message: language.label('LBL_SAVED'),
                })
            })
        }
    },
})

const { style } = useDataPosition(
    { dateFrom: positionDateFrom, dateTo: positionDateTo },
    { minDate: props.scheduler.dateBegin, maxDate: props.scheduler.dateEnd },
)

const currentlyPlanningTimeText = computed(() => {
    const text = []
    if (positionDtFrom.value?.isValid) {
        text.push(positionDtFrom.value.setZone(preferences.user?.timezone).toFormat('H:mm'))
    }
    if (positionDtTo.value?.isValid) {
        text.push(positionDtTo.value.setZone(preferences.user?.timezone).toFormat('H:mm'))
    }
    return text.join(' - ')
})
</script>

<style scoped lang="scss">
.currently-planning {
    position: absolute;
    background: rgba(var(--v-theme-activity), 0.1);
    border: thin solid rgb(var(--v-theme-activity)) !important;
    color: rgb(var(--v-theme-on-activity));
    font-size: 10px;
    letter-spacing: 0.33px;
    top: -40px;
    bottom: 1px;
    line-height: 12px;
    border-radius: 4px;
    margin: 0 2px;
    transition: all 120ms ease-out;

    &.disabled {
        pointer-events: none !important;
    }

    .currently-planning-header {
        background: rgb(var(--v-theme-activity));
        display: flex;
        flex-direction: row;
        align-items: center;
        height: min-content;
        padding: 0;
        width: 100%;
        user-select: none;
        -webkit-user-select: none;
        position: relative;
        cursor: grab;

        .currently-planning-header-content {
            display: flex;
            flex-direction: column;
            height: 100%;
            padding: 1px 2px 3px 2px;
            overflow: hidden;
            min-width: 0;
            width: 100%;
        }
        .currently-planning-header-time {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .currently-planning-header-title {
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    }

    .currently-planning-edge {
        width: 16px;
        position: absolute;
        top: 0px;
        bottom: 0px;
        cursor: ew-resize;
        &.left {
            left: -7px;
        }
        &.right {
            right: -7px;
        }
    }
}
</style>
