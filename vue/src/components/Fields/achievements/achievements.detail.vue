<template>
    <div v-for="achievement in props.modelValue" :key="achievement.id">
        <v-tooltip :text="achievement.name">
            <template v-slot:activator="{ props }">
                <img v-bind="props" v-if="achievement.rank == 0" src="../../../assets/thumb-turbo.png" height="24" />
                <img v-bind="props" v-if="achievement.rank == 1" src="../../../assets/thumb-bronze.png" height="24" />
                <img v-bind="props" v-if="achievement.rank == 2" src="../../../assets/thumb-silver.png" height="24" />
                <img v-bind="props" v-if="achievement.rank == 3" src="../../../assets/thumb-gold.png" height="24" />
                <img v-bind="props" v-if="achievement.rank == 4" src="../../../assets/thumb-platinum.png" height="24" />
                <img v-bind="props" v-if="achievement.rank == 5" src="../../../assets/thumb-diamond.png" height="24" />
            </template>
        </v-tooltip>
    </div>
</template>

<script setup lang="ts">
import { FieldVardef } from '@/store/modules'

interface Props {
    defs: FieldVardef
    label: string
    modelValue?: any
    data?: any
}

const props = defineProps<Props>()
</script>

<style scoped lang="scss">
label {
    font-size: 12px;
    color: rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity));
}
div {
    display: flex;
    gap: 8px;
    align-items: center;
}
</style>
