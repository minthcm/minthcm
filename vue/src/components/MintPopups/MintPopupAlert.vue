<template>
    <div class="mint-popup-alert">
        <span>{{ props.data.text }}</span>
        <div class="mint-popup-alert-buttons">
            <v-btn @click="handleConfirm()" color="secondary">{{ languages.label('LBL_OK') }}</v-btn>
        </div>
    </div>
</template>

<script setup lang="ts">
import { defineProps, defineEmits } from 'vue'
import { useLanguagesStore } from '@/store/languages'

interface Props {
    data: {
        text: string
        onConfirm: () => void
    }
}

const props = defineProps<Props>()
const emit = defineEmits(['close'])
const languages = useLanguagesStore()

function handleConfirm() {
    props.data.onConfirm()
    emit('close')
}
</script>

<style scoped lang="scss">
.mint-popup-alert {
    display: flex;
    flex-direction: column;
    gap: 32px;
    min-width: 400px;
    .mint-popup-alert-buttons {
        display: flex;
        justify-content: flex-end;
        border-top: thin solid #0002;
        padding: 16px 0px 0px 0px;
    }
}
</style>
