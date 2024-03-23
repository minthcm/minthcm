<template>
    <a class="mint-url-list-field" target="_blank" :href="props.data.bean[props.defs.name]">
        <span>{{ props.data.bean[props.defs.name] }}</span>
        <v-icon v-if="props.data.bean[props.defs.name]" size="x-small">mdi-open-in-new</v-icon>
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
.mint-url-list-field {
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
    color: rgb(var(--v-theme-secondary));
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 4px;
    width: fit-content;
}
</style>
