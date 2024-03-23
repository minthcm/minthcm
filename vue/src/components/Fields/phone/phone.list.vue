<template>
    <a class="mint-phone-list-field" :href="`tel:${props.data.bean[props.defs.name]}`">
        <v-icon size="x-small" v-if="props.data.bean[props.defs.name]">mdi-phone</v-icon>
        {{ props.data.bean[props.defs.name] }}
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
.mint-phone-list-field {
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
    color: rgb(var(--v-theme-secondary));
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 4px;
}
</style>
