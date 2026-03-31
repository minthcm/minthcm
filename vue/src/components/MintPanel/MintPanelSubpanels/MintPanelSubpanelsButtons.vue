<template>
    <div class="mint-subpanel-buttons">
        <MintButton
            v-if="buttons.length === 1"
            class="mint-subpanel-create-btn"
            variant="regular"
            :text="$vuetify.display.mdAndDown ? '' : buttons[0].title"
            :icon="buttons[0].icon"
            :id="buttons[0].actionKey || null"
            :name="buttons[0].actionKey || null"
            @click.stop="buttons[0].onClick"
        />
        <v-menu v-else-if="buttons.length > 1" offset="16">
            <template v-slot:activator="{ props, isActive }">
                <MintButton
                    class="mint-subpanel-dropdown-btn"
                    v-bind="props"
                    :active="isActive"
                    variant="regular"
                    append-icon="mdi-menu-down"
                    :text="$vuetify.display.mdAndDown ? '' : languages.label('LBL_ESLIST_ACTIONS')"
                />
            </template>
            <MintMenuList :items="buttons" />
        </v-menu>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore'
import { useLanguagesStore } from '@/store/languages'
import MintButton from '@/components/MintButtons/MintButton.vue'
import MintMenuList, { MenuListItem } from '@/components/MintMenuList.vue'
import SubpanelActions from '@/business/SubpanelActions'

interface SubpanelTopButton {
    widget_class: string
}

const props = defineProps({
    subpanel: {
        type: Object,
        required: true,
    },
})

const router = useRouter()
const store = useRecordViewStore()
const languages = useLanguagesStore()
const actionMap = {
    SubPanelTopButtonQuickCreate: 'Create',
    SubPanelTopCreateButton: 'Create',
    SubPanelTopSelectButton: 'Select',
    SubPanelTopButtonCreateCosts: 'CreateCosts',
    SubPanelTopSelectButton_Delegations: 'SelectWorkScheduleForDelegation',
}

const buttons = computed<MenuListItem[]>(() => {
    const buttonsList: MenuListItem[] = []
    props.subpanel.properties.top_buttons?.forEach((button: SubpanelTopButton) => {
        const actionName = actionMap[button.widget_class]
        const actionClass = SubpanelActions[actionName]
        if (typeof actionClass !== 'function') {
            console.warn(`Action ${actionName} not defined in BeanActions`)
            return
        }
        const options = {
            currentRoute: router.currentRoute.value.params,
        }
        const actionObject = new actionClass(store.bean, props.subpanel, options)
        if (actionObject.isAvailable()) {
            buttonsList.push(actionObject.toMenuListItem())
        }
    })
    return buttonsList
})
</script>

<style scoped lang="scss">
.mint-subpanel-buttons {
    display: flex;
    align-items: center;

    .mint-subpanel-create-btn,
    .mint-subpanel-dropdown-btn {
        pointer-events: all;
    }
}
</style>
