<template>
    <div class="mint-sidepanel" :style="{ width: props.width + 'px' }">
        <div v-if="!isReady" class="mint-sidepanel__loader">
            <v-progress-circular indeterminate color="primary" size="36" width="3" />
        </div>
        <template v-else>
            <div
                v-for="(widget, index) in orderedWidgets"
                :key="widget"
                class="mint-sidepanel__widget"
                :class="{
                    'mint-sidepanel__widget--drag-over': dragOverIndex === index,
                    'mint-sidepanel__widget--dragging': dragIndex === index,
                    'mint-sidepanel__widget--collapsed': collapsedWidgets[widget],
                }"
                :draggable="draggableIndex === index"
                @mousedown="onMouseDown(index, $event)"
                @dragstart="onDragStart(index, $event)"
                @dragover.prevent="onDragOver(index)"
                @dragleave="onDragLeave"
                @drop.prevent="onDrop(index)"
                @dragend="onDragEnd"
            >
                <div class="mint-sidepanel__controls">
                    <div
                        class="mint-sidepanel__collapse-btn"
                        :title="
                            collapsedWidgets[widget] ? languages.label('LBL_EXPAND') : languages.label('LBL_COLLAPSE')
                        "
                        @click.stop="toggleCollapse(widget)"
                    >
                        <v-icon :icon="collapsedWidgets[widget] ? 'mdi-chevron-down' : 'mdi-chevron-up'" size="18" />
                    </div>
                </div>
                <component v-if="!collapsedWidgets[widget] || everExpanded[widget]" :is="widgetComponents[widget]" />
            </div>
            <slot />
        </template>
    </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useLanguagesStore } from '@/store/languages'
import MintWidgetTenure from './MintWidgetTenure.vue'
import MintWidgetLeave from './MintWidgetLeave.vue'
import MintWidgetCalendar from './MintWidgetCalendar.vue'
import MintWidgetTrainings from './MintWidgetTrainings.vue'
import MintWidgetCompetencies from './MintWidgetCompetencies.vue'
import MintWidgetKudos from './MintWidgetKudos.vue'
import MintWidgetRecruitmentStage from './MintWidgetRecruitmentStage.vue'
import MintWidgetCandidateScore from './MintWidgetCandidateScore.vue'
import MintWidgetApplicationTimeline from './MintWidgetApplicationTimeline.vue'
import MintWidgetCandidatureStatuses from './MintWidgetCandidatureStatuses.vue'

const props = defineProps<{
    widgets: string[]
    width: number
}>()

const widgetComponents: Record<string, unknown> = {
    MintWidgetTenure,
    MintWidgetLeave,
    MintWidgetCalendar,
    MintWidgetTrainings,
    MintWidgetCompetencies,
    MintWidgetKudos,
    MintWidgetRecruitmentStage,
    MintWidgetCandidateScore,
    MintWidgetApplicationTimeline,
    MintWidgetCandidatureStatuses,
}

function getStorageKey(): string {
    const module = typeof route.params.module === 'string' ? route.params.module : 'default'
    return 'mint-sidepanel-widget-order-' + module
}

function getStorageKeyCollapsed(): string {
    const module = typeof route.params.module === 'string' ? route.params.module : 'default'
    return 'mint-sidepanel-widget-collapsed-' + module
}

const languages = useLanguagesStore()
const route = useRoute()
const isReady = ref(false)
const orderedWidgets = ref<string[]>([])
const collapsedWidgets = ref<Record<string, boolean>>({})
const everExpanded = ref<Record<string, boolean>>({})

function loadOrder() {
    try {
        const saved = localStorage.getItem(getStorageKey())
        if (saved) {
            const parsed: string[] = JSON.parse(saved)
            const valid = parsed.filter((w) => props.widgets.includes(w))
            const missing = props.widgets.filter((w) => !valid.includes(w))
            orderedWidgets.value = [...valid, ...missing]
            return
        }
    } catch {}
    orderedWidgets.value = [...props.widgets]
}

