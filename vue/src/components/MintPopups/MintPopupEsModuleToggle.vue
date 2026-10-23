<template>
    <div class="mint-popup-es-toggle">
        <v-checkbox
            v-if="props.data.showCheckbox"
            v-model="enableNewListView"
            :label="props.data.checkboxLabel ?? languages.label('LBL_ENABLE_NEW_LIST_VIEW', 'Administration')"
        />
        <div class="buttons">
            <MintButton
                variant="text"
                :text="languages.label('LBL_CANCEL_BUTTON_LABEL', 'Administration')"
                @click="handleCancel"
            />
            <MintButton
                variant="primary"
                :text="languages.label('LBL_SAVE_BUTTON_LABEL', 'Administration')"
                icon="mdi-check"
                @click="handleConfirm"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useLanguagesStore } from '@/store/languages'
import MintButton from '@/components/MintButtons/MintButton.vue'

interface Props {
    data: {
        showCheckbox: boolean
        checkboxLabel?: string
        onConfirm: (checkboxValue: boolean) => void
        onCancel: () => void
    }
}

const props = defineProps<Props>()
const emit = defineEmits(['close'])
const languages = useLanguagesStore()

const enableNewListView = ref(false)

function handleCancel() {
    props.data.onCancel()
    emit('close')
}

function handleConfirm() {
    props.data.onConfirm(enableNewListView.value)
}
</script>

<style scoped lang="scss">
.mint-popup-es-toggle {
    min-width: 360px;
    display: flex;
    flex-direction: column;
    gap: 8px;

    .buttons {
        display: flex;
        justify-content: space-between;
        border-top: thin solid #0002;
        padding: 16px 0px 0px 0px;
    }
}
</style>
