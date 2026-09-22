<template>
    <div class="mass-acceptance-popup">
        <div v-if="!data.done">
            <p class="mb-3">{{ progressText }}</p>
            <v-progress-linear
                :model-value="progress"
                color="primary"
                height="12"
                rounded
                striped
            />
            <p class="mt-2 text-caption text-medium-emphasis">{{ data.processed }} / {{ data.total }}</p>
        </div>
        <div v-else>
            <p class="mb-3">{{ summaryText }}</p>
            <v-btn color="primary" variant="tonal" block @click="data.onConfirm()">
                {{ languages.label('LBL_OK') }}
            </v-btn>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useLanguagesStore } from '@/store/languages'

interface Props {
    data: {
        processed: number
        total: number
        accepted: number
        skipped: number
        done: boolean
        module: string
        onConfirm: () => void
    }
}

const props = defineProps<Props>()
const languages = useLanguagesStore()

const progress = computed(() => {
    if (props.data.total === 0) {
        return 100
    }
    return Math.round((props.data.processed / props.data.total) * 100)
})

const progressText = computed(() => {
    const tpl = languages.label('LBL_MASSACCEPTANCE_POPUP_TEXT', props.data.module)
    return tpl
        .replace('{current}', String(props.data.processed))
        .replace('{total}', String(props.data.total))
})

const summaryText = computed(() => {
    const tpl = languages.label('LBL_MASSACCEPTANCE_SUMMARY', props.data.module)
    return tpl
        .replace('{accepted}', String(props.data.accepted))
        .replace('{skipped}', String(props.data.skipped))
})
</script>

<style scoped lang="scss">
.mass-acceptance-popup {
    min-width: 320px;
}
</style>
