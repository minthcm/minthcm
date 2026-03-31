<template>
    <div class="mint-popup-confirm">
        <span>{{ props.data.text }}</span>
        <div class="mint-popup-confirm-buttons">
            <MintButton @click="handleReject()" :text="languages.label('LBL_CANCEL')" variant="text" />
            <MintButton @click="handleConfirm()" :text="languages.label('LBL_CONFIRM')" variant="primary" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { useLanguagesStore } from '@/store/languages'
import MintButton from '@/components/MintButtons/MintButton.vue'

interface Props {
    data: {
        text: string
        onReject: () => void
        onConfirm: () => void
    }
}

const props = defineProps<Props>()
const emit = defineEmits(['close'])
const languages = useLanguagesStore()

function handleReject() {
    props.data.onReject()
    emit('close')
}

function handleConfirm() {
    props.data.onConfirm()
    emit('close')
}
</script>

<style scoped lang="scss">
.mint-popup-confirm {
    display: flex;
    flex-direction: column;
    gap: 32px;
    min-width: 400px;
    .mint-popup-confirm-buttons {
        display: flex;
        justify-content: space-between;
        border-top: thin solid #0002;
        padding: 16px 0px 0px 0px;
    }
}
</style>
