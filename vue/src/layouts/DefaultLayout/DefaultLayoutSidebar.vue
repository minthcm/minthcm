<template>
<div class="default-layout-sidebar">
    <div 
        class="navigation-scrim"
        :class="{ 'navigation-scrim-open': ux.sideMenu && !mdAndUp}"
        @click="ux.showHideSideMenu()"
    />
    <v-navigation-drawer
        class="sidebar-nav"
        :expand-on-hover="mdAndUp"
        :rail="!$vuetify.display.mdAndDown && storage.sideMenuShrinked"
        :temporary="!mdAndUp"
        :permanent="mdAndUp"
        width="260"
        color="#00000010"
        floating="true"
        rail-width="76"
        v-model="ux.sideMenu"
        :scrim="false"
    >
        <v-list
                        v-if="modules.currentModule?.name !== 'Home' && modules.currentModule?.actions"
            nav
            bg-color="primary"
            class="nav-list flex-shrink-0 py-4"
        >
            <v-list-item
                v-for="action in modules.currentModule.actions"
                :key="action.action + modules.currentModule + action.url"
                class="nav-item module-action"
                :value="action.action"
                v-bind="getLinkBinding(action)"
                :active="false"
                @click="getClickHandler(action)"
            >
                <div class="nav-title">
                    <v-icon :icon="`mdi-${action.icon}`" />
                    <span v-text="action.name" />
                </div>
            </v-list-item>
        </v-list>
        <div class="flex-grow-1" style="display: flex; flex-direction: column; overflow: auto">
            <v-text-field
                v-model="filterModulesQuery"
                class="find-module"
                :placeholder="languages.label('LBL_MINT4_FIND_MODULE')"
                variant="plain"
                density="compact"
                hide-details
            >
                <template #prepend-inner>
                    <v-fab-transition>
                        <v-icon v-if="filterModulesQuery" icon="mdi-close" @click="clearInput" />
                        <v-icon v-else icon="mdi-magnify" />
                    </v-fab-transition>
                </template>
            </v-text-field>
            <v-list
                ref="nav-list-ref"
                nav
                :class="{
                    'nav-list nav-list-blurred flex-grow-1': true,
                }"
                style="min-height: 80px"
            >
                <transition-group name="list" tag="ul">
                    <template v-if="filteredModules.length">
                        <v-list-item
                            class="nav-item"
                            v-for="filteredModule in filteredModules"
                            :key="filteredModule.name"
                            :value="filteredModule.name"
                            :data-cy="filteredModule.name"
                            :to="`/modules/${filteredModule.name}`"
                            :active="filteredModule.name === url.module"
                            color="secondary"
                            :class="{ 'v-list-item--active keyboard-hovered': selectedItem === filteredModule.name }"
                        >
                            <div style="display: flex; align-items: center; justify-content: space-between">
                                <div class="nav-title">
                                    <v-icon :icon="`${filteredModule.icon}`" />
                                    <span v-text="filteredModule.label" />
                                </div>
                                <v-menu v-if="filteredModule.name !== 'Home' && filteredModule.actions?.length">
                                    <template v-slot:activator="{ props, isActive }">
                                        <v-btn
                                            v-bind="props"
                                            @click.prevent.stop="null"
                                            class="menu-icon"
                                            :class="[isActive && 'menu-icon-active']"
                                            icon="mdi-dots-vertical"
                                            variant="text"
                                            density="compact"
                                            color="secondary"
                                        />
                                    </template>
                                    <MintMenuList :items="parseModuleActions(filteredModule.actions)" />
                                </v-menu>
                            </div>
                        </v-list-item>
                    </template>
                    <span
                        v-else
                        class="px-4 text-grey-darken-4"
                        v-text="languages.label('LBL_MINT4_NO_MODULES_FOUND')"
                    />
                </transition-group>
            </v-list>
            <v-expansion-panels class="nav-accordion" variant="accordion">
                <v-expansion-panel v-if="recents.recents?.length" bg-color="transparent">
                    <v-expansion-panel-title>
                        <v-icon class="mr-4" icon="mdi-history" />
                        <span v-text="languages.label('LBL_MINT4_RECENTLY_VIEWED')" />
                    </v-expansion-panel-title>
                    <v-expansion-panel-text>
                        <v-list nav class="nav-list">
                            <v-list-item
                                v-for="recent in recents.recents?.slice(0, 5) ?? []"
                                :key="recent.item_id"
                                class="nav-item"
                                :value="recent.item_id"
                                :to="`/modules/${recent.module_name}/DetailView/${recent.item_id}`"
                                :active="false"
                            >
                                <div class="nav-title">
                                    <v-icon :icon="modules.modules[recent.module_name]?.icon ?? 'mdi-clock'" />
                                    <span v-text="recent.item_summary" />
                                </div>
                            </v-list-item>
                        </v-list>
                    </v-expansion-panel-text>
                </v-expansion-panel>
                <v-expansion-panel v-if="favorites.favorites?.length" bg-color="transparent" elevetion="10">
                    <v-expansion-panel-title>
                        <v-icon class="mr-4" icon="mdi-heart" />
                        <span v-text="languages.label('LBL_MINT4_FAVORITE_RECORDS')" />
                    </v-expansion-panel-title>
                    <v-expansion-panel-text>
                        <v-list nav class="nav-list">
                            <v-list-item
                                v-for="favorite in favorites.favorites?.slice(0, 5) ?? []"
                                :key="favorite.id"
                                class="nav-item"
                                :value="favorite.id"
                                :to="`/modules/${favorite.module_name}/DetailView/${favorite.id}`"
                                :active="false"
                            >
                                <div class="nav-title">
                                    <v-icon :icon="modules.modules[favorite.module_name]?.icon ?? 'mdi-heart'" />
                                    <span v-text="favorite.item_summary" />
                                </div>
                            </v-list-item>
                        </v-list>
                    </v-expansion-panel-text>
                </v-expansion-panel>
            </v-expansion-panels>
        </div>
    </v-navigation-drawer>
    <div 
        class="shrinker" 
        :class="{ 'rail-mode': storage.sideMenuShrinked }"
        v-if="!$vuetify.display.mdAndDown"
    >
        <div class="shrinker-background"></div>
        <MintButton 
            class="shrinker-button" 
            variant="icon" 
            size="x-large" 
            :icon="storage.sideMenuShrinked || $vuetify.display.mdAndDown ? 'mdi-chevron-right' : 'mdi-chevron-left'" 
            @click="shrink" 
        />
    </div>
