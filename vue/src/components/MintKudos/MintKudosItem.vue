<template>
    <div class="list-wrapper">
        <li class="kudos-item" ref="target">
            <MintKudosItemHeader :kudos="props.kudos" />
            <v-divider class="my-2" />
            <MintKudosItemMessage :kudos="props.kudos" />
            <MintKudosItemFooter :kudos="props.kudos" />
        </li>
        <div class="d-flex px-3">
            <v-tooltip :text="tooltipdate" v-if="props.kudos.announcement_date" location="start">
                <template v-slot:activator="{ props }">
                    <p class="mr-3 text-caption" v-bind="props">{{ date }}</p>
                </template>
            </v-tooltip>
            <MintKudosItemReactions v-if="props.kudos.announced" :kudos="props.kudos" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, ref, useTemplateRef, watch } from 'vue'
import { DateTime } from 'luxon'
import MintKudosItemHeader from '@/components/MintKudos/MintKudosItemHeader.vue'
import MintKudosItemMessage from '@/components/MintKudos/MintKudosItemMessage.vue'
import MintKudosItemFooter from '@/components/MintKudos/MintKudosItemFooter.vue'
import MintKudosItemReactions from '@/components/MintKudos/MintKudosItemReactions.vue'
import { useLanguagesStore } from '@/store/languages'
import { useBackendStore } from '@/store/backend'
import { useIntersectionObserver } from '@vueuse/core'
import { useMintKudosStore } from './MintKudosStore'

const props = defineProps(['kudos'])
const languages = useLanguagesStore()
const backend = useBackendStore()
const store = useMintKudosStore()

const date = computed(() => {
    return props.kudos.announcement_date
        ? DateTime.fromSQL(props.kudos.announcement_date).toRelative()
        : languages.label('LBL_KUDOS_UNPUBLISHED')
})
const tooltipdate = computed(() => {
    const format = {
        date: backend.initData?.preferences?.date_format ?? 'dd/MM/y',
        time: backend.initData?.preferences?.time_format ?? 'H:mm',
    }
    return props.kudos.announcement_date
        ? DateTime
            .fromSQL(props.kudos.announcement_date)
            .toFormat(format.date + ' ' + format.time)
        : props.kudos.announcement_date
})

const target = useTemplateRef<HTMLDivElement>('target')
const targetIsVisible = ref(false)

useIntersectionObserver(target, ([entry]) => {
    targetIsVisible.value = entry?.isIntersecting || false
})

watch(targetIsVisible, (isVisible) => {
    if (isVisible && props.kudos.is_read == 0) {
        store.readKudosAlerts(props.kudos)
    }
})
</script>

<style scoped lang="scss">
.list-wrapper {
    padding: 16px;
}
.kudos-item {
    padding: 12px;
    margin-bottom: 4px;
    font-size: 14px;
    border-radius: 16px;
    color: rgba(var(--v-theme-on-surface), var(--v-hard-emphasis-opacity));
    background-color: rgb(var(--v-theme-primary-lighter));
}
</style>
