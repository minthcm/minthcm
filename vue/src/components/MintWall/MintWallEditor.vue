<template>
    <div class="mint-wall-editor">
        <MintWysiwyg v-model="description" :options="tinymceConfig" ref="wysiwyg">
            <template #footer>
                <div class="mint-wall-editor-buttons">
                    <MintButton
                        v-if="replyToId"
                        variant="text"
                        size="small"
                        :text="languages.label('LBL_MINT4_WALL_CANCEL_BTN')"
                        @mousedown.prevent
                        @click="emit('close', false)"
                    />
                    <MintButton
                        variant="primary"
                        size="small"
                        :text="languages.label(replyToId ? 'LBL_MINT4_WALL_REPLY_BTN' : 'LBL_MINT4_WALL_SEND_BTN')"
                        icon="mdi-send"
                        :disabled="!description"
                        @mousedown.prevent
                        @click="submit"
                    />
                </div>
            </template>
        </MintWysiwyg>
    </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import MintWysiwyg from '@/components/MintWysiwyg.vue'
import MintButton from '@/components/MintButtons/MintButton.vue'
import { useMintWallStore } from './MintWallStore'
import { useLanguagesStore } from '@/store/languages'
import type { RawEditorOptions } from 'tinymce'

interface Props {
    newsId: string
    replyToId?: string
    placeholder?: string
}

const props = defineProps<Props>()
const emit = defineEmits<{
    close: [success: boolean]
}>()

const store = useMintWallStore()
const languages = useLanguagesStore()

const wysiwyg = ref<InstanceType<typeof MintWysiwyg>>()
const description = ref('')
let blurTimer: ReturnType<typeof setTimeout> | null = null

const tinymceConfig: RawEditorOptions = {
    height: 150,
    plugins: 'lists emoticons',
    toolbar_mode: 'wrap' as RawEditorOptions['toolbar_mode'],
    toolbar: 'bold italic underline strikethrough | numlist bullist | emoticons',
    menubar: false,
    placeholder: props.placeholder,
    content_style: `
        @import url('https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;600&display=swap');
        body {
            font-family: Barlow;
            position: relative;
        }
        body[data-mce-placeholder]::before {
            content: attr(data-mce-placeholder);
            color: rgba(34, 47, 62, 0.5);
            position: absolute;
            pointer-events: none;
            top: 0;
            left: 0;
            padding: 1px;
        }
    `,
}

watch(
    () => wysiwyg.value?.tinymceEditor,
    (editor) => {
        if (!editor || !props.replyToId) return
        editor.on('blur', () => {
            blurTimer = setTimeout(() => {
                if (!description.value) {
                    emit('close', false)
                }
            }, 150)
        })
        editor.on('focus', () => {
            if (blurTimer !== null) {
                clearTimeout(blurTimer)
                blurTimer = null
            }
        })
    },
    { once: true },
)

async function submit() {
    if (!description.value) return
    // Capture values before clearing/unmounting
    const descToSend = description.value
    const newsId = props.newsId
    const replyToId = props.replyToId
    // Clear and close immediately for instant feedback
    description.value = ''
    wysiwyg.value?.tinymceEditor?.setContent('')
    emit('close', true)
    // Persist in background — addComment no longer toggles commentsLoading
    await store.addComment(newsId, descToSend, replyToId)
}
</script>

<style lang="scss">
.mint-wall-editor {
    width: 100%;
    box-sizing: border-box;
    margin: 8px 0 6px 0;

    .tox.tox-tinymce {
        min-height: unset;
    }

    .tox-toolbar-overlord,
    .tox-toolbar__primary {
        background: transparent !important;
    }

    .tox .tox-toolbar__group {
        padding: 0 0.5% !important;
    }

    .tox-toolbar__primary {
        padding: 2px !important;

        .tox-tbtn {
            height: 26px !important;
            width: 26px !important;
            min-width: unset !important;
            padding: 0 !important;

            svg {
                width: 16px !important;
                height: 16px !important;
            }
        }

        .tox-tbtn--select {
            width: auto !important;
            padding: 0 4px !important;
        }

        .tox-tbtn__select-chevron svg {
            width: 10px !important;
            height: 10px !important;
        }
    }

    .mint-wysiwyg-footer {
        padding: 6px 8px !important;
    }
}

.mint-wall-editor-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 6px;
    align-items: center;
}

.mint-wall-editor-enter-active {
    transition: opacity 0.2s ease-out, transform 0.2s ease-out;
}
.mint-wall-editor-enter-from {
    opacity: 0;
    transform: translateY(-8px);
}
</style>
