<template>
    <div class="mint-news-container">
        <div v-if="wall.wallLoading">
            <v-skeleton-loader
                v-for="index in 3"
                :key="'mint-news-wall-loader' + index"
                type="article"
                class="mint-news-loading"
            />
        </div>
        <v-row v-else no-gutters class="mint-news-row" v-for="newsItem in wall.newsList" :key="newsItem.id">
            <div class="mint-news-header">
                <img
                    v-if="newsItem.author.photo"
                    class="mint-news-avatar"
                    alt="News author photo"
                    :src="newsItem.author.photo"
                    @click="navigateTo(`/modules/Employees/DetailView/${newsItem.author.id}`)"
                />
                <v-icon
                    v-else
                    class="mint-news-avatar"
                    size="40"
                    icon="mdi-account"
                    @click="navigateTo(`/modules/Employees/DetailView/${newsItem.author.id}`)"
                />
                <div class="mint-news-subheader">
                    <div
                        class="mint-news-author"
                        @click="navigateTo(`/modules/Employees/DetailView/${newsItem.author.id}`)"
                    >
                        {{ newsItem.author.name }}
                    </div>
                    <div class="mint-news-time">
                        {{ toRelativeDate(newsItem.publication_date) }}
                    </div>
                    <MintUnreadDot v-if="newsItem.is_read === false" />
                </div>
            </div>
            <img
                v-if="newsItem.photo"
                class="mint-news-img"
                alt="News image"
                :src="newsItem.photo"
                @click="navigateTo(`/modules/News/DetailView/${newsItem.id}`)"
            />
            <div class="mint-news-title-row">
                <h5 class="mint-news-title" @click="navigateTo(`/modules/News/DetailView/${newsItem.id}`)">
                    {{ newsItem.name }}
                </h5>
                <v-btn
                    icon
                    variant="text"
                    size="small"
                    class="mint-news-open-btn"
                    :title="languages.label('LBL_MINT4_WALL_OPEN_FULL')"
                    @click.stop="navigateTo(`/modules/News/DetailView/${newsItem.id}`)"
                >
                    <v-icon size="20" icon="mdi-open-in-new" />
                </v-btn>
            </div>
            <div
                v-if="!expandedNewsIds.has(newsItem.id)"
                class="mint-news-desc"
            >{{ sanitizeNewsContent(normalizeNewsContent(newsItem.content_of_announcement)) }}</div>
            <div
                v-else
                class="mint-news-full-content"
                v-html="sanitizeNewsContent(newsItem.content_of_announcement)"
            />
            <div class="mint-news-footer">
                <div class="mint-news-footer-left">
                    <MintWallReactions :newsItem="newsItem"/>
                </div>
                <div class="mint-news-footer-right">
                    <MintWallCommentsCounter :comments-count="nonRemovedCommentsCount(newsItem.id)" />
                    <div class="mint-news-read-more" @click="toggleExpand(newsItem.id)">
                        {{ expandedNewsIds.has(newsItem.id) ? languages.label('LBL_MINT4_WALL_COLLAPSE') : languages.label('LBL_MINT4_WALL_READ_MORE') }}
                    </div>
                </div>
            </div>
            <div class="mint-news-comments">
                <div class="mint-news-comments-header">
                    {{ languages.label('LBL_MINT4_WALL_COMMENTS') }} ({{ nonRemovedCommentsCount(newsItem.id) }})
                </div>
                <div v-if="wall.commentsLoading.has(newsItem.id)" class="mint-news-comments-loading">
                    <v-skeleton-loader type="list-item-avatar" />
                </div>
                <template v-else>
                    <MintWallComment
                        v-for="comment in topLevelComments(newsItem.id)"
                        :key="comment.id"
                        :comment="comment"
                        :all-comments="wall.commentsByNewsId[newsItem.id] ?? []"
                        :depth="0"
                        :news-id="newsItem.id"
                        :active-reply-comment-id="activeReplyCommentIds[newsItem.id] ?? null"
                        @reply-click="setActiveReply(newsItem.id, $event)"
                    />
                    <Transition name="mint-wall-editor">
                        <MintWallEditor
                            v-if="!activeReplyCommentIds[newsItem.id]"
                            :news-id="newsItem.id"
                            :placeholder="languages.label(nonRemovedCommentsCount(newsItem.id) === 0 ? 'LBL_MINT4_WALL_NO_COMMENTS' : 'LBL_MINT4_WALL_LEAVE_COMMENT')"
                        />
                    </Transition>
                </template>
            </div>
        </v-row>
    </div>
