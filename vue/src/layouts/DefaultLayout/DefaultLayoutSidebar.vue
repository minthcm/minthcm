<template>
    <v-navigation-drawer
        class="sidebar-nav"
        expand-on-hover
        :rail="$vuetify.display.mdAndDown"
        permanent
        width="260"
        :color="$vuetify.display.mdAndDown ? '#d5e6e4dd' : '#00000010'"
        :floating="$vuetify.display.lgAndUp"
        rail-width="76"
    >
        <v-list
            v-if="modules.activeModule?.name !== 'Home' && modules.activeModule?.actions"
            nav
            bg-color="primary"
            class="nav-list flex-shrink-0 py-4"
        >
            <v-list-item
                v-for="action in modules.activeModule.actions"
                :key="action.action"
                class="nav-item module-action"
                :value="action.action"
                :to="url.fromLegacyUrl(action.url)"
                :active="false"
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
                    <v-icon icon="mdi-magnify" />
                </template>
            </v-text-field>
            <v-list nav class="nav-list nav-list-blurred flex-grow-1" style="min-height: 80px">
                <transition-group name="list" tag="ul">
                    <template v-if="filteredModules.length">
                        <v-list-item
                            class="nav-item"
                            v-for="filteredModule in filteredModules"
                            :key="filteredModule.name"
                            :value="filteredModule.name"
                            :data-cy="filteredModule.name"
                            :to="
                                ![
                                    'Calls',
                                    'Candidates',
                                    'Meetings',
                                    'Tasks',
                                    'Candidatures',
                                    'Positions',
                                    'Recruitments',
                                ].includes(filteredModule.name)
                                    ? `/modules/${filteredModule.name}`
                                    : `/modules/${filteredModule.name}/ESListView`
                            "
                            :active="filteredModule.name === url.module"
                            color="secondary"
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
                    <div class="px-4" v-else>No modules found</div>
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
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useUrlStore } from '@/store/url'
import { useFavoritesStore } from '@/store/favorites'
import { useRecentsStore } from '@/store/recents'
import { useModulesStore, ModuleAction } from '@/store/modules'
import MintMenuList from '@/components/MintMenuList.vue'
import { useLanguagesStore } from '@/store/languages'

const modules = useModulesStore()
const url = useUrlStore()
const favorites = useFavoritesStore()
const recents = useRecentsStore()
const languages = useLanguagesStore()

const filterModulesQuery = ref('')
const filteredModules = computed(() => {
    const query = filterModulesQuery.value.trim().toLowerCase()
    if (!query) {
        return modules.visibleModules
    }
    return modules.visibleModules.filter((m) => m.label.toLowerCase().includes(query))
})

function parseModuleActions(actions: ModuleAction[]) {
    return actions.map((action) => ({
        title: action.name,
        url: url.fromLegacyUrl(action.url),
        icon: action.icon,
    }))
}
</script>
<style lang="scss">
.sidebar-nav {
    top: var(--v-top-nav-height) !important;
    max-height: calc(100vh - var(--v-top-nav-height));
    backdrop-filter: blur(10px);
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
    &:hover {
        background: #0000001f;
    }
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
    &:hover .nav-title {
        transform: translateX(-8px);
        color: rgb(var(--v-theme-secondary-dark));
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
    &:hover .menu-icon {
        opacity: 1 !important;
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
</style>
