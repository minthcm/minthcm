<template>
    <div class="mint-popup-confirm">
        <div class="mint-popup-confirm-body" :class="{ 'is-submitting': submitting }">
            <span>{{ props.data.text }}</span>
            <div class="mint-popup-confirm-buttons">
                <MintButton @click="handleReject()" :disabled="submitting" :text="props.data.cancelLabel ?? languages.label('LBL_CANCEL')" variant="text" />
                <MintButton @click="handleConfirm()" :disabled="submitting" :text="props.data.confirmLabel ?? languages.label('LBL_CONFIRM')" variant="primary" />
            </div>
        </div>
        <div v-if="submitting" class="mint-popup-confirm-loading">
            <v-progress-circular indeterminate color="secondary" size="48" width="5" />
            <span v-if="props.data.loaderLabel" class="mint-popup-confirm-loading-text" v-text="props.data.loaderLabel" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useLanguagesStore } from '@/store/languages'
import MintButton from '@/components/MintButtons/MintButton.vue'

interface Props {
    data: {
        text: string
        confirmLabel?: string
        cancelLabel?: string
        loaderLabel?: string
        onReject: () => void
        onConfirm: () => void
        // When set, confirming doesn't close the popup immediately — the content dims and a
        // loading overlay (spinner + `loaderLabel`) covers it in place, keeping the popup's
        // size/position stable, until this settles (see popups.ts `confirm()`).
        onSubmit?: () => Promise<void>
    }
}

const props = defineProps<Props>()
const emit = defineEmits(['close'])
const languages = useLanguagesStore()
const submitting = ref(false)

function handleReject() {
    if (submitting.value) {
        return // can't cancel once the request is already in flight
    }
    props.data.onReject()
    emit('close')
}

async function handleConfirm() {
    if (submitting.value) {
        return
    }
    if (props.data.onSubmit) {
        submitting.value = true
        await props.data.onSubmit()
    }
    props.data.onConfirm()
    emit('close')
}
</script>

<style scoped lang="scss">
.mint-popup-confirm {
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 32px;
    min-width: 400px;

    .mint-popup-confirm-body {
        display: flex;
        flex-direction: column;
        gap: 32px;

        // Dimming comes from the semi-transparent overlay drawn on top (below) — not from here
        // too, or the two multiply together and the content disappears instead of just dimming.
        &.is-submitting {
            pointer-events: none;
        }
    }

    .mint-popup-confirm-buttons {
        display: flex;
        justify-content: space-between;
        border-top: thin solid #0002;
        padding: 16px 0px 0px 0px;
    }

    // Overlays the content in place (rather than replacing it) so the popup keeps its size and
    // position instead of shrinking/re-centering while submitting. The surface color at partial
    // opacity is what dims the content underneath — it stays visible (and legible) through it.
    .mint-popup-confirm-loading {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 16px;
        background: rgba(var(--v-theme-surface), 0.55);
        text-align: center;

        .mint-popup-confirm-loading-text {
            font-size: 14px;
            font-weight: 600;
            color: rgb(var(--v-theme-secondary-dark));
        }
    }
}
</style>