</div>

</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch, nextTick, useTemplateRef, onUnmounted } from 'vue'
import { useUrlStore } from '@/store/url'
import { useFavoritesStore } from '@/store/favorites'
import { useRecentsStore } from '@/store/recents'
import { useModulesStore, ModuleAction } from '@/store/modules'
import { useUxStore } from '@/store/ux'
import MintMenuList from '@/components/MintMenuList.vue'
import { useLanguagesStore } from '@/store/languages'
import { useRouter } from 'vue-router'
import { popupComponents } from '@/custom/components/MintPopups/CustomMintPopupsMap'
import { usePopupsStore } from '@/store/popups'
import ComponentLoader from '@/utils/componentLoader'
import MintButton from '@/components/MintButtons/MintButton.vue'
import { useLocalStorageStore } from '@/store/localStorage'
import { useDisplay } from 'vuetify'

const modules = useModulesStore()
const url = useUrlStore()
const favorites = useFavoritesStore()
const recents = useRecentsStore()
const languages = useLanguagesStore()
const popups = usePopupsStore()
const router = useRouter()
const storage = useLocalStorageStore()


const shrink = () => {
    storage.sideMenuShrinked = !storage.sideMenuShrinked
}
const ux = useUxStore()

const { mdAndUp } = useDisplay()

const filterModulesQuery = ref('')
const filteredModules = computed(() => {
    const query = filterModulesQuery.value.trim().toLowerCase()
    if (!query) {
        return modules.visibleModules
    }
    return modules.visibleModules.filter((m) => m.label.toLowerCase().includes(query))
})

