<template>
    <div class="pagination-container">
        <v-icon color="secondary" @click="changePageNumber('first', tableName)" :disabled="shouldDisable('previous')"
            >mdi-page-first</v-icon
        >
        <v-icon color="secondary" @click="changePageNumber('previous', tableName)" :disabled="shouldDisable('previous')"
            >mdi-chevron-left</v-icon
        >
        <span v-text="pageText" color="secondary" />
        <v-icon color="secondary" @click="changePageNumber('next', tableName)" :disabled="shouldDisable('next')"
            >mdi-chevron-right</v-icon
        >
        <v-icon color="secondary" @click="changePageNumber('last', tableName)" :disabled="shouldDisable('next')"
            >mdi-page-last</v-icon
        >
    </div>
</template>

<script setup lang="ts">
import { defineProps, computed, defineEmits } from 'vue'
import { useLanguagesStore } from '@/store/languages'

interface Props {
    tableName: string
    total: number
    paginateBy: number
    page: number
}

const props = defineProps<Props>()
const emit = defineEmits(['pageChanged'])
const languages = useLanguagesStore()

const pageText = computed(() => {
    let page = Number(props.page)
    let paginateBy = Number(props.paginateBy)
    const firstRecord = page * paginateBy + 1
    const lastRecord = Math.min((page + 1) * paginateBy, props.total)
    const pageText = `${firstRecord} - ${lastRecord} ${languages.label('LBL_ESLIST_PAGE_TEXT')} ${props.total}`
    return pageText
})

const changePageNumber = (toPage: string, tableName: string) => {
    let page = 0
    const lastPage = Math.ceil(props.total / props.paginateBy) - 1
    switch (toPage) {
        case 'first':
            break
        case 'last':
            page = lastPage
            break
        case 'next':
            page = Number(props.page) + 1
            break
        case 'previous':
            page = Number(props.page) - 1
            break
    }
    emit('pageChanged', page, tableName, props.paginateBy)
}

const shouldDisable = (direction: string) => {
    let total = Number(props.total)
    let page = Number(props.page)
    let paginateBy = Number(props.paginateBy)
    switch (direction) {
        case 'next':
            return total <= (page + 1) * paginateBy
        case 'previous':
            return page <= 0
        default:
            return false
    }
}
</script>

<style scoped lang="scss">
.pagination-container {
    display: flex;
    float: right;
    margin-top: 16px;
    margin-right: 22px;
}
</style>