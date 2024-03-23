<template>
    <div
        :class="{
            'mint-comments-editor-container': true,
            'mint-comments-editor-compact': mode === 'reply',
        }"
    >
        <div v-if="mode !== 'edit'" class="mint-comments-editor-avatar" @click="openEmployeeDetailView">
            <img
                v-if="auth.user?.photo"
                :src="`legacy/index.php?entryPoint=download&type=Users&id=${auth.user?.id}_photo`"
            />
            <v-icon v-else icon="mdi-account" />
        </div>
        <div class="mint-comments-editor">
            <MintWysiwyg
                v-model="description"
                :options="tinymceConfig"
                ref="wysiwyg"
                @cursor-change="calculateUserQuery"
            >
                <template #footer>
                    <div class="mint-comments-editor-buttons">
                        <div class="mint-comments-editor-buttons-group">
                            <MintButton
                                v-if="mode !== 'new'"
                                variant="text"
                                :text="languages.label('LBL_MINT4_COMMENTS_CANCEL_BTN')"
                                @click="emit('close')"
                            />
                        </div>
                        <div class="mint-comments-editor-buttons-group">
                            <MintButton
                                v-if="mode === 'new'"
                                variant="primary"
                                :text="languages.label('LBL_MINT4_COMMENTS_ADD_COMMENT_BTN')"
                                :disabled="isPrimaryButtonDisabled"
                                icon="mdi-send"
                                @click="addNewComment"
                            />
                            <MintButton
                                v-else-if="mode === 'edit'"
                                variant="primary"
                                :text="languages.label('LBL_MINT4_COMMENTS_SAVE_BTN')"
                                :disabled="isPrimaryButtonDisabled"
                                icon="mdi-check"
                                @click="saveEditedComment"
                            />
                            <template v-else-if="mode === 'reply'">
                                <MintButton
                                    variant="text"
                                    :text="languages.label('LBL_MINT4_COMMENTS_QUOTE_BTN')"
                                    icon="mdi-format-quote-close"
                                    @click="quote"
                                />
                                <MintButton
                                    variant="primary"
                                    :text="languages.label('LBL_MINT4_COMMENTS_REPLY_BTN')"
                                    :disabled="isPrimaryButtonDisabled"
                                    icon="mdi-reply"
                                    @click="reply"
                                />
                            </template>
                        </div>
                    </div>
                </template>
            </MintWysiwyg>
        </div>
        <v-slide-x-transition>
            <MintCommentsUsersHint
                v-if="userQuery !== null"
                :query="userQuery"
                class="mint-comments-editor-users-hint"
                @user-click="insertUsername"
            />
        </v-slide-x-transition>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted, nextTick } from 'vue'
import { useRouter } from 'vue-router'
import MintWysiwyg from '@/components/MintWysiwyg.vue'
import { MintComment, useMintCommentsStore } from './MintCommentsStore'
import MintButton from '@/components/MintButtons/MintButton.vue'
import { useAuthStore } from '@/store/auth'
import { useLanguagesStore } from '@/store/languages'
import MintCommentsUsersHint from './MintCommentsUsersHint.vue'
import { RawEditorSettings as TinymceConfig } from 'tinymce'

interface Props {
    mode: 'new' | 'edit' | 'reply'
    comment?: MintComment // new - null | edit - edited comment | reply - parent comment
}

const props = defineProps<Props>()
const emit = defineEmits(['close'])

const store = useMintCommentsStore()
const auth = useAuthStore()
const router = useRouter()
const languages = useLanguagesStore()

const tinymceConfig: TinymceConfig = {
    height: 280,
    content_style: `
        @import url('https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;600&display=swap'); body { font-family: Barlow; }
        blockquote {
            border-left: thin solid #00654e;
            padding-left: 1em;
            margin-left: 1em;
            margin-right: 1em;
            background: rgba(0, 0, 0, 0.04);
        }
    `,
}

const wysiwyg = ref<InstanceType<typeof MintWysiwyg>>()
const initialDescription = props.mode === 'edit' ? props.comment?.description ?? '' : ''
const description = ref(initialDescription)
const userQuery = ref<null | string>(null)
const isPrimaryButtonDisabled = computed(() => !description.value)

onMounted(() => {
    if (props.mode === 'reply' && wysiwyg.value?.tinymceEditor) {
        wysiwyg.value.tinymceEditor?.focus()
    }
})

async function addNewComment() {
    if (description.value) {
        await store.addComment(description.value)
        wysiwyg.value?.tinymceEditor?.setContent('')
        userQuery.value = null
        store.fetchComments()
    }
}

