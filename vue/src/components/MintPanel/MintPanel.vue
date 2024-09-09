<template>
    <keep-alive>
        <component
            :is="PanelComponent"
            :data="props.data"
        />
    </keep-alive>
</template>

<script setup lang="ts">
import { defineProps, computed, defineAsyncComponent } from 'vue'

interface Props {
    component: string
    data: any
}

const props = defineProps<Props>()
const PanelComponent = defineAsyncComponent(() => import(`@/components/MintPanel/${props.component}/${props.component}.vue`))
</script>

<style scoped lang="scss">

</style>
