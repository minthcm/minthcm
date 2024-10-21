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
                    <div class="mint-news-time">{{ toRelativeDate(newsItem.publication_date) }}</div>
                </div>
            </div>
            <img
                v-if="newsItem.photo"
                class="mint-news-img"
                alt="News image"
                :src="newsItem.photo"
                @click="navigateTo(`/modules/News/DetailView/${newsItem.id}`)"
            />
            <h5 class="mint-news-title" @click="navigateTo(`/modules/News/DetailView/${newsItem.id}`)">
                {{ newsItem.name }}
            </h5>
            <div class="mint-news-desc">{{ normalizeNewsContent(newsItem.content_of_announcement) }}</div>
            <div class="mint-news-footer">
                <MintButton
                    :variant="newsItem.liked ? 'regular' : 'text'"
                    :text="newsItem.liked ? 'LIKED' : 'LIKE'"
                    :active="newsItem.liked"
                    icon="mdi-thumb-up"
                    @click="wall.likeClicked(newsItem.id)"
                />
                <div class="mint-news-read-more" @click="navigateTo(`/modules/News/DetailView/${newsItem.id}`)">
                    {{ languages.label('LBL_MINT4_WALL_READ_MORE') }}
                </div>
            </div>
        </v-row>
    </div>
</template>

<script setup lang="ts">
import { DateTime } from 'luxon'
import { useRouter } from 'vue-router'
import { useLanguagesStore } from '@/store/languages'
import { useMintWallStore } from './MintWallStore'
import { useUxStore } from '@/store/ux'
import MintButton from '@/components/MintButtons/MintButton.vue'
const router = useRouter()
const languages = useLanguagesStore()
const wall = useMintWallStore()
const ux = useUxStore()
wall.loadNews()
function toRelativeDate(date: string) {
    const dt = DateTime.fromSQL(date, { zone: 'UTC' })
    if (dt.diffNow('days').days >= -5) {
        return dt.toRelative()
    }
    return dt.toFormat('dd.MM.yyyy')
}
function normalizeNewsContent(html: string) {
    let newsContent = html.replace(/<\/?[^>]+(>|$)/g, '')
    if (newsContent.length > 250) {
        newsContent = newsContent.substring(0, 247) + '...'
    }
    return newsContent
}
function navigateTo(url: string) {
    router.push(url)
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
        background: #f5fbfa 0% 0% no-repeat padding-box;
        border-radius: 16px;
        margin: 8px 16px;
        padding: 12px;
        color: #000000de;
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
                    color: rgb(var(--v-theme-primary));
                    font-weight: 600; // SemiBold
                    font-size: 12px;
                    text-align: right;
                    margin-right: 28px;
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
        .mint-news-title {
            font-size: 24px;
            letter-spacing: 0.18px;
            margin-bottom: 16px;
            cursor: pointer;
        }
        .mint-news-desc {
            letter-spacing: 0.18px;
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .mint-news-footer {
            margin: 16px 8px 8px 8px;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            color: rgb(var(--v-theme-secondary));
            .mint-news-like {
                font-weight: 600; // SemiBold
                color: rgb(var(--v-theme-secondary));
                background-color: rgb(var(--v-theme-primary-lighter));
            }
            .mint-news-read-more {
                text-decoration: underline;
                cursor: pointer;
            }
        }
    }
}
</style>
