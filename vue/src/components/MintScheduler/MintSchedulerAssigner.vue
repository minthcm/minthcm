<template>
    <div class="scheduler-search-bar">
        <v-text-field
            v-model="searchQuery"
            prepend-inner-icon="mdi-magnify"
            variant="outlined"
            :width="$vuetify.display.mdAndDown ? '95%' : '350px'"
            density="compact"
            :label="language.label('LBL_SEARCH')"
            clearable
            :loading="isSearching"
            color="secondary"
            :messages="
                !isSearching && searchQueryParsed && !visibleParticipants?.length
                    ? language.label('LBL_SCHEDULER_NO_RESULTS')
                    : []
            "
            @keydown.enter="search"
        />
    </div>
    <div>
        <div>
            <MintSchedulerRow
                v-for="(participant, index) in visibleParticipants"
                :key="participant.id"
                :participant="participant"
                :scheduler="props.scheduler"
                draggable
                @dragstart="startDrag($event, participant)"
                :class="{
                    'border-top': true,
                    'border-bottom': index === visibleParticipants.length - 1,
                }"
            >
                <template #actions>
                    <MintButton
                        @click="props.scheduler.addParticipant(participant)"
                        icon="mdi-plus"
                        variant="text"
                        size="medium"
                    />
                </template>
            </MintSchedulerRow>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import { useDebounceFn } from '@vueuse/core'
import MintButton from '../MintButtons/MintButton.vue'
import MintSchedulerRow from './MintSchedulerRow.vue'
import { useMintScheduler } from './useMintScheduler'
import { Participant } from './MintScheduler.model'
import { useLanguagesStore } from '@/store/languages'
import { mintApi } from '@/api/api'
import { AxiosError } from 'axios'

interface Props {
    scheduler: ReturnType<typeof useMintScheduler>
}

const props = defineProps<Props>()

const language = useLanguagesStore()

const searchQuery = ref('')
const searchQueryParsed = computed(() => searchQuery.value?.trim().toLowerCase() ?? '')

const isSearching = ref(false)

const searchDebounce = useDebounceFn(search, 300, { maxWait: 5000 })

watch(searchQueryParsed, () => {
    if (!searchQueryParsed.value) {
        resultParticipants.value = []
    }
    isSearching.value = true
    searchDebounce()
})

const resultParticipants = ref<Participant[]>([])
const visibleParticipants = computed<Participant[]>(() => {
    return resultParticipants.value?.filter(
        (participant) => !props.scheduler.participants.value?.find((p) => p.id === participant.id),
    )
})

let controller: AbortController | null = null
async function search() {
    if (controller) {
        controller.abort()
    }
    if (!searchQueryParsed.value) {
        resultParticipants.value = []
        isSearching.value = false
        return
    }
    isSearching.value = true
    controller = new AbortController()
    try {
        const result = await mintApi.post<Participant[]>(
            '/scheduler',
            {
                date_from: props.scheduler.dateBegin.value,
                date_to: props.scheduler.dateEnd.value,
                search: searchQueryParsed.value,
            },
            {
                signal: controller.signal,
            },
        )
        resultParticipants.value = result.data ?? []
        isSearching.value = false
    } catch (error: unknown) {
        if (error instanceof AxiosError) {
            if (error.name === 'CanceledError') {
                return
            }
        }
        console.error('Failed to search participants', error)
        isSearching.value = false
    }
}

watch([props.scheduler.dateBegin, props.scheduler.dateEnd], () => {
    isSearching.value = true
    searchDebounce()
})

function startDrag(event: DragEvent, participant: Participant) {
    if (event.dataTransfer) {
        event.dataTransfer.setData('participant', JSON.stringify(participant))
    }
}
</script>

<style scoped lang="scss">
.scheduler-search-bar {
    position: relative;
    padding: 32px 0px 8px 0px;
    width: 100%;
    background: rgb(var(--v-theme-background));
}
</style>
