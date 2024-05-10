<template>
    <div class="mint-popup-relate">
        <ListView :module="props.data.moduleName" mode="relate" />
    </div>
</template>

<script setup lang="ts">
import ListView from '@/views/ListView/ListView.vue'
import { onUnmounted } from 'vue'

interface Props {
    data: {
        moduleName: string
        popupMode: string
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