const selectedItem = ref('')
const itemsKeys = computed(() => filteredModules.value.map((item) => item.name))

function parseModuleActions(actions: ModuleAction[]) {
    return actions.map((action) => ({
        title: action.name,
        url: url.fromLegacyUrl(action.url),
        icon: action.icon,
        onClickActionData: action?.onClickActionData ?? '',
    }))
}

function clearInput() {
    filterModulesQuery.value = ''
    selectedItem.value = ''
}

function getLinkBinding(action: ModuleAction){
    const targetUrl = action.url ? url.fromLegacyUrl(action.url) : ''

    if (!targetUrl || targetUrl === '/') {
        return {}
    }

    if (targetUrl.startsWith('http')) {
        return {
            href: targetUrl,
            target: '_blank',
        }
    }
    return {
        to: targetUrl,
    }
}

async function getClickHandler(action: ModuleAction) {
    if (!action.url || action.url === '/') {
        if (action?.onClickActionData?.type === 'popup' && action?.onClickActionData?.componentPath) {
            popups.showPopup({
                title: action.name,
                component: await ComponentLoader.loadComponent(action?.onClickActionData?.componentPath ?? '')
            })
        }
    }
}


function navigateToModule(moduleName: string) {
    router.push({ name: 'list', params: { module: moduleName } })
}

const navListRef = useTemplateRef('nav-list-ref')
function scrollToSelectedItem() {
    nextTick(() => {
        if (!selectedItem.value || !navListRef.value) return
        
        const navListElement = (navListRef.value as { $el: HTMLElement } | null)?.$el ?? null
        const selectedElement = navListElement?.querySelector(`[data-cy="${selectedItem.value}"]`)
        if (selectedElement) {
            selectedElement.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest',
                inline: 'nearest'
            })
        }
    })
}

function selectItem(event) {
    if (!filteredModules.value.length || filterModulesQuery.value == '') {
        selectedItem.value = ''
        return
    }
    const currentIndex = itemsKeys.value.indexOf(selectedItem.value) ?? -1
    switch (event.key) {
        case 'ArrowDown':
            event.preventDefault()
            if (currentIndex < itemsKeys.value.length - 1) {
                selectedItem.value = itemsKeys.value[currentIndex + 1]
            } else {
                selectedItem.value = itemsKeys.value[0]
            }
            scrollToSelectedItem()
            break
        case 'ArrowUp':
            event.preventDefault()
            if (currentIndex > 0) {
                selectedItem.value = itemsKeys.value[currentIndex - 1]
            } else {
                selectedItem.value = itemsKeys.value[itemsKeys.value.length - 1]
            }
            scrollToSelectedItem()
            break
        case 'Enter':
            event.preventDefault()
            if (selectedItem.value) {
                navigateToModule(selectedItem.value)
            }
            break
        case 'Escape':
            event.preventDefault()
            selectedItem.value = ''
            break
    }
}

onMounted(() => {
    document.addEventListener('keydown', (event) => selectItem(event))
})

watch(
    () => filterModulesQuery.value,
    (newValue) => {
        if (newValue === '') {
            selectedItem.value = ''
        }
    },
)

onUnmounted(() => {
    document.removeEventListener('keydown', selectItem)
})

