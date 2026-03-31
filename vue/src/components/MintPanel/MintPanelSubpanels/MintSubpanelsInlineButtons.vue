<template>
    <div class="action-buttons">
        <mint-button
            v-for="inlineButton in inlineButtons"
            variant="regular"
            :text="inlineButton.title"
            :icon="inlineButton.icon"
            @click.stop="inlineButton.onClick"
        />
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import SubpanelActions from '@/business/SubpanelActions'
import { useRouter } from 'vue-router';
import { useRecordViewStore } from '@/views/RecordView/RecordViewStore';
import { MenuListItem } from '@/components/MintMenuList.vue';
import MintButton from '@/components/MintButtons/MintButton.vue';

export interface MintInlineButton {
    label: string,
    widget_class: string
    [key: string]: any
}

interface Props {
    module: string
    recordId: string
    subpanel: Object
}

const props = defineProps<Props>()
const router = useRouter()
const store = useRecordViewStore()
const inlineActionMap = {
    SubPanelEditButton: 'Edit',
    SubPanelRemoveButton: 'Remove',
    SubPanelDeleteButton: 'Delete',
}

const inlineButtons = computed(() => {
    const buttonsList: MenuListItem[] = []
    props.subpanel.inlineButtons.forEach((inlineButton: MintInlineButton) => {
        const actionName = inlineActionMap[inlineButton.widget_class]
        const actionClass = SubpanelActions[actionName]
        if (typeof actionClass !== 'function') {
            console.warn(`Action ${actionName} not defined in BeanActions`)
            return
        }
        const options = {
            currentRoute: router.currentRoute.value.params,
            recordId: props.recordId
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
.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: flex-end;
    padding-right: 15px;
}
</style>