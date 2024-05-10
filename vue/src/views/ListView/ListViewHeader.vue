<template>
    <div class="list-header">
        <v-menu v-if="store.mode === 'list' && store.config?.config?.mass_actions?.length" offset="16">
            <template v-slot:activator="{ props, isActive }">
                <MintButton
                    v-bind="props"
                    :active="isActive"
                    append-icon="mdi-menu-down"
                    :text="languages.label('LBL_ESLIST_MASS_ACTION')"
                    :disabled="!store.selected?.length"
                />
            </template>
            <MintMenuList :items="massActions" />
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
            :variant="store.mode === 'list' ? 'primary' : 'regular'"
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
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import MintButton from '@/components/MintButtons/MintButton.vue'
import MintMenuList from '@/components/MintMenuList.vue'
import { useListViewStore } from './ListViewStore'
import { useLanguagesStore } from '@/store/languages'
import { usePopupsStore } from '@/store/popups'
import ListViewColumnsPopup from './ListViewColumnsPopup.vue'
import MassActions from './MassActions'

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

const massActions = computed(() => {
    if (!store.config?.config?.mass_actions) {
        return null
    }
    return store.config.config.mass_actions.map((action) => ({
        icon: action.icon,
        title: languages.label(action.label, store.module),
        onClick: () => MassActions[action.action]?.(store.selected),
    }))
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
}
</style>
