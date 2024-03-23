<template>
    <a class="mint-email-list-field" :href="`mailto:${props.data.bean[props.defs.name]}`">
        <v-icon size="x-small" v-if="props.data.bean[props.defs.name]">mdi-email</v-icon>
        <span>{{ props.data.bean[props.defs.name] }}</span>
    </a>
</template>

<script setup lang="ts">
import { FieldVardef } from '@/store/modules'

interface Props {
    defs: FieldVardef
    data?: any
}

const props = defineProps<Props>()
</script>

<style scoped lang="scss">
.mint-email-list-field {
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
    color: rgb(var(--v-theme-secondary));
    text-decoration: none;
    display: flex;
    align-items: baseline;
    gap: 6px;
    width: fit-content;

    .v-icon {
        top: 1px;
    }
}
</style>
