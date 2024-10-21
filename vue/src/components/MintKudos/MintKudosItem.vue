<template>
    <div class="list-wrapper">
        <li class="kudos-item">
            <MintKudosItemHeader :kudos="props.kudos" />
            <v-divider class="my-2" />
            <MintKudosItemMessage :kudos="props.kudos" />
            <MintKudosItemFooter :kudos="props.kudos" />
        </li>
        <div class="d-flex px-3">
            <p class="mr-3 text-caption">{{ date }}</p>
            <MintKudosItemReactions v-if="props.kudos.announced" :kudos="props.kudos" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { DateTime } from 'luxon'
import MintKudosItemHeader from '@/components/MintKudos/MintKudosItemHeader.vue'
import MintKudosItemMessage from '@/components/MintKudos/MintKudosItemMessage.vue'
import MintKudosItemFooter from '@/components/MintKudos/MintKudosItemFooter.vue'
import MintKudosItemReactions from '@/components/MintKudos/MintKudosItemReactions.vue'
import { useLanguagesStore } from '@/store/languages'

const props = defineProps(['kudos'])
const languages = useLanguagesStore()
const date = computed(() => {
    return props.kudos.announcement_date
        ? DateTime.fromSQL(props.kudos.announcement_date).toRelative()
        : languages.label('LBL_KUDOS_UNPUBLISHED')
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
    background-color: #f5fbfa;
}
</style>
