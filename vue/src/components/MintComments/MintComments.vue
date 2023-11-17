<template>
    <div class="mint-comments">
        <v-fade-transition>
            <div
                v-if="store.isLoading || store.isInitialLoading"
                :class="{
                    'mint-comments-overlay': true,
                    'mint-comments-overlay-initial': store.isInitialLoading,
                }"
            >
                <v-progress-circular size="100" width="7" color="primary" indeterminate />
            </div>
        </v-fade-transition>
        <template v-if="store.pinnedThreads?.length">
            <h1 class="mb-2">{{ languages.label('LBL_MINT4_COMMENTS_PINNED_TITLE') }}</h1>
            <div class="mint-comments-threads">
                <MintCommentsMessage v-for="thread in store.pinnedThreads" :key="thread.id" :comment="thread" pinned />
            </div>
        </template>
        <h1 class="mb-2">{{ languages.label('LBL_MINT4_COMMENTS_TITLE') }}</h1>
        <div v-if="store.threads?.length" class="mint-comments-threads">
            <MintCommentsMessage v-for="thread in store.threads" :key="thread.id" :comment="thread" />
        </div>
        <div
            v-if="!store.access.add && !store.threads?.length"
            v-text="languages.label('LBL_MINT4_COMMENTS_NO_COMMENTS')"
        />
        <MintCommentsEditor v-if="store.access.add" mode="new" />
    </div>
</template>

<script setup lang="ts">
import MintCommentsMessage from './MintCommentsMessage.vue'
import { useMintCommentsStore } from './MintCommentsStore'
import MintCommentsEditor from './MintCommentsEditor.vue'
import { useLanguagesStore } from '@/store/languages'

const languages = useLanguagesStore()

const store = useMintCommentsStore()
store.fetchInitialData()
</script>

<style scoped lang="scss">
.mint-comments {
    font-family: Barlow;
    position: relative;
    padding: 32px;
    min-width: 740px;
    max-width: 740px;
    display: flex;
    flex-direction: column;
    gap: 48px;
}

.mint-comments-overlay {
    position: absolute;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 1000;
    background: #0002;
    display: flex;
    justify-content: center;
    align-items: center;

    &.mint-comments-overlay-initial {
        background: #fff;
    }
}

.mint-comments-threads {
    display: flex;
    flex-direction: column;
    width: 100%;
    gap: 32px;
}
</style>
