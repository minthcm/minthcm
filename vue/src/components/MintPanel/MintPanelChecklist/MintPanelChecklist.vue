<template>
    <div v-if="checklist.length" class="checklist-panel">
        <div class="tabs-container">
            <h1>{{ title }}</h1>
        </div>
        <div class="fields-container">
            <v-list>
                <v-list-item v-for="item in checklist" :key="item.id">
                    <v-checkbox 
                        :label="item.task" 
                        :model-value="item.complete" 
                        :disabled="!isEditable" 
                        @change="toggleItem(item)" 
                    />
                </v-list-item>
            </v-list>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useLanguagesStore } from '@/store/languages'
import { modulesApi } from '@/api/modules.api'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'
import { useStatusBoxesStore } from '@/store/statusBoxes'

interface Props {
    data: {
        title: string
    }
}

const props = defineProps<Props>()
const store = useRecordViewStore()
const languages = useLanguagesStore()

interface ChecklistItem {
    id: string
    task: string
    complete: boolean
}

const checklist = ref<ChecklistItem[]>([])

const title = computed(() => {
    return languages.label(props.data?.title ?? 'LBL_DETAILS', store.bean.module)
})

const isEditable = computed(() => {
    return store.bean.aclAccess.edit
})

async function fetchChecklist() {
    if (store.bean.id == '') {
        checklist.value = []
        return
    }
    const value = await modulesApi.getChecklistItems(store.bean.module, store.bean.id)
    let data = value.data
    let id = 0
    if (typeof data === 'string') {
        try {
            const decoded = data.replace(/&quot;/g, '"').replace(/&#39;/g, "'")
            const parsed = JSON.parse(decoded)
            if (Array.isArray(parsed)) {
                parsed.forEach((item) => {
                    if (!item.id) {
                        item.id = id++
                    } else {
                        id = Math.max(id, item.id) + 1
                    }
                })
                data = parsed
            }
        } catch (e) {
            console.error('Error parsing checklist data', e)
        }
    }
    checklist.value = data
}

async function toggleItem(item: ChecklistItem) {
    item.complete = !item.complete
    const allCompleted = checklist.value.every((i) => i.complete)
    if (allCompleted) {
        store.bean.updateFields({
            status: 'Completed',
        })
    } else {
        store.bean.updateFields({
            status: 'In Progress',
        })
    }
    const checklistJson = JSON.stringify(checklist.value)
    store.bean.updateFields({
        checklist: checklistJson,
    })
    let successfulSave = await store.bean.save()
    if (typeof successfulSave === 'object' && !successfulSave.status) {
        useStatusBoxesStore().showStatus(successfulSave.error || 'Unknown error', {
            type: 'error',
            message: useLanguagesStore().label('LBL_VALIDATION_ERROR', store.bean.module),
            autoClose: true,
        })
    }
}

onMounted(() => {
    fetchChecklist()
})
</script>

<style scoped lang="scss">
.checklist-panel {
    border-radius: 16px;
    background: rgb(var(--v-theme-surface));
    box-shadow: 0px 1px 12px #00997619;

    h1 {
        font-size: 20px;
    }

    .tabs-container {
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 16px;
        border-bottom: 1px solid #dbdbdb;
    }

    .fields-container {
        padding: 0 24px 0 24px;
        display: flex;
        gap: 24px;
    }

    ::v-deep(.v-input__details) {
        display: none;
    }
}
</style>
