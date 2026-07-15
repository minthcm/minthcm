<template>
    <div :class="{
        'default-layout-sidebar': !shrinked && !mdAndDown,
        'default-layout-sidebar-railed': shrinked && !mdAndDown,
        'default-layout-sidebar-mobile': mdAndDown,
        'no-hover': disableHover
    }">
    <v-navigation-drawer
        :rail="!mdAndDown && shrinked"
        permanent
        width="260"
        color="transparent"
        floating="true"
        rail-width="76"
        v-model="ux.sideMenu"
        :scrim="false"
        @mouseenter="onSidebarNavEnter"
        @mouseleave="onSidebarNavLeave"
        :class="{
            'no-hover': disableHover,
            'sidebar-nav': true,
        }"
    >
        <v-list
                        v-if="modules.currentModule?.name !== 'Home' && modules.currentModule?.actions"
            nav
            bg-color="primary"
            class="nav-list flex-shrink-0 py-4"
            ref="module-actions-ref"
        >
            <v-list-item
                v-for="action in modules.currentModule.actions"
                :key="action.action + modules.currentModule + action.url"
                class="nav-item module-action"
                :value="action.action"
                v-bind="getLinkBinding(action)"
                :active="false"
                tabindex="0"
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
                ref="searchInputRef"
                v-model="filterModulesQuery"
                class="find-module"
                :placeholder="languages.label('LBL_MINT4_FIND_MODULE')"
                variant="plain"
                density="compact"
                hide-details
                :name="'find-module-input'"
                :id="'find-module-input'"
                :aria-label="languages.label('LBL_MINT4_FIND_MODULE')"
                :aria-description="languages.label('LBL_MINT4_FIND_MODULE_COMMENT')"
                :aria-describedby="'find-module-input-help'"
            >
                <template #prepend-inner>
                    <v-fab-transition>
                        <v-icon v-if="filterModulesQuery" icon="mdi-close" @click="clearInput" />
                        <v-icon v-else icon="mdi-magnify" />
                    </v-fab-transition>
                    <p id="find-module-input-help" name="find-module-input-help" hidden>{{languages.label('LBL_MINT4_FIND_MODULE_COMMENT')}}</p>
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
                                :name="'module-' + filteredModule.name + '-button'"
                                :id="'module-' + filteredModule.name + '-button'"
                                :aria-label="filteredModule.label"
                                :aria-description="filteredModule.label"
                                :aria-describedby="'module-' + filteredModule.name + '-button-help'"
                                :tabindex="0"
                                @keydown.space="handleModuleItemSpace($event, filteredModule.name)"
                                @keydown.enter="handleModuleItemEnter($event, filteredModule.name)"
                        >
                            <p :id="'module-' + filteredModule.name + '-button-help'" :name="'module-' + filteredModule.name + '-button-help'" hidden>{{filteredModule.label}}</p>
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
                <v-expansion-panel 
                    v-if="recents.recents?.length" 
                    bg-color="transparent"
                    name="recently-viewed-panel"
                    id="recently-viewed-panel"
                    :aria-label="languages.label('LBL_MINT4_RECENTLY_VIEWED')"
                    :aria-description="languages.label('LBL_MINT4_RECENTLY_VIEWED_COMMENT')"
                    :aria-describedby="'recently-viewed-panel-help'"
                >
                    <v-expansion-panel-title>
                        <v-icon class="mr-4" icon="mdi-history" />
                        <span v-text="languages.label('LBL_MINT4_RECENTLY_VIEWED')" />
                        <p id="recently-viewed-panel-help" name="recently-viewed-panel-help" hidden>{{languages.label('LBL_MINT4_RECENTLY_VIEWED_COMMENT')}}</p>
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
                                :tabindex="0"
                            >
                                <div class="nav-title">
                                    <v-icon :icon="modules.modules[recent.module_name]?.icon ?? 'mdi-clock'" />
                                    <span v-text="recent.item_summary" />
                                </div>
                            </v-list-item>
                        </v-list>
                    </v-expansion-panel-text>
                </v-expansion-panel>
                <v-expansion-panel 
                    v-if="favorites.favorites?.length" 
                    bg-color="transparent" 
                    elevetion="10"
                    name="favorite-records-panel"
                    id="favorite-records-panel"
                    :aria-label="languages.label('LBL_MINT4_FAVORITE_RECORDS')"
                    :aria-description="languages.label('LBL_MINT4_FAVORITE_RECORDS_COMMENT')"
                    :aria-describedby="'favorite-records-panel-help'"
                >
                    <v-expansion-panel-title>
                        <v-icon class="mr-4" icon="mdi-heart" />
                        <span v-text="languages.label('LBL_MINT4_FAVORITE_RECORDS')" />
                        <p id="favorite-records-panel-help" name="favorite-records-panel-help" hidden>{{languages.label('LBL_MINT4_FAVORITE_RECORDS_COMMENT')}}</p>
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
                                :tabindex="0"
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
        :class="{ 
            'rail-mode': shrinked, 
            'alternative-color': useAlternativeColor,
        }"
        v-if="!$vuetify.display.mdAndDown"
        @mouseenter="onShrinkerEnter"
    >
        <MintButton 
            class="shrinker-button" 
            :class="{
                'no-hover': disableHover,
                'alternative-color': useAlternativeColor,
            }"
            variant="icon" 
            size="x-large" 
            :icon="shrinked || $vuetify.display.mdAndDown ? 'mdi-chevron-right' : 'mdi-chevron-left'" 
            @click="shrink" 
            ref="shrinker-button-ref"
            @keydown.enter.prevent="shrink"
            @keydown.space.prevent="shrink"
            :name="'toggle-side-menu-button'"
            :id="'toggle-side-menu-button'"
            :aria-label="languages.label('LBL_MINT_TOGGLE_SIDE_MENU')"
            :aria-description="languages.label('LBL_MINT_TOGGLE_SIDE_MENU_COMMENT')"
            :aria-describedby="'toggle-side-menu-button-help'"
        />
        <p id="toggle-side-menu-button-help" name="toggle-side-menu-button-help" hidden>{{languages.label('LBL_MINT_TOGGLE_SIDE_MENU_COMMENT')}}</p>
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