async function saveEditedComment() {
    if (description.value && props.comment) {
        emit('close')
        await store.editCommentDescription(props.comment.id, description.value)
        store.fetchComments()
    }
}

async function reply() {
    if (description.value && props.comment) {
        await store.addComment(description.value, props.comment.id)
        store.fetchComments()
        emit('close')
    }
}

function quote() {
    if (!props.comment?.description) {
        return
    }
    let quoteBody = `<p style="padding-left:1em;"><em>${props.comment.assigned_user.name} ${languages.label(
        'LBL_MINT4_COMMENTS_QUOTE_SUFFIX',
    )}:</em></p>`
    const dom = document.createElement('div')
    dom.innerHTML = props.comment.description
    for (const child of dom.children) {
        quoteBody += `<blockquote>${child.outerHTML}</blockquote>`
        quoteBody += '<p><br data-mce-bogus="1"></p>'
    }
    wysiwyg.value?.tinymceEditor?.execCommand('mceInsertContent', false, quoteBody)
}

function openEmployeeDetailView() {
    const userUrl = router.resolve({
        name: 'module-view',
        params: {
            module: 'Employees',
            action: 'DetailView',
            record: auth.user?.id,
        },
    })
    window.open(userUrl.href, '_blank')
}

function insertUsername(username: string) {
    const sel = wysiwyg.value?.tinymceEditor?.selection.getSel()
    if (!wysiwyg.value || !wysiwyg.value.tinymceEditor || !sel?.focusNode || userQuery.value === null) {
        return
    }
    const textBeforeCursor = sel.focusNode.textContent?.slice(0, sel?.focusOffset)
    if (!textBeforeCursor?.includes('@')) {
        return
    }
    const atSymbolPosition = textBeforeCursor.lastIndexOf('@')
    const newOffset = sel.focusOffset + (username.length - userQuery.value.length)
    sel.focusNode.textContent =
        (sel.focusNode.textContent ?? '').slice(0, atSymbolPosition + 1) +
        username +
        (sel.focusNode.textContent ?? '').slice(atSymbolPosition + 1 + userQuery.value.length)
    wysiwyg.value.tinymceEditor.selection.setCursorLocation(sel.focusNode, newOffset)
    wysiwyg.value.tinymceEditor.focus()
    wysiwyg.value.tinymceEditor.execCommand('mceInsertContent', false, '&nbsp;')
}

async function calculateUserQuery() {
    await nextTick()
    const sel = wysiwyg.value?.tinymceEditor?.selection.getSel()
    const textBeforeCursor = sel?.focusNode?.textContent?.slice(0, sel?.focusOffset)
    if (!textBeforeCursor?.includes('@')) {
        userQuery.value = null
        return
    }
    const atSymbolPosition = textBeforeCursor.lastIndexOf('@')
    const characterBeforeAtSymbol = textBeforeCursor[atSymbolPosition - 1]
    if (characterBeforeAtSymbol?.match(/[a-zA-Z0-9]/g)?.length) {
        // probably an e-mail address
        userQuery.value = null
        return
    }
    const textBetweenAtSymbolAndCursor = textBeforeCursor.slice(atSymbolPosition + 1)
    const numberOfSpaces = textBetweenAtSymbolAndCursor.match(/[\s]/g)?.length ?? 0
    if (numberOfSpaces > 1) {
        userQuery.value = null
        return
    }
    const numberOfInvalidCharacters = textBetweenAtSymbolAndCursor.match(/[^a-zA-Z0-9\s]/g)?.length ?? 0
    if (numberOfInvalidCharacters > 0) {
        userQuery.value = null
        return
    }
    userQuery.value = textBetweenAtSymbolAndCursor ?? ''
}

watch(() => description.value, calculateUserQuery)
</script>

<style scoped lang="scss">
.mint-comments-editor-container {
    display: flex;
    gap: 16px;
    width: 100%;
    position: relative;

    .mint-comments-editor-avatar {
        display: flex;
        height: fit-content;
        cursor: pointer;
        > * {
            font-size: 32px;
            background: #eee;
            color: #444;
            width: 48px;
            height: 48px;
            object-fit: cover;
            border-radius: 50%;
        }
    }

    .mint-comments-editor {
        width: 100%;
        .mint-comments-editor-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 32px;

            .mint-comments-editor-buttons-group {
                display: flex;
                align-items: center;
                gap: 16px;
            }
        }
    }

    .mint-comments-editor-users-hint {
        position: absolute;
        left: calc(100% + 16px);
        z-index: 1;
    }
}

.mint-comments-editor-compact {
    .mint-comments-editor-avatar {
        > * {
            font-size: 20px;
            width: 32px;
            height: 32px;
        }
    }
}
</style>
