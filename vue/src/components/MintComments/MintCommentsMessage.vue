<template>
    <div
        :class="{
            'mint-comments-message-container': true,
            'mint-comments-message-compact': props.compact,
        }"
    >
        <div class="mint-comments-message-main-comment">
            <div class="mint-comments-message-avatar" @click="openEmployeeDetailView(comment.assigned_user.id)">
                <img
                    v-if="comment.assigned_user.photo"
                    :src="`legacy/index.php?entryPoint=download&type=Users&id=${comment.assigned_user.id}_photo`"
                />
                <v-icon v-else icon="mdi-account" />
            </div>
            <div class="mint-comments-message">
                <div class="mint-comments-message-body" ref="commentBodyContainer">
                    <div class="mint-comments-message-header">
                        <div class="mint-comments-message-header-info">
                            <span
                                class="mint-comments-message-user"
                                v-text="comment.assigned_user.name"
                                @click="openEmployeeDetailView(comment.assigned_user.id)"
                            />
                            <span
                                class="mint-comments-message-edited"
                                v-if="comment.date_edited && !comment.removed"
                                v-text="`(${languages.label('LBL_MINT4_COMMENTS_EDITED')} ${dateEdited})`"
                            />
                        </div>
                        <span class="mint-comments-message-header-date">{{ dateCreated }}</span>
                    </div>
                    <MintCommentsEditor v-if="isEditMode" mode="edit" :comment="comment" @close="isEditMode = false" />
                    <div
                        v-else-if="props.comment.removed"
                        class="mint-comments-message-deleted"
                        v-text="commentRemovedDescription"
                    />
                    <div v-else class="mint-comments-message-content" v-html="contentHtml" />
                </div>
                <div v-if="!isEditMode && !props.comment.removed" class="mint-comments-message-actions">
                    <div class="mint-comments-message-reactions">
                        <MintReactions v-if="comment.reactions?.length" :reactions="comment.reactions" />
                        <v-menu location="top" offset="8">
                            <template v-slot:activator="{ props, isActive }">
                                <MintButton
                                    size="small"
                                    v-bind="props"
                                    variant="text"
                                    :text="languages.label('LBL_MINT4_COMMENTS_REACT_BTN')"
                                    :active="isActive"
                                />
                            </template>
                            <MintReactionsActions
                                :active-reaction-type="currentUserReactionType"
                                @react="handleReactAction"
                                @delete-reaction="store.deleteCommentReaction(props.comment.id)"
                            />
                        </v-menu>
                    </div>
                    <div class="d-flex">
                        <MintButton
                            v-if="!isExpanded && replies.length"
                            variant="text"
                            :text="`${languages.label('LBL_MINT4_COMMENTS_EXPAND_BTN')} (${nestedCommentsCount})`"
                            size="small"
                            @click="isExpanded = true"
                        />
                        <MintButton
                            v-else-if="store.access.add"
                            variant="text"
                            :text="languages.label('LBL_MINT4_COMMENTS_REPLY_BTN')"
                            icon="mdi-reply"
                            size="small"
                            @click="isReplyMode = true"
                        />
                        <v-menu>
                            <template v-slot:activator="{ props, isActive }">
                                <MintButton
                                    v-if="commentMenuActions.length"
                                    v-bind="props"
                                    icon="mdi-dots-vertical"
                                    variant="nav"
                                    size="small"
                                    :active="isActive"
                                />
                            </template>
                            <MintMenuList :items="commentMenuActions" />
                        </v-menu>
                    </div>
                </div>
            </div>
        </div>
        <v-slide-y-transition>
            <div
                v-if="isExpanded && !props.comment.removed && (replies.length || isReplyMode)"
                class="mint-comments-message-replies"
            >
                <MintCommentsMessage v-for="reply in replies" :key="reply.id" :comment="reply" compact />
                <MintCommentsEditor
                    v-if="isReplyMode && !props.comment.removed"
                    mode="reply"
                    :comment="props.comment"
                    @close="isReplyMode = false"
                />
            </div>
        </v-slide-y-transition>
    </div>
</template>

