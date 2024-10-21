<template>
    <ul class="nav">
        <li v-for="item in store.navOptions" :key="item" class="nav-item">
            <button
                @click="handleClick(item)"
                :class="{ 'nav-item': true, active: store.activeTab === item && store.activeView !== 'user-list' }"
            >
                {{ languages.translateListValue(item, 'kudos_navbar_item_list') }}
            </button>
        </li>
        <MintButton
            @click="store.activeView = 'user-list'"
            icon="mdi-plus"
            :text="languages.label('LBL_GIVE_KUDOS')"
            variant="primary"
            size="medium"
            class="ml-auto"
        />
    </ul>
</template>

<script setup lang="ts">
import { useLanguagesStore } from '@/store/languages'
import MintButton from '../MintButtons/MintButton.vue'
import { useMintKudosStore } from './MintKudosStore'
import { NavOption } from './types'

const store = useMintKudosStore()
const languages = useLanguagesStore()

const handleClick = (item: NavOption) => {
    store.activeTab = item
}
</script>

<style scoped lang="scss">
.nav {
    display: flex;
    align-items: center;
    flex-wrap: wrap-reverse;
    gap: 16px;
    padding: 16px;
    list-style-type: none;
    &-item {
        text-transform: uppercase;
        color: rgb(var(--v-theme-secondary));
        font-weight: bold;
        border-bottom: 2px solid transparent;
        & .active {
            border-bottom: 2px solid rgb(var(--v-theme-secondary));
        }
    }
}
</style>
