<template>
    <v-icon class="mint-bool-field" :icon="parsedBool ? 'mdi-check' : 'mdi-close'" />
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { FieldVardef } from '@/store/modules'

interface Props {
    defs: FieldVardef
    data?: any
}

const props = defineProps<Props>()

const parsedBool = computed<boolean>(() => {
    //TODO: 'false', empty array, trim ...
    return !!props.data.bean[props.defs.name]
})
</script>

<style scoped lang="scss">
.mint-bool-field {
    color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
}
</style>