<script setup lang="ts">
import { computed, ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { MintComment } from './MintCommentsStore'
import MintButton from '@/components/MintButtons/MintButton.vue'
import MintMenuList, { MenuListItem } from '@/components/MintMenuList.vue'
import MintReactions from '@/components/MintReactions/MintReactions.vue'
import MintReactionsActions from '@/components/MintReactions/MintReactionsActions.vue'
import { DateTime } from 'luxon'
import { useMintCommentsStore } from './MintCommentsStore'
import { useAuthStore } from '@/store/auth'
import MintCommentsEditor from './MintCommentsEditor.vue'
import { useLanguagesStore } from '@/store/languages'

interface Props {
    comment: MintComment
    pinned?: boolean
    compact?: boolean
}

const props = defineProps<Props>()
const router = useRouter()

const store = useMintCommentsStore()
const auth = useAuthStore()
const languages = useLanguagesStore()

const isExpanded = ref(!props.pinned)
const isEditMode = ref(false)

const isReplyMode = ref(false)

const commentBodyContainer = ref<HTMLDivElement | null>(null)

onMounted(() => {
    if (commentBodyContainer.value) {
        commentBodyContainer.value.addEventListener('click', (e) => {
            const userId = (e.target as HTMLElement).dataset.userId
            if (userId) {
                openEmployeeDetailView(userId)
            }
        })
    }
})

const replies = computed<MintComment[]>(() => {
    return store.comments.filter((comment) => comment.reply_to_id === props.comment.id)
})

const nestedCommentsCount = computed(() => {
    let count = 0
    function calculate(comment: MintComment) {
        const replies = store.comments.filter((c) => c.reply_to_id === comment.id)
        if (!replies.length) {
            return
        }
        count += replies.length
        replies.forEach((reply) => calculate(reply))
    }
    calculate(props.comment)
    return count
})

const contentHtml = computed(() => {
    let content = props.comment.description
    const matchedUsers = Array.from(content.matchAll(/(\W|^)@(\w*)/g))
    matchedUsers.forEach((match) => {
        const username = match[2]
        const user = store.users.find((user) => user.user_name === username)
        if (!user) {
            return
        }
        const regex = new RegExp(`(?<=\\W|^)(@${username})(?=\\W)`, 'g')
        let className = 'mint-comments-message-content-user-highlight'
        if (user.id === auth.user?.id) {
            className += ' mint-comments-message-content-user-highlight-owner'
        }
        content = content.replace(regex, `<span class="${className}" data-user-id="${user.id}">${user.name}</span>`)
    })
    return content
})

const dateCreated = computed(() => {
    const dt = DateTime.fromSQL(props.comment.date_entered, { zone: 'UTC' })
    return dt.toLocal().toFormat('dd.MM.yyyy HH:mm')
})

const currentUserReactionType = computed(() => {
    return props.comment.reactions?.find((reaction) => reaction.user.id === auth.user?.id)?.type
})

function handleReactAction(type: string) {
    if (type) {
        store.reactToComment(props.comment.id, type)
    }
}

const commentRemovedDescription = computed(() => {
    if (!nestedCommentsCount.value) {
        return languages.label('LBL_MINT4_COMMENTS_REMOVED')
    }
    if (nestedCommentsCount.value === 1) {
        return languages.label('LBL_MINT4_COMMENTS_REMOVED_WITH_REPLIES_SINGULAR')
    }
    return languages
        .label('LBL_MINT4_COMMENTS_REMOVED_WITH_REPLIES_PLURAL')
        .replace('{x}', nestedCommentsCount.value.toString())
})

const commentMenuActions = computed<MenuListItem[]>(() => {
    const actions: MenuListItem[] = []
    if (auth.user?.is_admin || (auth.user?.id === props.comment.assigned_user.id && !props.comment.removed)) {
        actions.push({
            title: languages.label('LBL_MINT4_COMMENTS_ACTION_EDIT'),
            icon: 'pencil',
            onClick: () => {
                isEditMode.value = true
            },
        })
        actions.push({
            title: languages.label('LBL_MINT4_COMMENTS_ACTION_REMOVE'),
            icon: 'delete',
            onClick: () => {
                store.deleteComment(props.comment.id)
            },
        })
    }
    if (!props.comment.reply_to_id && store.access.pin) {
        if (props.comment.pinned && props.pinned) {
            actions.push({
                title: languages.label('LBL_MINT4_COMMENTS_ACTION_UNPIN'),
                icon: 'pin-off',
                onClick: () => store.unpinComment(props.comment.id),
            })
        } else if (!props.comment.pinned && !props.pinned) {
            actions.push({
                title: languages.label('LBL_MINT4_COMMENTS_ACTION_PIN'),
                icon: 'pin',
                onClick: () => store.pinComment(props.comment.id),
            })
        }
    }
    return actions
})

const dateEdited = computed(() => {
    if (!props.comment.date_edited) {
        return ''
    }
    const dt = DateTime.fromSQL(props.comment.date_edited, { zone: 'UTC' })
    return dt.toLocal().toFormat('dd.MM.yyyy HH:mm')
})

function openEmployeeDetailView(userId: string) {
    const userUrl = router.resolve({
        name: 'module-view',
        params: {
            module: 'Employees',
            action: 'DetailView',
            record: userId,
        },
    })
    window.open(userUrl.href, '_blank')
}
</script>
<style lang="scss">
.mint-comments-message-content {
    all: revert;
    *:not(table, tr, td) {
        all: revert;
    }
    > :first-child {
        margin-top: 16px;
    }
    > :last-child {
        margin-bottom: 16px;
    }
    blockquote {
        border-left: thin solid rgb(var(--v-theme-primary));
        padding-left: 1em;
        margin-left: 1em;
        margin-right: 1em;
        background: rgba(0, 0, 0, 0.04);
    }
    .mint-comments-message-content-user-highlight {
        cursor: pointer;
        font-weight: 600 !important;
        color: rgb(var(--v-theme-primary)) !important;
        background: rgba(var(--v-theme-primary-light), 1) !important;
        border-radius: 4px !important;
        padding: 2px 4px !important;
        &.mint-comments-message-content-user-highlight-owner {
            color: rgb(var(--v-theme-primary-lighter)) !important;
            background: rgb(var(--v-theme-primary)) !important;
        }
    }
}

// recursive 2 levels of deepness
.mint-comments-threads {
    > .mint-comments-message-container {
        > .mint-comments-message-replies {
            padding-left: 64px;
            > .mint-comments-message-container {
                > .mint-comments-message-replies {
                    padding-left: 48px;
                }
            }
        }
    }
}
</style>

<style scoped lang="scss">
.mint-comments-message-container {
    display: flex;
    flex-direction: column;
    gap: 16px;
    .mint-comments-message-main-comment {
        display: flex;
        gap: 16px;
        width: 100%;
    }

    .mint-comments-message-replies {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
}

.mint-comments-message-compact {
    .mint-comments-message-avatar {
        > * {
            font-size: 20px;
            width: 32px;
            height: 32px;
        }
    }
}

.mint-comments-message-avatar {
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

.mint-comments-message {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.mint-comments-message-body {
    background: rgb(var(--v-theme-primary-lighter));
    border-radius: 16px;
    padding: 8px 12px;
    display: flex;
    flex-direction: column;

    .mint-comments-message-deleted {
        font-style: italic;
        color: #0008;
        letter-spacing: 0.43px;
        font-size: 0.9em;
        margin: 16px 0px;
    }

    .mint-comments-message-content {
        overflow-wrap: anywhere;
    }

    .mint-comments-message-header {
        font-weight: 600;
        font-size: 14px;
        color: rgb(var(--v-theme-primary));
        display: flex;
        justify-content: space-between;
        letter-spacing: 0.43px;

        .mint-comments-message-header-info {
            color: rgb(var(--v-theme-secondary));
            display: flex;
            align-items: center;
            gap: 8px;

            .mint-comments-message-user {
                cursor: pointer;
            }

            .mint-comments-message-edited {
                font-style: italic;
                color: #0008;
                letter-spacing: 0.43px;
                font-size: 11px;
                font-weight: 400;
            }
        }

        .mint-comments-message-header-date {
            font-size: 12px;
            letter-spacing: 0.4px;
            font-weight: 400;
            color: rgba(0, 0, 0, 0.6);
        }
    }
}

.mint-comments-message-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0px 8px;

    .mint-comments-message-reactions {
        display: flex;
        gap: 8px;
    }
}
</style>