const shrinked = ref(storage.sideMenuShrinked)
const disableHover = ref(false)
const sidebarNavHovered = ref(false)
let clearSidebarHoverTimeout: ReturnType<typeof setTimeout> | null = null

function onSidebarNavEnter() {
    if (clearSidebarHoverTimeout) {
        clearTimeout(clearSidebarHoverTimeout)
        clearSidebarHoverTimeout = null
    }
    sidebarNavHovered.value = true
}

function onSidebarNavLeave() {
    clearSidebarHoverTimeout = setTimeout(() => {
        sidebarNavHovered.value = false
    }, 150)
}

function onShrinkerEnter() {
    if (sidebarNavHovered.value && shrinked.value) {
        if (clearSidebarHoverTimeout) {
            clearTimeout(clearSidebarHoverTimeout)
            clearSidebarHoverTimeout = null
        }
    }
}

const shrink = () => {
    shrinked.value = !shrinked.value
    sidebarNavHovered.value = false
    storage.sideMenuShrinked = shrinked.value

    if (clearSidebarHoverTimeout) {
        clearTimeout(clearSidebarHoverTimeout)
        clearSidebarHoverTimeout = null
    }
}

const moduleActionsRef = useTemplateRef('module-actions-ref')
const moduleActionsHeight = ref(0)
const shrinkerButtonRef = useTemplateRef('shrinker-button-ref')
const shrinkerButtonPosition = ref(0)

const useAlternativeColor = computed(() => {
    return moduleActionsHeight.value > shrinkerButtonPosition.value
})

function calculateModuleActionsAndShrinkerHeight() {
    nextTick(() => {
        if (moduleActionsRef.value && modules.currentModule?.actions) {
            const element = moduleActionsRef.value.$el || moduleActionsRef.value
            moduleActionsHeight.value = element.offsetHeight
        } else {
            moduleActionsHeight.value = 0
        }

        if(shrinkerButtonRef.value) {
            const element = shrinkerButtonRef.value.$el || shrinkerButtonRef.value
            shrinkerButtonPosition.value = element.getBoundingClientRect().top
        } else {
            shrinkerButtonPosition.value = 0
        }
    })
}
const ux = useUxStore()

const { mdAndUp, mdAndDown } = useDisplay()

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

function handleModuleItemSpace(event: KeyboardEvent, moduleName: string) {
    const target = event.target as HTMLElement
    if (target.closest('button')) {
        return
    }
    event.preventDefault()
    navigateToModule(moduleName)
}

function handleModuleItemEnter(event: KeyboardEvent, moduleName: string) {
    const target = event.target as HTMLElement
    if (target.closest('button')) {
        return
    }
    event.preventDefault()
    navigateToModule(moduleName)
}

const searchInputRef = useTemplateRef('searchInputRef')
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

