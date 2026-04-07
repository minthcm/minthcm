<template>
    <v-list class="mint-menu-list" nav density="compact" color="secondary">
        <v-list-item v-for="item in processedItems" :name="item.actionKey || null" :id="item.actionKey || null" :key="item.title" @click="item.onClick" :active="false" v-bind="item.url && item.url !== '/' ? { to: item.url } : { tag: 'button' }" :aria-label="languages.label(item.title)">
            <template v-if="item.icon" #prepend>
                <span style="font-size: 11px"><v-icon :icon="getIcon(item.icon)" /></span>
            </template>
            <v-list-item-title>
                {{ languages.label(item.title) }}
            </v-list-item-title>
        </v-list-item>
    </v-list>
</template>

<script setup lang="ts">
import { usePopupsStore } from '@/store/popups'
import { computed, defineProps } from 'vue'
import { useLanguagesStore } from '@/store/languages'
import ComponentLoader from '@/utils/componentLoader'

export interface MenuListOnClickActionData {
    type?: string
    componentPath?: string
}

export interface MenuListItem {
    title: string
    icon?: string
    url?: string
    onClick?: (() => Promise<void>) | (() => void)
    onClickActionData?: MenuListOnClickActionData
    actionKey?: string | null
}

interface Props {
    items: MenuListItem[]
}
const languages = useLanguagesStore()

const props = defineProps<Props>()
const popups = usePopupsStore()

const processedItems = computed(() =>
  props.items.map((item) => {
    if (!item.url || item.url === '' || item.url === '/') {
      if (item?.onClickActionData?.type === 'popup' && item?.onClickActionData?.componentPath) {
        item.onClick = async () => {
          popups.showPopup(
            {
                title: item.title,
                component: await ComponentLoader.loadComponent(item?.onClickActionData?.componentPath ?? '')
            }
          )
        }
      }
    }
    return { ...item }
  })
)

//TODO: global function?
function getIcon(icon: string) {
    if (!icon) {
        return ''
    }
    if (icon.slice(0, 4) === 'mdi-') {
        return icon // Material Design Icons
    }
    if (icon.slice(0, 3) === 'fi-') {
        return icon // Flag Icons
    }
    return `mdi-${icon}` // return mdi by default
}
</script>

<style lang="scss">
.mint-menu-list {
    min-width: 186px;
    padding: 2px 0px;
    color: rgb(var(--v-theme-secondary));
    .v-list-item {
        margin: 0px;
        padding: 0px 12px;
        &:hover {
            color: rgb(var(--v-theme-secondary-dark));
            background: rgb(var(--v-theme-primary-light));
        }
    }
    .v-list-item-title {
        font-size: 14px;
        font-weight: 600;
    }
    .v-icon {
        opacity: 1;
        margin-inline-end: 8px;
    }
}
.mint-menu-list button.v-list-item {
    width: 100%; 
    text-align: left;
}
</style>
