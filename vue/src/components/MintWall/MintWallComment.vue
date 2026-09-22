<template>
    <template v-if="!comment.removed">
        <div class="mint-news-comment" :class="{ 'mint-news-comment-reply': depth > 0 }">
            <img
                v-if="comment.assigned_user.photo"
                class="mint-news-comment-avatar"
                alt="Comment author photo"
                :src="`legacy/index.php?entryPoint=download&type=Users&id=${comment.assigned_user.id}_photo`"
            />
            <v-icon
                v-else
                class="mint-news-comment-avatar"
                :size="depth > 0 ? 28 : 32"
                icon="mdi-account"
            />
            <div class="mint-news-comment-body">
                <div class="mint-news-comment-meta">
                    <span class="mint-news-comment-author">{{ comment.assigned_user.full_name }}</span>
                    <span class="mint-news-comment-date">{{ commentDate }}</span>
                    <span class="mint-news-comment-meta-spacer" />
                    <button
                        class="mint-news-comment-reply-btn"
                        :class="{ active: activeReplyCommentId === comment.id }"
                        @click="handleReplyClick"
                    >
                        <v-icon size="12" icon="mdi-reply" />
                        {{ languages.label('LBL_MINT4_WALL_REPLY_BTN') }}
                    </button>
                </div>
                <div class="mint-news-comment-content" v-html="sanitizedDescription" />
            </div>
        </div>
        <Transition name="mint-wall-editor">
            <MintWallEditor
                v-if="activeReplyCommentId === comment.id"
                :news-id="newsId"
                :reply-to-id="comment.id"
                :placeholder="languages.label('LBL_MINT4_WALL_REPLY_PLACEHOLDER')"
                @close="handleReplyEditorClose"
            />
        </Transition>
        <div v-if="directReplies.length > 0" class="mint-news-comment-replies-toggle">
            <span class="mint-news-comment-show-replies" @click="isExpanded = !isExpanded">
                <v-icon size="14" :icon="isExpanded ? 'mdi-chevron-up' : 'mdi-chevron-down'" />
                <span class="mint-news-comment-show-replies-text">{{
                    isExpanded
                        ? languages.label('LBL_MINT4_COMMENTS_HIDE_BTN')
                        : `${languages.label('LBL_MINT4_COMMENTS_EXPAND_BTN')} (${nestedCommentsCount})`
                }}</span>
            </span>
        </div>
        <v-slide-y-transition>
            <div v-if="isExpanded" class="mint-news-comment-replies">
                <MintWallComment
                    v-for="reply in directReplies"
                    :key="reply.id"
                    :comment="reply"
                    :all-comments="allComments"
                    :depth="depth + 1"
                    :news-id="newsId"
                    :active-reply-comment-id="activeReplyCommentId"
                    @reply-click="emit('reply-click', $event)"
                />
            </div>
        </v-slide-y-transition>
    </template>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import { DateTime } from 'luxon'
import DOMPurify from 'dompurify'
import { useLanguagesStore } from '@/store/languages'
import type { NewsComment } from './MintWallStore'
import MintWallEditor from './MintWallEditor.vue'

const props = defineProps<{
    comment: NewsComment
    allComments: NewsComment[]
    depth: number
    newsId: string
    activeReplyCommentId: string | null
}>()

const emit = defineEmits<{
    'reply-click': [commentId: string | null]
}>()

const languages = useLanguagesStore()
const isExpanded = ref(false)

function handleReplyClick() {
    if (props.activeReplyCommentId === props.comment.id) {
        emit('reply-click', null)
    } else {
        emit('reply-click', props.comment.id)
    }
}

function handleReplyEditorClose(success: boolean) {
    if (success) {
        isExpanded.value = true
    }
    emit('reply-click', null)
}

const directReplies = computed(() =>
    props.allComments.filter((c) => c.reply_to_id === props.comment.id && !c.removed),
)

const nestedCommentsCount = computed(() => {
    let count = 0
    function calculate(commentId: string) {
        const replies = props.allComments.filter((c) => c.reply_to_id === commentId && !c.removed)
        count += replies.length
        replies.forEach((r) => calculate(r.id))
    }
    calculate(props.comment.id)
    return count
})

const commentDate = computed(() =>
    DateTime.fromSQL(props.comment.date_entered, { zone: 'UTC' }).toLocal().toFormat('dd.MM.yyyy HH:mm'),
)

const sanitizedDescription = computed(() => DOMPurify.sanitize(props.comment.description ?? ''))


</script>

<style scoped lang="scss">
.mint-news-comment {
    display: flex;
    flex-direction: row;
    gap: 8px;
    margin-bottom: 10px;
    .mint-news-comment-avatar {
        object-fit: cover;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    &.mint-news-comment-reply {
        .mint-news-comment-avatar {
            width: 28px;
            height: 28px;
        }
    }
    .mint-news-comment-body {
        flex: 1;
        background: rgb(var(--v-theme-comment-bg));
        border-radius: 10px;
        padding: 6px 10px;
        min-width: 0;
        .mint-news-comment-meta {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 6px;
            margin-bottom: 2px;
            .mint-news-comment-author {
                font-weight: 600;
                font-size: 12px;
                color: rgb(var(--v-theme-secondary));
                white-space: nowrap;
            }
            .mint-news-comment-date {
                font-size: 11px;
                color: rgb(var(--v-theme-on-comment));
                white-space: nowrap;
            }
            .mint-news-comment-meta-spacer {
                flex: 1;
            }
            .mint-news-comment-reply-btn {
                display: inline-flex;
                align-items: center;
                gap: 3px;
                background: transparent;
                border: none;
                cursor: pointer;
                color: rgb(var(--v-theme-secondary));
                font-size: 11px;
                font-weight: 600;
                padding: 2px 6px;
                border-radius: 50px;
                text-transform: uppercase;
                letter-spacing: 0.4px;
                transition: all 150ms ease-in-out;
                white-space: nowrap;
                flex-shrink: 0;
                &:hover {
                    background: rgb(var(--v-theme-comment-hover));
                }
                &.active {
                    background: rgb(var(--v-theme-comment-hover));
                    color: rgb(var(--v-theme-secondary-dark));
                }
            }
        }
        .mint-news-comment-content {
            font-size: 13px;
            word-break: break-word;
            :deep(p) {
                margin: 2px 0;
            }
            :deep(a) {
                color: rgb(var(--v-theme-secondary));
            }
        }
    }
}
.mint-news-comment-replies-toggle {
    padding-left: 40px;
    margin-top: -6px;
    margin-bottom: 8px;
    .mint-news-comment-show-replies {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        font-weight: 600;
        color: rgb(var(--v-theme-secondary));
        cursor: pointer;
        user-select: none;
        .mint-news-comment-show-replies-text {
            &:hover {
                text-decoration: underline;
            }
        }
    }
}
.mint-news-comment-replies {
    padding-left: 24px;
    border-left: 2px solid rgb(var(--v-theme-comment-hover));
    margin-left: 16px;
    margin-bottom: 4px;
}
</style>