function selectItem(event: KeyboardEvent) {
    const searchEl = (searchInputRef.value as any)?.$el as HTMLElement | null
    if (!searchEl?.contains(document.activeElement)) {
        return
    }
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
        case ' ':
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

const keydownHandler = (event: KeyboardEvent) => selectItem(event)

onMounted(() => {
    document.addEventListener('keydown', keydownHandler)
    calculateModuleActionsAndShrinkerHeight()
})

onUnmounted(() => {
    document.removeEventListener('keydown', keydownHandler)
})

watch(
    () => filterModulesQuery.value,
    (newValue) => {
        if (newValue === '') {
            selectedItem.value = ''
        }
    },
)

watch(
    () => modules.currentModule?.name,
    () => {
        calculateModuleActionsAndShrinkerHeight()
    }
)

// On mobile the sidebar is an overlay; collapse it back once the user picks an
// option and navigates somewhere (module, recent, favorite).
watch(
    () => router.currentRoute.value.fullPath,
    () => {
        if (mdAndDown.value) {
            ux.sideMenu = false
        }
    }
)
</script>
<style lang="scss">
.sidebar-nav {
    top: var(--v-top-nav-height) !important;
    max-height: calc(100vh - var(--v-top-nav-height));
    .v-navigation-drawer__content {
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
}
.default-layout-sidebar {
    transition: all 0.3s ease;
    &::before {
        top: var(--v-top-nav-height) !important;
        left: 0;
        width: 277px;
        height: calc(100vh - var(--v-top-nav-height));
        content: '';
        backdrop-filter: blur(24px);
        background: rgba(0, 0, 0, 0.039);
        z-index: 0;
        position: absolute;
        transition: width 0.3s ease, mask 0.3s ease;
        mask: 
            radial-gradient(
                circle 17px at 260px calc(50vh - var(--v-top-nav-height)),
                black 17px, 
                transparent 17px
            ),
            linear-gradient(
                to right,
                black 0px 260px,
                transparent 260px 277px
            );
        mask-composite: add;
    }
}
.default-layout-sidebar-railed {
    transition: all 0.3s ease;
    
    // The frosted backdrop lives on the drawer content itself — not a wrapper
    // ::before with an animated mask — so it always matches the drawer's real
    // width (76px rail or 260px expanded-on-hover) and fully covers the menu,
    // instead of leaving page content bleeding through where the mask fails to
    // expand in sync with the drawer.
    &::before {
        content: none;
    }

    .sidebar-nav .v-navigation-drawer__content {
        backdrop-filter: blur(24px);
        background: rgba(0, 0, 0, 0.039);
    }
}

// On mobile the sidebar has only two states: fully hidden or fully expanded
// (no icon rail, no hover expansion). The frosted wrapper ::before is not used
// here — instead the backdrop lives on the drawer itself, so it appears only
// while the drawer is open and never leaks a strip over the page background.
.default-layout-sidebar-mobile {
    .sidebar-nav .v-navigation-drawer__content {
        backdrop-filter: blur(24px);
        background: rgba(var(--v-theme-surface), 0.85);
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
}

// While the rail is collapsed the highlight should fill the whole row, edge to
// edge, with no rounding — the flush-left rounded lozenge only makes sense in the
// wide expanded menu. Drop the right inset and radius, and hide the labels
// explicitly so the full-width row never reveals a sliver of module text (they
// are otherwise only clipped, not hidden). Everything comes back on hover.
.default-layout-sidebar-railed:not(:hover) {
    .nav-list {
        padding-right: 0;
    }
    .nav-item {
        border-radius: 0;
    }
    .nav-title span {
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
    :deep(.v-field:has(input:focus-visible)) {
        outline: 2px solid rgb(var(--v-theme-secondary));
        outline-offset: -2px;
        border-radius: 4px;
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
    :deep(.v-expansion-panel-title:focus-visible) {
        outline: 2px solid rgb(var(--v-theme-secondary));
        outline-offset: -2px;
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
        &:focus-visible {
            opacity: 1 !important;
            outline: 2px solid rgb(var(--v-theme-secondary));
            border-radius: 4px;
        }
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
    &:focus-visible {
        outline: 2px solid rgb(var(--v-theme-secondary));
        outline-offset: -2px;
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
    &:focus-visible {
        box-shadow: inset 0 0 0 2px rgba(255, 255, 255, 0.6);
        background: #0004;
        .nav-title {
            color: #fff;
        }
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
    transition: left 0.5s ease;
    z-index: 1005;
    border-radius: 50%;
    
    &.rail-mode {
        left: calc(76px - 17px);
    }
}

.shrinker-button {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 5;
    &:focus-visible {
        outline: 2px solid rgb(var(--v-theme-secondary));
        outline-offset: 2px;
        border-radius: 50%;
    }
}

.default-layout-sidebar,
.default-layout-sidebar-railed {
    &:hover {
        .shrinker {
            left: calc(260px - 17px);
        }
    }

    .v-navigation-drawer--rail {
        transition: transform 0.5s ease, width 0.5s ease !important;
    }

    &:hover,
    &:has(.shrinker:hover) {
        .v-navigation-drawer--rail {
            width: 260px !important;
            transition: all 0.5s ease;
        }
    }
}

.no-hover {
    pointer-events: none !important;
}

.shrinker
{
    &.alternative-color
    {
        background-color: rgb(var(--v-theme-primary)) !important;
    }
}

.shrinker-button.alternative-color
{
    color: rgba(255, 255, 255, 0.686) !important;
    &:hover {
        color: white;
    }
}
</style>

