<template>
    <div class="columns-popup">
        <div class="columns">
            <div class="column">
                <span v-text="languages.label('LBL_ESLIST_VISIBLE_COLUMNS')" />
                <div
                    class="columns-container"
                    @dragover.prevent="onVisibleColumnsDragOver"
                    @dragenter.prevent
                    @drop="onDrop($event, 'visible-columns')"
                >
                    <div
                        v-for="col in visibleColumns"
                        :key="col.name"
                        class="column-chip visible"
                        :class="{ dragged: col.name === draggedColumnName }"
                        draggable="true"
                        @dragstart="startDrag($event, col.name)"
                    >
                        <span v-text="col.label" />
                        <v-btn
                            @click="moveColumnToHidden(col.name)"
                            icon="mdi-minus"
                            density="compact"
                            variant="text"
                        />
                    </div>
                </div>
            </div>
            <div class="column">
                <span v-text="languages.label('LBL_ESLIST_HIDDEN_COLUMNS')" />
                <div
                    class="columns-container"
                    @dragover.prevent
                    @dragenter.prevent="onHiddenColumnsDragEnter"
                    @drop="onDrop($event, 'hidden-columns')"
                >
                    <div
                        v-for="col in hiddenColumns"
                        :key="col.name"
                        class="column-chip"
                        :class="{ dragged: col.name === draggedColumnName }"
                        draggable="true"
                        @dragstart="startDrag($event, col.name)"
                    >
                        <span v-text="col.label" />
                        <v-btn
                            @click="moveColumnToVisible(col.name)"
                            icon="mdi-plus"
                            density="compact"
                            variant="text"
                        />
                    </div>
                </div>
                <v-text-field
                    v-model="columnsSearchPhrase"
                    ref="filterInput"
                    class="mt-4"
                    variant="outlined"
                    density="compact"
                    hide-details
                    :label="languages.label('LBL_ESLIST_FILTER')"
                />
            </div>
        </div>
        <div class="buttons mt-4">
            <MintButton
                @click="$emit('close')"
                variant="text"
                :text="languages.label('LBL_ESLIST_CANCEL')"
            />
            <MintButton
                @click="setDefaultColumns"
                class="ms-auto"
                variant="text"
                :text="languages.label('LBL_ESLIST_DEFAULT')"
            />
            <MintButton
                @click="applyColumns"
                variant="primary"
                :text="languages.label('LBL_ESLIST_SAVE')"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed, defineEmits } from 'vue'
import MintButton from '@/components/MintButtons/MintButton.vue'
import { useLanguagesStore } from '@/store/languages'
import { useListViewStore } from './ListViewStore'

const emit = defineEmits(['close'])

const store = useListViewStore()
const languages = useLanguagesStore()
const visibleColumns = ref([])
const draggedColumnName = ref('')
const columnsSearchPhrase = ref('')

onMounted(() => {
    visibleColumns.value = [...store.visibleColumns]
})

const hiddenColumns = computed(() => {
    return store.allColumns
        .filter((col) => !visibleColumns.value.find((c) => c.name === col.name))
        .filter(
            (col) =>
                !columnsSearchPhrase.value ||
                col.label.toLowerCase().includes(columnsSearchPhrase.value.toLowerCase()),
        )
})

function setDefaultColumns() {
    store.setDefaultColumns()
    store.savePreferences()
    emit('close')
}

function applyColumns() {
    store.preferences.columns = visibleColumns.value.map((col) => col.name)
    store.savePreferences()
    emit('close')
}

function moveColumnToHidden(colName: string) {
    visibleColumns.value = visibleColumns.value.filter(
        (c) => c.name !== colName,
    )
}

function moveColumnToVisible(colName: string) {
    if (!visibleColumns.value.find((c) => c.name === colName)) {
        visibleColumns.value.push(
            store.allColumns.find((c) => c.name === colName),
        )
    }
}

function onVisibleColumnsDragOver(e) {
    moveColumnToVisible(draggedColumnName.value)
    const path = e.composedPath()
    if (
        path[0] &&
        path[0].classList.contains('column-chip') &&
        !path[0].classList.contains('dragged')
    ) {
        moveColumnToHidden(draggedColumnName.value)
        const index = [...path[0].parentNode.children].indexOf(path[0])
        visibleColumns.value.splice(
            index,
            0,
            store.allColumns.find((c) => c.name === draggedColumnName.value),
        )
    }
}

function onHiddenColumnsDragEnter() {
    moveColumnToHidden(draggedColumnName.value)
}

function startDrag(e: DragEvent, colName: string) {
    if (e.dataTransfer) {
        e.dataTransfer.dropEffect = 'move'
        e.dataTransfer.effectAllowed = 'move'
        e.dataTransfer.setData('colName', colName)
        draggedColumnName.value = colName
    }
}

function onDrop(e, list) {
    draggedColumnName.value = null
    const colName = e.dataTransfer.getData('colName')
    if (list === 'hidden-columns') {
        moveColumnToHidden(colName)
    } else if (list === 'visible-columns') {
        moveColumnToVisible(colName)
    }
}
</script>

<style scoped lang="scss">
.columns-popup {
    display: flex;
    flex-direction: column;
    min-width: 700px;

    .columns {
        display: flex;
        gap: 16px;
        justify-content: space-between;

        .column {
            width: 100%;
            & > span {
                user-select: none;
            }
        }

        .columns-container {
            border: thin solid #0003;
            border-radius: 2px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            box-shadow: 0 2px 6px #0003;
            height: 350px;
            overflow: auto;

            .column-chip {
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-radius: 100px;
                background: #ddd;
                color: black;
                padding: 4px 12px 4px 16px;
                cursor: grab;
                user-select: none;
                box-shadow: 0 1px 3px #0003;
                transform: translate(
                    0,
                    0
                ); /* trick to get rid of white corners during drag */
                opacity: 0.92;
                transition: opacity 200ms;

                &:hover {
                    opacity: 1;
                }
                &.visible {
                    background: rgb(var(--v-theme-secondary));
                    color: rgba(
                        var(--v-theme-surface),
                        var(--v-high-emphasis-opacity)
                    );
                }
                &.dragged {
                    opacity: 0.5;
                }
            }
        }
    }

    .buttons {
        display: flex;
        width: 100%;
        gap: 16px;
        justify-content: space-between;
    }
}
</style>
