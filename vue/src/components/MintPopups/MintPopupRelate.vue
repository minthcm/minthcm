<template>
    <div class="mint-popup-relate">
        <ListView :module="props.data.moduleName" mode="relate" :filters="props.data.filterDefs" />
    </div>
</template>

<script setup lang="ts">
import ListView from '@/views/ListView/ListView.vue'
import { onUnmounted } from 'vue'
import { filterDef } from '@/utils/qsOperatorsTypes'

interface Props {
    data: {
        moduleName: string
        popupMode: string
        filterDefs: filterDef[]
        fieldToNameArray: { [key: string]: string }[]
        onConfirm: (data: any) => void
        onClose: () => void
    }
}

const props = defineProps<Props>()
onUnmounted(() => {
    props.data.onClose()
})
</script>

<style scoped lang="scss">
.mint-popup-relate {
    width: 80vw;
}
</style>