</script>
<style lang="scss">
.sidebar-nav {
    top: var(--v-top-nav-height) !important;
    max-height: calc(100vh - var(--v-top-nav-height));
    backdrop-filter: blur(24px);
    .v-navigation-drawer__content {
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
}
.sidebar-nav.v-navigation-drawer--rail {
    .menu-icon {
        display: none;
    }
    .menu-icon.menu-icon-active {
        display: block;
    }
    .nav-accordion .v-expansion-panel-title span {
        display: none;
    }
}
.sidebar-nav.v-navigation-drawer--is-hovering {
    .menu-icon {
        display: block;
    }
    .nav-accordion .v-expansion-panel-title span {
        display: block;
    }
}
</style>
<style scoped lang="scss">
.nav-list {
    padding-left: 0px;
    padding-right: 16px;
    overflow-y: auto !important;
    -ms-overflow-style: none;
    scrollbar-width: none;
    &::-webkit-scrollbar {
        display: none;
    }
}

.find-module {
    padding: 12px 24px 12px 24px;
    flex: 0;
    color: rgb(var(--v-theme-secondary));
    .v-icon {
        opacity: 1;
        margin-right: 10px;
    }
    :deep(.v-field__input) {
        padding-top: 8px;
    }
}

.nav-accordion {
    white-space: nowrap;
    box-shadow: 0 0 1rem #0003;
    color: rgb(var(--v-theme-secondary));
    font-weight: 600;
    :deep(.v-expansion-panel) {
        border-radius: 0px;
    }
    :deep(.v-expansion-panel__shadow) {
        display: none;
    }
    :deep(.v-expansion-panel-text__wrapper) {
        padding: 0px;
    }
    :deep(.v-expansion-panel-title) {
        padding: 8px 24px;
    }
    :deep(.v-list-item-title) {
        font-weight: 600;
    }
}

.nav-item {
    transition: all 150ms ease-out;
    border-radius: 0px 20px 20px 0px;
    padding-left: 0px;
    padding-right: 0px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    :deep(.v-list-item__content) {
        width: 100%;
    }
    .nav-title {
        color: rgb(var(--v-theme-secondary));
        padding-left: 24px;
        white-space: nowrap;
        overflow: hidden;
        display: flex;
        align-items: center;
        gap: 16px;
        span {
            text-overflow: ellipsis;
            overflow: hidden;
        }
    }
    .menu-icon {
        margin: 0 8px;
        margin-left: auto;
        opacity: 0 !important;
        transition: all 250ms ease-in-out;
    }
    .menu-icon.menu-icon-active {
        opacity: 1 !important;
    }

    &:hover,
    &.keyboard-hovered {
        background: #0000001f;
        .nav-title {
            transform: translateX(-8px);
            color: rgb(var(--v-theme-secondary-dark));
        }
        .menu-icon {
            opacity: 1 !important;
        }
    }
}

.nav-item.module-action {
    &:hover {
        background: #0004;
    }
    .nav-title {
        color: #ffffffaf;
    }
    &:hover .nav-title {
        color: #fff;
    }
}

.nav-title {
    transition: all 150ms ease-in-out;
    color: #ffffffaf;
    font-weight: 600;
    font-size: 16px;
}

.nav-list-blurred {
    .v-list-item-title {
        font-size: 1rem;
        font-weight: 600;
        color: rgb(var(--v-theme-secondary));
        line-height: 1.5;
    }
    .v-icon {
        opacity: 1;
    }
}

.shrinker {
    position: fixed;
    top: 50%;
    left: calc(260px - 17px);
    transform: translateY(-50%);
    width: 34px;
    height: 34px;
    cursor: pointer;
    transition: left 0.3s ease;
    z-index: 1005;
    
    &.rail-mode {
        left: calc(76px - 17px);
    }
}

.shrinker-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 34px;
    height: 34px;
    background: rgba(0, 0, 0, 0.039);
    border-radius: 50%;
    backdrop-filter: blur(24px);
    clip-path: polygon(50% 0, 100% 0, 100% 100%, 50% 100%);
    z-index: 0;
}

.shrinker-button {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1;
}

.default-layout-sidebar {
    &:hover {
        .shrinker {
            left: calc(260px - 17px);
        }
    }

    &:hover,
    &:has(.shrinker:hover) {
        .v-navigation-drawer--rail {
            transform: translateX(0) !important;
            width: 260px !important;
        }
    }
}

.navigation-scrim {
    background-color: rgba(0, 0, 0, 0);
    position: fixed;
    top: var(--v-top-nav-height);
    left: 0;
    right: 0;
    height: calc(100vh - var(--v-top-nav-height));
    z-index: 1004;
    transition: left 0.2s ease, background-color 0.2s ease;
    pointer-events: none;

    &.navigation-scrim-open {
        left: 260px;
        width: auto;
        background-color: rgba(0, 0, 0, 0.3);
        pointer-events: auto;
    }
}
</style>

