<template>
    <ul class="nav">
        <li v-for="item in store.navOptions" :key="item" class="nav-item">
            <button
                @click="handleClick(item)"
                :class="{ 'nav-item': true, active: store.activeTab === item && store.activeView !== 'user-list' }"
                @keydown.enter="handleClick(item)"
                @keydown.space="handleClick(item)"
                :name="'kudos-nav-item-' + item"
                :id="'kudos-nav-item-' + item"
                :aria-label="languages.label('LBL_KUDOS_NAVBAR_ITEM_' + item.toUpperCase())"
                :aria-description="languages.label('LBL_KUDOS_NAVBAR_ITEM_' + item.toUpperCase() + '_COMMENT')"
                :aria-describedby="'kudos-nav-item-' + item + '-help'"
            >
                {{ languages.translateListValue(item, 'kudos_navbar_item_list') }}
            </button>
            <p :id="'kudos-nav-item-' + item + '-help'" :name="'kudos-nav-item-' + item + '-help'" hidden>{{ languages.label('LBL_KUDOS_NAVBAR_ITEM_' + item.toUpperCase() + '_COMMENT') }}</p>
        </li>
        <MintButton
            @click="store.activeView = 'user-list'"
            icon="mdi-plus"
            :text="$vuetify.display.mdAndDown ? '' : languages.label('LBL_GIVE_KUDOS')"
            variant="primary"
            size="medium"
            class="ml-auto"
            @keydown.enter="store.activeView = 'user-list'"
            @keydown.space="store.activeView = 'user-list'"
            name='kudos-nav-item-give'
            id='kudos-nav-item-give'
            :aria-label="languages.label('LBL_GIVE_KUDOS')"
            :aria-description="languages.label('LBL_GIVE_KUDOS_COMMENT')"
            aria-describedby="kudos-nav-item-give-help"
        />
        <p id="kudos-nav-item-give-help" name="kudos-nav-item-give-help" hidden>{{ languages.label('LBL_GIVE_KUDOS_COMMENT') }}</p>
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
