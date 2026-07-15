<template>
    <v-list ref="listRef" class="mint-menu-list" nav density="compact" color="secondary">
        <v-list-item v-for="item in processedItems" :name="item.actionKey || null" :id="item.actionKey || null" :key="item.title" @click="item.onClick" @keydown.space.prevent="handleItemSpace(item)" :active="false" v-bind="item.url && item.url !== '/' ? { to: item.url } : { tag: 'button' }" :aria-label="languages.label(item.title)">
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
import { computed, defineProps, onMounted, onUnmounted, useTemplateRef } from 'vue'
import { useLanguagesStore } from '@/store/languages'
import ComponentLoader from '@/utils/componentLoader'
import { useRouter } from 'vue-router'

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
const router = useRouter()

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

function handleItemSpace(item: MenuListItem) {
    if (item.url && item.url !== '/') {
        router.push(item.url)
    } else if (item.onClick) {
        item.onClick()
    }
}

function handleTabCapture(e: KeyboardEvent) {
    if (e.key !== 'Tab') return
    const listComp = listRef.value
    if (!listComp) return
    const list = (listComp as any)?.$el as HTMLElement | null
    if (!list) return
    const focusable = Array.from(list.querySelectorAll<HTMLElement>('a.v-list-item, button.v-list-item'))
    if (!focusable.length) return
    const idx = focusable.indexOf(document.activeElement as HTMLElement)
    if (idx === -1) return
    e.preventDefault()
    e.stopImmediatePropagation()
    const next = e.shiftKey
        ? (idx <= 0 ? focusable.length - 1 : idx - 1)
        : (idx >= focusable.length - 1 ? 0 : idx + 1)
    focusable[next]?.focus()
}

const listRef = useTemplateRef<HTMLElement>('listRef')

onMounted(() => {
    document.addEventListener('keydown', handleTabCapture, true)
})

onUnmounted(() => {
    document.removeEventListener('keydown', handleTabCapture, true)
})

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
