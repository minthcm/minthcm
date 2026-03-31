<template>
    <div class="data-workschedule" :style="style">
        {{ languagesStore.translateListValue(props.workschedule.type, 'workschedule_type_list') }}
    </div>
</template>

<script setup lang="ts">
import { useDataPosition } from '@/composables/useDataPosition'
import { useMintScheduler } from '../useMintScheduler'
import { computed } from 'vue'
import { DataWorkschedule } from '../MintScheduler.model'
import { useLanguagesStore } from '@/store/languages'
import { useTheme } from 'vuetify'

interface Props {
    workschedule: DataWorkschedule
    scheduler: ReturnType<typeof useMintScheduler>
}

const props = defineProps<Props>()
const theme = useTheme()

const languagesStore = useLanguagesStore()

const dateFrom = computed(() => props.workschedule.date_start)
const dateTo = computed(() => props.workschedule.date_end)

const color = computed(() => {
    if (!theme.current.value.colors[`workschedule-${props.workschedule.type}`]) {
        return '--v-theme-workschedule-default'
    }
    return `--v-theme-workschedule-${props.workschedule.type}`
})

const position = useDataPosition(
    { dateFrom, dateTo },
    { minDate: props.scheduler.dateBegin, maxDate: props.scheduler.dateEnd },
)

const style = computed(() => {
    return {
        ...position.style.value,
        background: `rgba(var(${color.value}), 0.1)`,
        color: `color-mix(in srgb, rgb(var(${color.value})) 70%, black 30%)`,
        borderColor: `rgb(var(${color.value}))`,
    }
})
</script>

<style scoped lang="scss">
.data-workschedule {
    position: absolute;
    padding: 4px 8px;
    font-size: 10px;
    border-left: 2px solid #888;
    border-right: 2px solid #888;
    letter-spacing: 0.33px;
    height: calc(100% - 8px);
    margin: 0 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    border-radius: 4px;
    transition: all 150ms ease-in-out;
}
</style>
