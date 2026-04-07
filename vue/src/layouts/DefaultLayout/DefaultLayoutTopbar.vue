<template>
    <nav class="top-bar">
        <div 
            :class="{
                'img-logo': true,
                'img-logo-railed': mdAndDown && !ux.sideMenu,
            }" 
            @click="logoOnClick"
        >
            <img v-if="(mdAndDown && ux.sideMenu) || !mdAndDown" src="../../assets/mint_logo_white.svg" />
            <v-icon v-else>mdi-menu</v-icon>
        </div>
        <DefaultLayoutSearch v-if="!mdAndDown || !ux.sideMenu" />
        <div class="flex-grow-1" />
        <v-menu offset="16">
            <template v-slot:activator="{ props, isActive }">
                <MintButton
                    v-bind="props"
                    variant="nav"
                    icon="mdi-plus"
                    :active="isActive"
                    v-if="!mdAndDown || !ux.sideMenu"
                    name="quick-create-menu-button"
                    id="quick-create-menu-button"
                    :aria-label="languages.label('LBL_MINT_QUICK_CREATE')"
                    :aria-description="languages.label('LBL_MINT_QUICK_CREATE_COMMENT')"
                    aria-describedby="quick-create-menu-button-help"
                />
                <p id="quick-create-menu-button-help" name="quick-create-menu-button-help" hidden>{{ languages.label('LBL_MINT_QUICK_CREATE_COMMENT') }}</p>
            </template>
            <MintMenuList :items="quickCreateMenu" />
        </v-menu>
        <MintButton
            icon="mdi-apps"
            variant="nav"
            @click="showModulesPopup"
            :active="!!popups.popups.find((p) => p.component === DefaultLayoutModulesPopup)"
            v-if="!mdAndDown"
            @keydown.enter="showModulesPopup"
            @keydown.space="showModulesPopup"
            name="all-modules-popup-button"
            id="all-modules-popup-button"
            :aria-label="languages.label('LBL_MINT_ALL_MODULES')"
            :aria-description="languages.label('LBL_MINT_ALL_MODULES_COMMENT')"
            aria-describedby="all-modules-popup-button-help"
        />
        <p id="all-modules-popup-button-help" name="all-modules-popup-button-help" hidden>{{ languages.label('LBL_MINT_ALL_MODULES_COMMENT') }}</p>

        <v-menu v-model="alertsMenu" offset="16" :close-on-content-click="false" v-if="!mdAndDown || !ux.sideMenu">
            <template v-slot:activator="{ props, isActive }">
                <v-badge
                    v-bind="props"
                    :content="alerts.unreadFilteredAlertsCountText"
                    color="error"
                    location="bottom end"
                    :model-value="alerts.unreadFilteredAlertsCount > 0"
                >
                    <MintButton 
                        icon="mdi-bell" 
                        variant="nav" 
                        :active="isActive" 
                        name="alerts-menu-button"
                        id="alerts-menu-button"
                        :aria-label="languages.label('LBL_MINT_ALERTS')"
                        :aria-description="languages.label('LBL_MINT_ALERTS_COMMENT')"
                        aria-describedby="alerts-menu-button-help"
                    />
                    <p id="alerts-menu-button-help" name="alerts-menu-button-help" hidden>{{ languages.label('LBL_MINT_ALERTS_COMMENT') }}</p>
                </v-badge>
            </template>
            <DefaultLayoutAlerts @close="alertsMenu = false" />
        </v-menu>
        <DefaultLayoutUser v-if="!mdAndDown || !ux.sideMenu" />
        <div />
    </nav>
</template>

<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import { useBackendStore } from '@/store/backend'
import { useAlertsStore } from '@/store/alerts'
import { usePopupsStore } from '@/store/popups'
import { useModulesStore } from '@/store/modules'
import { useLanguagesStore } from '@/store/languages'
import { useUxStore } from '@/store/ux'
import DefaultLayoutAlerts from './DefaultLayoutAlerts.vue'
import DefaultLayoutUser from './DefaultLayoutUser.vue'
import MintMenuList, { MenuListItem } from '@/components/MintMenuList.vue'
import MintButton from '@/components/MintButtons/MintButton.vue'
import DefaultLayoutModulesPopup from './DefaultLayoutModulesPopup.vue'
import DefaultLayoutSearch from './DefaultLayoutSearch.vue'
import { useRoute } from 'vue-router'
import router from '@/router'
import { useDisplay } from 'vuetify'

const backend = useBackendStore()
const alerts = useAlertsStore()
const popups = usePopupsStore()
const modules = useModulesStore()
const languages = useLanguagesStore()
const route = useRoute();
const ux = useUxStore();
const { mdAndDown } = useDisplay();

const alertsMenu = ref(false)

const quickCreateMenu = computed<MenuListItem[]>(() => {
    if (!backend.initData?.quick_create) {
        return []
    }
    var quick_create = []
    for (let qc of backend.initData.quick_create) {
        if (
            backend.initData.modules[qc.module].acl.access == 89 &&
            [75, 80, 90, 99].includes(backend.initData.modules[qc.module].acl.edit)
        ) {
            quick_create.push(qc)
        }
    }

    return quick_create.map((qc) => ({
        title: qc.name,
        icon: modules.modules[qc.module]?.icon ?? 'mdi-pencil',
        url: `/modules/${qc.module}/EditView`,
    }))
})

function showModulesPopup() {
    popups.showPopup({
        title: languages.label('LBL_MINT4_ALL_MODULES'),
        icon: 'mdi-apps',
        component: DefaultLayoutModulesPopup,
    })
}
function navigateToDashboard() {
    if(route.name === 'dashboard') {
        document.querySelector('iframe.legacy-view')?.contentWindow.document.querySelector('.nav-dashboard > li > a')?.click();
    } else {
        router.push({ name: 'dashboard' });
    }
}

const logoOnClick = () => {
    if (mdAndDown.value) {
        ux.showHideSideMenu();
    } else {
        navigateToDashboard();
    }
}
</script>

<style scoped lang="scss">
.top-bar {
    z-index: 1990;
    position: fixed;
    height: var(--v-top-nav-height);
    background: rgb(var(--v-theme-surface));
    width: 100%;
    display: flex;
    gap: 16px;
    align-items: center;
    box-shadow: 0 0 1rem #0005;
}
.nav-btn {
    padding: 6px;
}
.v-badge {
    :deep(.v-badge__badge) {
        outline: 2px solid #fff;
    }
}
.img-logo {
    display: flex;
    align-items: center;
    background: rgb(var(--v-theme-primary));
    min-height: var(--v-top-nav-height);
    height: var(--v-top-nav-height);
    min-width: 260px;
    max-width: 260px;
    width: 260px;
    z-index: 1000;
    cursor: pointer;
    overflow: hidden;

    transition: width 0.2s ease, min-width 0.2s ease, max-width 0.2s ease;

    img {
        padding: 12px 24px;
        height: 58px;
        transition: opacity 0.2s ease;
        opacity: 1;
    }

    i {
        transition: opacity 0.2s ease;
        opacity: 0;
    }

    &.img-logo-railed {
        max-width: 76px;
        min-width: 76px;
        width: 76px;
        i {
            padding: 12px 38px;
            font-size: 42px;
            color: white;
            opacity: 1;
        }

        img {
            opacity: 0;
            pointer-events: none;
        }
    }
}
</style>
