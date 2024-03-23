<template>
    <div>
        <v-chip
            v-if="props.defs?.options_colors"
            :color="languages.translateListValue(props.data.bean[props.defs.name], props.defs?.options_colors)"
        >
            {{ languages.translateListValue(props.data.bean[props.defs.name], props.defs?.options) }}
        </v-chip>
        <div v-else>{{ languages.translateListValue(props.data.bean[props.defs.name], props.defs?.options) }}</div>
    </div>
</template>

<script setup lang="ts">
import { FieldVardef } from '@/store/modules'
import { useLanguagesStore } from '@/store/languages'

interface Props {
    defs: FieldVardef
    data?: any
}

const props = defineProps<Props>()
const languages = useLanguagesStore()
</script>

<style scoped lang="scss">
:deep(.v-chip.v-chip--size-default) {
    height: 28px;
    border-radius: 4px;
    letter-spacing: 0.09px;
}
</style>
