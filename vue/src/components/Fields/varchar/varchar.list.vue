<template>
    <span>{{ props.data.bean[defs.name] }}</span>
</template>

<script setup lang="ts">
import { FieldVardef } from '@/store/modules'

interface Props {
    defs: FieldVardef
    data?: any
}

const props = defineProps<Props>()
</script>

<style scoped lang="scss"></style>
