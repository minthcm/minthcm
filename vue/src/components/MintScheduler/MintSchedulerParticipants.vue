<template>
    <div class="scheduler-participants" @dragover="allowDrop" @drop="onDrop" @dragenter.prevent>
        <template v-if="props.scheduler.participants.value?.length">
            <MintSchedulerRow
                v-for="(participant, index) in props.scheduler.participants.value"
                :key="participant.id"
                :participant="participant"
                :scheduler="props.scheduler"
                :class="{
                    'border-top': true,
                    'border-bottom': index === props.scheduler.participants.value.length - 1,
                }"
            >
                <template #actions>
                    <MintButton
                        v-if="props.scheduler.isEditable.value"
                        @click="props.scheduler.removeParticipant(participant)"
                        :disabled="props.scheduler.participants.value.length <= 1"
                        icon="mdi-delete"
                        variant="text"
                        size="medium"
                    />
                </template>
            </MintSchedulerRow>
        </template>
        <div v-else>{{ language.label('LBL_SCHEDULER_NO_PARTICIPANTS') }}</div>
    </div>
</template>

<script setup lang="ts">
import { useLanguagesStore } from '@/store/languages'
import MintButton from '../MintButtons/MintButton.vue'
import MintSchedulerRow from './MintSchedulerRow.vue'
import { useMintScheduler } from './useMintScheduler'

interface Props {
    scheduler: ReturnType<typeof useMintScheduler>
}

const props = defineProps<Props>()

const language = useLanguagesStore()

function allowDrop(event: DragEvent) {
    event.preventDefault()
}

function onDrop(e: DragEvent) {
    if (!e.dataTransfer?.getData('participant') || !props.scheduler.isEditable.value) {
        return
    }
    const participant = JSON.parse(e.dataTransfer.getData('participant'))
    if (participant?.id) {
        props.scheduler.addParticipant(participant)
    }
}
</script>

<style scoped lang="scss">
.scheduler-participants {
    padding-top: 20px;
}
</style>