</template>

<script setup lang="ts">
import { DateTime } from 'luxon'
import { useRouter } from 'vue-router'
import DOMPurify from 'dompurify'
import { useLanguagesStore } from '@/store/languages'
import { useMintWallStore } from './MintWallStore'
import { useUxStore } from '@/store/ux'
import MintUnreadDot from '../MintUnreadDot.vue'
import MintWallReactions from './MintWallReactions.vue'
import MintWallCommentsCounter from './MintWallCommentsCounter.vue'
import MintWallComment from './MintWallComment.vue'
import MintWallEditor from './MintWallEditor.vue'
import { computed, onMounted, ref } from 'vue'

const router = useRouter()
const languages = useLanguagesStore()
const wall = useMintWallStore()
const ux = useUxStore()

const expandedNewsIds = ref<Set<string>>(new Set())
const activeReplyCommentIds = ref<Record<string, string | null>>({})

function setActiveReply(newsId: string, commentId: string | null) {
    activeReplyCommentIds.value = { ...activeReplyCommentIds.value, [newsId]: commentId }
}

function toggleExpand(id: string) {
    if (expandedNewsIds.value.has(id)) {
        expandedNewsIds.value.delete(id)
    } else {
        expandedNewsIds.value.add(id)
        wall.readNewsAlerts(id)
        if (!wall.commentsByNewsId[id]) {
            void wall.fetchComments(id)
        }
    }
    expandedNewsIds.value = new Set(expandedNewsIds.value)
}

function topLevelComments(newsId: string) {
    return (wall.commentsByNewsId[newsId] ?? []).filter((c) => !c.reply_to_id && !c.removed)
}

function nonRemovedCommentsCount(newsId: string) {
    const all = wall.commentsByNewsId[newsId] ?? []
    let count = 0
    function traverse(parentId: string | null) {
        const children = all.filter((c) => (c.reply_to_id || null) === parentId && !c.removed)
        count += children.length
        children.forEach((c) => traverse(c.id))
    }
    traverse(null)
    return count
}

onMounted(() => {
    wall.loadNews()
})

const toRelativeDate = computed(() => (date: string) => {
    const dt = DateTime.fromSQL(date, { zone: 'UTC' })
    if (dt.diffNow('days').days >= -5) {
        return dt.toRelative()
    }
    return dt.toFormat('dd.MM.yyyy')
})

const sanitizeNewsContent = (html: string) => DOMPurify.sanitize(html ?? '')

const normalizeNewsContent = computed(() => (html: string) => {
    let newsContent = html.replace(/<\/?[^>]+(>|$)/g, '')
    if (newsContent.length > 250) {
        newsContent = newsContent.substring(0, 247) + '...'
    }
    return newsContent
})
async function navigateTo(url: string) {
    if (router.currentRoute.value.path === url && router.currentRoute.value.params.module === 'News') {
        await wall.readNewsAlerts(router.currentRoute.value.params.record as string)
    } else {
        router.push(url)
    }
    ux.drawer = null
}
</script>

