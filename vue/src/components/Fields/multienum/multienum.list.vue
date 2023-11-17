<template>
    <h2>{{ props.data.bean[defs.name] }}</h2>
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

</style>
