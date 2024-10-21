<template>
    <p v-if="props.kudos.description">
        {{ props.kudos.description }}
    </p>
    <div v-else class="caption private">
        <v-icon icon="mdi-eye-off" class="mr-2" />
        <span v-html="privateKudosnotification" />
    </div>
</template>

<script setup lang="ts">
import { useLanguagesStore } from '@/store/languages'
import { computed } from 'vue'

const props = defineProps(['kudos'])
const languages = useLanguagesStore()

const privateKudosnotification = computed(() =>
    languages.label('LBL_KUDOS_PRIVATE_TEXT_ON_MESSAGE', undefined, {
        name: props.kudos.employee.first_name ? props.kudos.employee.first_name : props.kudos.employee.full_name,
    }),
)
</script>

<style scoped lang="scss">
.private {
    display: flex;
    color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
}
</style>