<style scoped lang="scss">
.mint-news-container {
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    font-family: 'Barlow', sans-serif;
    overflow: scroll;
    scroll-behavior: smooth;
    height: 100%;
    padding-top: 8px;
    .mint-news-loading {
        top: 20px;
    }
    .mint-news-row {
        display: flex;
        flex-direction: column;
        background: rgb(var(--v-theme-primary-lighter)) 0% 0% no-repeat padding-box;
        border-radius: 16px;
        margin: 8px 16px;
        padding: 12px;
        .mint-news-header {
            display: flex;
            flex-direction: row;
            width: 100%;
            max-height: 40px;
            margin-bottom: 16px;
            .mint-news-avatar {
                object-fit: cover;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                cursor: pointer;
            }
            .mint-news-subheader {
                display: flex;
                flex-direction: row;
                align-items: center;
                width: 100%;
                justify-content: space-between;
                .mint-news-author {
                    letter-spacing: 0.43px;
                    color: rgb(var(--v-theme-secondary));
                    font-weight: 600; // SemiBold
                    font-size: 14px;
                    margin-left: 16px;
                    cursor: pointer;
                }
                .mint-news-time {
                    letter-spacing: 0.4px;
                    font-weight: normal;
                    font-size: 12px;
                    text-align: right;
                    margin-left: auto;
                    margin-right: 16px;
                }
            }
        }
        .mint-news-img {
            width: 100%;
            min-height: 80px;
            max-height: 200px;
            border-radius: 16px;
            object-fit: cover;
            margin-bottom: 16px;
            cursor: pointer;
        }
        .mint-news-title-row {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 4px;
            margin-bottom: 16px;
            .mint-news-title {
                font-size: 24px;
                letter-spacing: 0.18px;
                cursor: pointer;
                margin-bottom: 0;
            }
            .mint-news-open-btn {
                color: rgb(var(--v-theme-secondary));
                opacity: 0.6;
                flex-shrink: 0;
                &:hover {
                    opacity: 1;
                }
            }
        }
        .mint-news-desc {
            letter-spacing: 0.18px;
            max-height: 6.4em;
            overflow: hidden;
            line-height: 1.6;
            mask-image: linear-gradient(to bottom, black 60%, transparent 100%);
            -webkit-mask-image: linear-gradient(to bottom, black 60%, transparent 100%);
            padding-left: 16px;
        }
        .mint-news-full-content {
            letter-spacing: 0.18px;
            overflow-y: auto;
            padding-right: 4px;

            :deep(h1), :deep(h2), :deep(h3), :deep(h4), :deep(h5), :deep(h6) {
                margin: 8px 0 4px;
                font-weight: 600;
            }
            :deep(p) {
                margin: 4px 0;
            }
            :deep(ul), :deep(ol) {
                margin: 4px 0 4px 20px;
            }
            :deep(a) {
                color: rgb(var(--v-theme-secondary));
            }
            :deep(img) {
                max-width: 100%;
                height: auto;
                border-radius: 8px;
            }
        }
        .mint-news-footer {
            margin: 16px 8px 8px 8px;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            color: rgb(var(--v-theme-secondary));
            .mint-news-footer-left {
                display: flex;
                flex-direction: row;
                .mint-news-like {
                    font-weight: 600; // SemiBold
                    color: rgb(var(--v-theme-secondary));
                    background-color: rgb(var(--v-theme-primary-lighter));
                }
            }
            .mint-news-footer-right {
                min-width: 30%;
                display: flex;
                flex-direction: row;
                align-items: end;
                .mint-news-read-more {
                    text-decoration: underline;
                    cursor: pointer;
                    margin-left: auto;
                }
            }
            @media (max-width: 600px) {
                .mint-news-footer-right {
                    min-width: 45%;
                }
            }
        }
        .mint-news-comments {
            margin-top: 12px;
            border-top: 1px solid #d9eeec;
            padding-top: 8px;
            .mint-news-comments-header {
                font-weight: 600;
                font-size: 13px;
                color: rgb(var(--v-theme-secondary));
                margin-bottom: 8px;
            }
            .mint-news-comments-empty {
                font-size: 13px;
                color: #8a8a8a;
                font-style: italic;
                padding: 4px 0;
            }
        }
    }
}
</style>