function saveOrder() {
    localStorage.setItem(getStorageKey(), JSON.stringify(orderedWidgets.value))
}

function loadCollapsed() {
    try {
        const saved = localStorage.getItem(getStorageKeyCollapsed())
        if (saved) {
            collapsedWidgets.value = JSON.parse(saved)
        }
    } catch {}
}

function toggleCollapse(widget: string) {
    const nowCollapsed = !collapsedWidgets.value[widget]
    collapsedWidgets.value = { ...collapsedWidgets.value, [widget]: nowCollapsed }
    if (!nowCollapsed) everExpanded.value = { ...everExpanded.value, [widget]: true }
    localStorage.setItem(getStorageKeyCollapsed(), JSON.stringify(collapsedWidgets.value))
}

// orderedWidgets must be ready before isReady=true
loadOrder()
loadCollapsed()

// Widgets that start expanded are immediately allowed to mount
orderedWidgets.value.forEach((w) => {
    if (!collapsedWidgets.value[w]) everExpanded.value[w] = true
})

// Re-sync when the widget list changes (e.g. after defs load)
watch(() => props.widgets, loadOrder)

languages
    .fetchModuleLanguage(typeof route.params.module === 'string' ? route.params.module : 'Employees')
    .catch(() => {})
    .finally(() => {
        isReady.value = true
    })

// Drag-and-drop
const dragIndex = ref<number | null>(null)
const dragOverIndex = ref<number | null>(null)
const draggableIndex = ref<number | null>(null)

function onMouseDown(index: number, e: MouseEvent) {
    const target = e.target as HTMLElement
    draggableIndex.value = target.closest('[class*="__title"]') !== null ? index : null
}

function onDragStart(index: number, e: DragEvent) {
    dragIndex.value = index
    if (e.dataTransfer) e.dataTransfer.effectAllowed = 'move'
}

function onDragOver(index: number) {
    dragOverIndex.value = index
}

function onDragLeave() {
    dragOverIndex.value = null
}

function onDrop(targetIndex: number) {
    if (dragIndex.value === null || dragIndex.value === targetIndex) return
    const list = [...orderedWidgets.value]
    const [moved] = list.splice(dragIndex.value, 1)
    list.splice(targetIndex, 0, moved)
    orderedWidgets.value = list
    saveOrder()
    dragOverIndex.value = null
}

function onDragEnd() {
    dragIndex.value = null
    dragOverIndex.value = null
    draggableIndex.value = null
}
</script>

<style scoped lang="scss">
.mint-sidepanel {
    flex-shrink: 0;
    align-self: flex-start;
    position: relative;
    top: 32px;
    display: flex;
    flex-direction: column;
    gap: 16px;

    &__widget {
        position: relative;
        transition: opacity 0.15s;

        &--dragging {
            opacity: 0.4;
        }

        &--drag-over {
            outline: 2px dashed rgb(var(--v-theme-primary));
            outline-offset: 4px;
            border-radius: 16px;
        }

        &--collapsed {
            :deep([class$='__content']) {
                display: none !important;
            }
        }

        :deep([class$='__title']) {
            cursor: grab;
            border-bottom: 1px solid rgba(0, 0, 0, 0.09);
            padding-bottom: 10px;
            margin-bottom: 10px !important;
        }
    }

    &__controls {
        position: absolute;
        top: 8px;
        right: 8px;
        display: flex;
        gap: 2px;
        z-index: 1;
    }

    &__collapse-btn {
        cursor: pointer;
        color: rgba(0, 0, 0, 0.25);
        line-height: 1;
        transition: color 0.15s;

        &:hover {
            color: rgba(0, 0, 0, 0.55);
        }
    }

    &__loader {
        display: flex;
        justify-content: center;
        padding: 32px 0;
    }
}
</style>

<style>
@keyframes mint-widget-fadein {
    from {
        opacity: 0;
        transform: translateY(4px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
