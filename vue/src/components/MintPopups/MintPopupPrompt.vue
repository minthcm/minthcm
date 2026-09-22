<template>
    <div class="mint-popup-prompt">
        <div class="mint-popup-prompt-body" :class="{ 'is-submitting': submitting }">
            <MdPreview v-if="props.data.text" :model-value="props.data.text" :sanitize="sanitize" preview-theme="default" />
            <v-textarea v-model="value" :rules="rules" :disabled="submitting" variant="outlined" density="compact" rows="4" auto-grow />
            <div class="mint-popup-prompt-buttons">
                <MintButton @click="handleReject()" :disabled="submitting" :text="props.data.cancelLabel ?? languages.label('LBL_CANCEL')" variant="text" />
                <MintButton @click="handleConfirm()" :disabled="submitting" :text="props.data.confirmLabel ?? languages.label('LBL_CONFIRM')" variant="primary" />
            </div>
        </div>
        <div v-if="submitting" class="mint-popup-prompt-loading">
            <v-progress-circular indeterminate color="secondary" size="48" width="5" />
            <span v-if="props.data.loaderLabel" class="mint-popup-prompt-loading-text" v-text="props.data.loaderLabel" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import DOMPurify from 'dompurify'
import { MdPreview } from 'md-editor-v3'
import 'md-editor-v3/lib/style.css'
import { useLanguagesStore } from '@/store/languages'
import MintButton from '@/components/MintButtons/MintButton.vue'

interface Props {
    data: {
        text: string
        required?: boolean
        confirmLabel?: string
        cancelLabel?: string
        loaderLabel?: string
        onReject: () => void
        onConfirm: (value: string) => void
        // When set, confirming doesn't close the popup immediately — the form dims and a loading
        // overlay (spinner + `loaderLabel`) covers it in place, keeping the popup's size/position
        // stable, until this settles. Only then does it resolve/close (see popups.ts `prompt()`).
        onSubmit?: (value: string) => Promise<void>
    }
}

const props = defineProps<Props>()
const emit = defineEmits(['close'])
const languages = useLanguagesStore()
const value = ref('')
const submitting = ref(false)
const sanitize = (html: string) => DOMPurify.sanitize(html)

const rules = computed(() =>
    props.data.required
        ? [(v: string) => !!v?.trim() || languages.label('LBL_MINT4_ERROR_REQUIRED_FIELD')]
        : [],
)

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
    const trimmed = value.value.trim()
    if (props.data.required && !trimmed) {
        return // `rules` validation already shows the inline error
    }
    if (props.data.onSubmit) {
        submitting.value = true
        await props.data.onSubmit(trimmed)
    }
    props.data.onConfirm(trimmed)
    emit('close')
}
</script>

<style scoped lang="scss">
.mint-popup-prompt {
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 16px;
    min-width: 400px;

    .mint-popup-prompt-body {
        display: flex;
        flex-direction: column;
        gap: 16px;

        // Dimming comes from the semi-transparent overlay drawn on top (below) — not from here
        // too, or the two multiply together and the form disappears instead of just dimming.
        &.is-submitting {
            pointer-events: none;
        }
    }

    .mint-popup-prompt-buttons {
        display: flex;
        justify-content: space-between;
        border-top: thin solid #0002;
        padding: 16px 0px 0px 0px;
    }

    // Overlays the form in place (rather than replacing it) so the popup keeps its size and
    // position instead of shrinking/re-centering while submitting. The surface color at partial
    // opacity is what dims the form underneath — it stays visible (and legible) through it.
    .mint-popup-prompt-loading {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 16px;
        background: rgba(var(--v-theme-surface), 0.55);
        text-align: center;

        .mint-popup-prompt-loading-text {
            font-size: 14px;
            font-weight: 600;
            color: rgb(var(--v-theme-secondary-dark));
        }
    }
}
</style>
