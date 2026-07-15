<template>
    <div class="list-header" :class="{ 'list-header-railed': $vuetify.display.mdAndDown }">
        <v-menu v-if="store.mode === 'list' && store.massActions.length" offset="16">
            <template v-slot:activator="{ props, isActive }">
                <MintButton
                    v-bind="props"
                    :active="isActive"
                    append-icon="mdi-menu-down"
                    :text="languages.label('LBL_ESLIST_MASS_ACTION')"
                    :disabled="(!store.selected?.length || store.isMassUpdate) && !store.allSelected"
                />
            </template>
            <MintMenuList :items="store.massActions" />
        </v-menu>
        <MintButton
            v-else-if="store.mode === 'relate' && store.itemsSelectable"
            variant="primary"
            icon="mdi-check"
            :text="languages.label('LBL_SELECT_BUTTON_LABEL')"
            @click="store.handleSelectRelate"
            :disabled="!store.selected?.length"
        />
        <MintButton
            variant="regular"
            icon="mdi-plus"
            :text="languages.label('LBL_ESLIST_ADD_FILTER')"
            @click="store.addFilterRow"
        />
        <MintButton
            class="ms-auto"
            icon="mdi-playlist-plus"
            :text="languages.label('LBL_ESLIST_COLUMNS')"
            @click="showColumnsPopup"
        />
        <MintButton
            variant="regular"
            icon="mdi-refresh"
            @click="store.getData"
            :tooltip="languages.label('LBL_ESLIST_REFRESH')"
        />
    </div>
    <div>
        <div
            v-if="(store.isHeaderChecked || store.allSelected) && store.itemsLength > store.options.itemsPerPage"
            class="mass-selection-bar"
        >
            <div v-if="!store.allSelected">
                {{ languages.label('LBL_ESLIST_MASS_SELECTION_ALL_RECORDS_PAGE').replace("{number}", store.selectedOnPageCount) }}
                <a @click="store.selectAll">{{ languages.label('LBL_ESLIST_MASS_SELECTION_ALL_RECORDS_FILTERS').replace("{number}", store.itemsLength) }}</a>
            </div>
            <div v-else>
                {{ languages.label('LBL_ESLIST_MASS_SELECTION_ALL_RECORDS_PAGE').replace("{number}", store.itemsLength) }}
                <a @click="store.clearAllSelection">{{ languages.label('LBL_ESLIST_MASS_SELECTION_CLEAR') }}</a>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, onMounted } from 'vue'
import MintButton from '@/components/MintButtons/MintButton.vue'
import MintMenuList from '@/components/MintMenuList.vue'
import { useListViewStore } from './ListViewStore'
import { useLanguagesStore } from '@/store/languages'
import { usePopupsStore } from '@/store/popups'
import ListViewColumnsPopup from './ListViewColumnsPopup.vue'

const store = useListViewStore()

const languages = useLanguagesStore()
const popups = usePopupsStore()

function showColumnsPopup() {
    popups.showPopup({
        title: languages.label('LBL_ESLIST_COLUMNS_MANAGEMENT'),
        component: ListViewColumnsPopup,
        icon: 'mdi-playlist-plus',
    })
}

onMounted(() => {
    store.massActions.map((action) => {
        if (!action.onClick) {
            console.error('Mass action missing onClick handler', action)
        }
    })
})
</script>

<style scoped lang="scss">
.list-header {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    justify-content: space-between;

    &.list-header-railed {
        overflow-y: auto;
        scrollbar-width: none;
    }

    :deep(.mint-button:focus-visible) {
        outline: 2px solid rgb(var(--v-theme-secondary-dark));
        outline-offset: 2px;
    }
}
.mass-selection-bar {
    width: 100%;
    background: #c7e8dc;
    padding: 12px 16px;
    border-radius: 4px;
    text-align: center;
    color: #1f3b2e;
    margin-bottom: 8px;
    a {
        margin-left: 8px;
        text-decoration: underline;
        cursor: pointer;
        font-weight: bold;
        color: #145D7B;
    }
}
</style>
